<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Mail\EnviarMail;
use Illuminate\Support\Str;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

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
        $codigo=str_pad(rand(0,999999),6,'0', STR_PAD_LEFT);
        $user->codigo=$codigo;
        $user->save();

        //ruta firmada
         $link = URL::temporarySignedRoute('activar',now()->addMinutes(15),['id' => $user->id]);
        try {
            Mail::to($user->email)->send(new EnviarMail($user, $link, $codigo));

            return redirect()->back()->with('status', '¡Código enviado! Revisa tu bandeja de entrada.');
        } catch (\Exception $e) {
           return redirect()->back()->withErrors(['email' => 'Error al enviar el correo: ' . $e->getMessage()]);
        }
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
