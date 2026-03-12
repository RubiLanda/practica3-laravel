<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
// Elimina la línea de PragmaRX que tienes y pon esta:
use Google2FA;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $recaptchaResponse = $request->input('g-recaptcha-response');
    
        $verify = Http::withoutVerifying() //esto sera por mientras, ya que pide certificados HTTPS y no los tenemos
        ->asForm()
        ->post('https://www.google.com/recaptcha/api/siteverify', [
        'secret' => env('RECAPTCHA_SECRET_KEY'),
        'response' => $recaptchaResponse,
        'remoteip' => $request->ip(),
        ]);

        if (!$verify->json('success')) {
            return redirect()->back()
                ->withErrors(['error_superior' => 'Por favor, verifica que no eres un robot.'])
                ->withInput();
        }

        $credenciales =$request->only('email','password');
        if (!Auth::validate($credenciales)){
            return redirect()->back()
            ->withErrors(['error_superior' => 'Credenciales incorrectas'])
            ->withInput();
        }
        $user = User::where('email', $request->email)->first();
        $google2fa = new Google2FA();

        // Si el usuario NO tiene configurado el 2FA (primera vez)
        if (!$user->google2fa_secret) {
            $secret = Google2FA::generateSecretKey();
            $user->google2fa_secret = $secret; //encriptado para q no se pueda escanear con una camara normal
            $user->save();

            $qrCodeUrl =Google2FA::getQRCodeUrl(
                'MiAppLaravel', // nombre de la app en el cel
                $user->email,
                $secret
            );

            $qrCodeImage = QrCode::size(200)->generate($qrCodeUrl);

            //guardamos el ID para la siguiente sesion
            session(['2fa_user_id' => $user->id]);

            return view('auth.2fa_setup', compact('qrCodeImage', 'secret'));
        }

            // Si ya tiene el factor solo le pedimos el codigo
            session(['2fa_user_id' => $user->id]);
            return view('auth.2fa_verify');
        
    }

    public function verify2fa(Request $request)
    {
       $request->validate([
        'one_time_password' => [
            'required',
            'numeric',      
            'digits:6',     
        ],
    ], [
        // Mensajes personalizados
        'one_time_password.numeric' => 'El código debe contener solo números.',
        'one_time_password.digits' => 'El código debe tener exactamente 6 dígitos.',
    ]);

        // Recuperamos al usuario que intentó loguearse
        $userId = session('2fa_user_id');
        if (!$userId) {
            return redirect()->route('login')->withErrors(['error_superior' => 'Sesión expirada.']);
        }

        $user = User::findOrFail($userId);
        $google2fa = new Google2FA();

        // Verificamos el código (el secreto se desencripta solo gracias al modelo)
        $valid = Google2FA::verifyKey($user->google2fa_secret, $request->one_time_password);

        if ($valid) {
            // iniciamos sesión oficialmente
            Auth::login($user);
            
            // Limpiamos la sesión temporal
            session()->forget('2fa_user_id');

            return redirect()->intended(RouteServiceProvider::HOME);
        }

        return back()->withErrors(['error_2fa' => 'El código ingresado es incorrecto o ya expiró.']);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
