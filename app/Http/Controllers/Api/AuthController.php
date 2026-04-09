<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->verifyTurnstile($request);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'token' => $user->createToken('kulinar')->plainTextToken,
            'user'  => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $this->verifyTurnstile($request);

        $data = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Pogrešni podaci za prijavu.'],
            ]);
        }

        return response()->json([
            'token' => $user->createToken('kulinar')->plainTextToken,
            'user'  => $user,
        ]);
    }

    public function googleRedirect()
    {
        return response()->json([
            'url' => Socialite::driver('google')->stateless()->redirect()->getTargetUrl(),
        ]);
    }

    public function googleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Google autentifikacija nije uspjela.'], 422);
        }

        $user = User::updateOrCreate(
            ['google_id' => $googleUser->getId()],
            [
                'name'              => $googleUser->getName(),
                'email'             => $googleUser->getEmail(),
                'avatar'            => $googleUser->getAvatar(),
                'email_verified_at' => now(),
            ]
        );

        $token = $user->createToken('kulinar')->plainTextToken;
        $userData = json_encode($user);

        // Vrati HTML koji spremi token u localStorage i preusmjeri na Flutter app
        $html = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"></head>
<body>
<script>
  try {
    localStorage.setItem('kulinar_token', {$this->jsString($token)});
    localStorage.setItem('kulinar_user', {$this->jsString($userData)});
  } catch(e) {}
  window.location.href = '/';
</script>
<p>Preusmjerava...</p>
</body>
</html>
HTML;

        return response($html, 200)->header('Content-Type', 'text/html');
    }

    private function jsString(string $value): string
    {
        return json_encode($value);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Odjavljeni ste.']);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name'                  => 'sometimes|string|max:255',
            'email'                 => 'sometimes|email|unique:users,email,' . $user->id,
            'password'              => 'sometimes|string|min:8|confirmed',
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user->fresh());
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $user->delete();

        return response()->json(['message' => 'Račun je obrisan.']);
    }

    private function verifyTurnstile(Request $request): void
    {
        $token = $request->input('cf_turnstile_response');

        if (! $token) {
            throw ValidationException::withMessages([
                'cf_turnstile_response' => ['CAPTCHA verifikacija je obavezna.'],
            ]);
        }

        // Mobile klijenti preskače CF Turnstile
        if ($token === 'mobile-bypass') {
            return;
        }

        $response = Http::asForm()->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
            'secret'   => config('services.turnstile.secret'),
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);

        if (! $response->json('success')) {
            throw ValidationException::withMessages([
                'cf_turnstile_response' => ['CAPTCHA verifikacija nije uspjela.'],
            ]);
        }
    }
}
