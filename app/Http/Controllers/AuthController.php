<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\VerificationCode;

class AuthController extends Controller
{
    public function registerForm(Request $request)
    {
        $role = $request->query('role', old('role', null));
        return view('auth.register', compact('role'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', 'unique:users,email'],
            'password' => 'required|string|confirmed|min:6',
            'role' => 'required|string|in:vidangeur,menagere',
            'phone' => ['nullable', 'string', 'max:20'],
            'verification_channel' => 'required|string|in:email,phone,both',
        ], [
            'email.email' => 'L’adresse email est invalide.',
            'email.unique' => 'Cette adresse email est déjà utilisée.',
            'phone.max' => 'Le numéro de téléphone est trop long.',
            'verification_channel.required' => 'Veuillez choisir un canal de vérification.',
        ]);

        $hasEmail = !empty($request->email);
        $hasPhone = !empty($request->phone);
        $channel = $request->verification_channel;

        if ($channel === 'email' && !$hasEmail) {
            return back()->withErrors(['email' => 'Une adresse email est requise pour la vérification par email.'])->withInput();
        }

        if ($channel === 'phone' && !$hasPhone) {
            return back()->withErrors(['phone' => 'Un numéro de téléphone est requis pour la vérification par téléphone.'])->withInput();
        }

        if ($channel === 'both' && (!$hasEmail || !$hasPhone)) {
            return back()->withErrors(['email' => 'Pour la vérification par les deux canaux, fournissez un email et un téléphone.', 'phone' => 'Pour la vérification par les deux canaux, fournissez un email et un téléphone.'])->withInput();
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = 'uploads/profiles/' . time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('uploads/profiles'), basename($photoPath));
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'photo' => $photoPath,
            'country' => $request->country ?? null,
            'region' => $request->region ?? null,
            'city' => $request->city ?? null,
            'quarter' => $request->quarter ?? null,
            'address' => $request->address ?? null,
            'birthdate' => $request->birthdate ?? null,
            'verification_code' => $code,
            'verification_channel' => $channel,
        ]);

        // Assigner des coordonnees GPS basees sur la ville et le quartier
        if ($request->role === 'vidangeur') {
            $coords = $this->getCoordsFromQuarter($request->city, $request->quarter, $user->id);
            $user->latitude = $coords[0];
            $user->longitude = $coords[1];
            $user->save();
        }

        $emailSent = false;
        if ($channel === 'email' || $channel === 'both') {
            try {
                Mail::mailer('smtp')->to($user->email)->send(new VerificationCode($code, $user->name));
                $emailSent = true;
            } catch (\Exception $e) {
                Log::error('Echec envoi email verification: '.$e->getMessage());
                if (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'refused')) {
                    $message = 'Echec d\'envoi : verifiez votre connexion internet.';
                }
            }
        }

        Auth::login($user);
        return $this->redirectByRole($user->role)->with('success', 'Compte cree avec succes.');
    }

    private $cityBounds = [
        'douala' => ['lat' => [4.00, 4.10], 'lng' => [9.65, 9.85]],
        'yaounde' => ['lat' => [3.80, 3.92], 'lng' => [11.40, 11.55]],
        'yaoundé' => ['lat' => [3.80, 3.92], 'lng' => [11.40, 11.55]],
        'bafoussam' => ['lat' => [5.45, 5.55], 'lng' => [10.38, 10.45]],
        'bafang' => ['lat' => [5.14, 5.20], 'lng' => [10.16, 10.22]],
        'bamenda' => ['lat' => [5.92, 6.00], 'lng' => [10.12, 10.18]],
        'garoua' => ['lat' => [9.27, 9.35], 'lng' => [13.37, 13.43]],
        'maroua' => ['lat' => [10.56, 10.62], 'lng' => [14.30, 14.35]],
        'nkongsamba' => ['lat' => [4.93, 5.00], 'lng' => [9.91, 9.96]],
        'kribi' => ['lat' => [2.91, 2.96], 'lng' => [9.89, 9.94]],
        'limbe' => ['lat' => [3.99, 4.06], 'lng' => [9.19, 9.25]],
        'buea' => ['lat' => [4.14, 4.19], 'lng' => [9.21, 9.26]],
        'dschang' => ['lat' => [5.42, 5.48], 'lng' => [10.04, 10.10]],
        'foumban' => ['lat' => [5.71, 5.76], 'lng' => [10.88, 10.93]],
        'kumba' => ['lat' => [4.61, 4.66], 'lng' => [9.41, 9.46]],
        'ebolowa' => ['lat' => [2.88, 2.93], 'lng' => [11.13, 11.18]],
        'sangmelima' => ['lat' => [2.91, 2.96], 'lng' => [11.96, 12.01]],
        'bertoua' => ['lat' => [4.56, 4.62], 'lng' => [13.66, 13.71]],
        'ngaoundere' => ['lat' => [7.29, 7.35], 'lng' => [13.56, 13.61]],
        'edea' => ['lat' => [3.78, 3.83], 'lng' => [10.11, 10.16]],
        'mbouda' => ['lat' => [5.61, 5.66], 'lng' => [10.24, 10.30]],
    ];

    private function getCoordsFromQuarter($city, $quarter, $userId)
    {
        $key = strtolower(trim($city ?? ''));
        $bounds = $this->cityBounds[$key] ?? ['lat' => [4.00, 4.10], 'lng' => [9.65, 9.85]];
        $hash = crc32(strtolower(trim($quarter ?? '')) . '_' . $userId);
        $ratioLat = (($hash & 0xFFFF) % 1000) / 1000;
        $ratioLng = (($hash >> 16) % 1000) / 1000;
        $lat = $bounds['lat'][0] + $ratioLat * ($bounds['lat'][1] - $bounds['lat'][0]);
        $lng = $bounds['lng'][0] + $ratioLng * ($bounds['lng'][1] - $bounds['lng'][0]);
        return [round($lat, 7), round($lng, 7)];
    }

    public function verificationForm()
    {
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);
        $user = Auth::user();

        if (!$user || $user->verification_code !== $request->code) {
            return back()->withErrors(['code' => 'Code invalide.']);
        }

        if ($user->verification_channel === 'phone' && $user->phone) {
            $user->phone_verified_at = now();
        } elseif ($user->verification_channel === 'email' && $user->email) {
            $user->email_verified_at = now();
        } elseif ($user->verification_channel === 'both') {
            if ($user->phone) {
                $user->phone_verified_at = now();
            }
            if ($user->email) {
                $user->email_verified_at = now();
            }
        }
        $user->verification_code = null;
        $user->save();

        return $this->redirectByRole($user->role)
            ->with('success', 'Compte verifie avec succes.');
    }

    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=> 'required|email',
            'password'=> 'required|string',
        ]);

        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user()->role);
        }

        return back()->withErrors(['email' => 'Identifiants invalides']);
    }

    public function resendCode(Request $request)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = $code;
        $user->save();

        $sent = false;
        try {
            Mail::mailer('smtp')->to($user->email)->send(new VerificationCode($code, $user->name));
            $sent = true;
        } catch (\Exception $e) {
            Log::error('Echec renvoi email verification: '.$e->getMessage());
        }

        if ($sent) {
            return back()->with('message', 'Un nouveau code vous a ete envoye par email.');
        }

        Log::error('Echec renvoi email verification pour user #'.$user->id);
        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->save();
        if ($e && (str_contains($e->getMessage(), 'Connection') || str_contains($e->getMessage(), 'timeout') || str_contains($e->getMessage(), 'refused'))) {
            return $this->redirectByRole($user->role)->with('success', 'Verification automatique (envoi impossible).');
        }
        return $this->redirectByRole($user->role)->with('success', 'Verification automatique (erreur technique).');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    private function redirectByRole($role)
    {
        return match($role) {
            'menagere' => redirect()->route('menagere.dashboard'),
            'vidangeur' => redirect()->route('vidangeur.dashboard'),
            'admin' => redirect()->route('dashboard'),
            default => redirect()->route('home'),
        };
    }
}
