<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use App\Mail\EnviarMail;
use Illuminate\Routing\Exceptions\InvalidSignatureException;

class CodigoController extends Controller
{
    public function VerificarCodigo(Request $request,$id){
        $rules=[
            'codigo' => 'required|string|min:6|max:6',
        ];
        $mensajes=[
            'codigo.required'=>'Ingrese el código',
            'codigo.min'=>'Ingrese los 6 dígitos',
            'codigo.max'=>'ingrese los 6 digitos'
        ];
        $validator = Validator::make($request->all(), $rules, $mensajes);
        if($validator->fails()){
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::find($id);
        if (!$user || $user->codigo !== $request->codigo) {
            return redirect()->back()->with('error', 'Código incorrecto.');

        }
        Auth::login($user); // Esto crea la sesión en el navegador
        $request->session()->regenerate();

        $user->codigo=null;
        $user->save();
        return redirect()->intended('/dashboard');
    }

    public function vistaCodigo(Request $request ){
        return view('emails.codigo', ['id' => $request->id]);
    }
}
