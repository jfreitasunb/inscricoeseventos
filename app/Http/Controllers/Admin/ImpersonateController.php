<?php

namespace InscricoesEventosMat\Http\Controllers\Admin;

use Auth;
use Illuminate\Http\Request;
use InscricoesEventosMat\Http\Controllers\Controller;
use InscricoesEventosMat\Models\User;

class ImpersonateController extends Controller
{   

    public function PermiteImpersontate()
    {
        if (Auth::user()->user_type !== 'admin'){
            return redirect()->back();
        }
    }

    public function index()
    {

        $this->PermiteImpersontate();

    	return view('templates.partials.admin.impersonate');

    }

    public function store(Request $request)
    {

        $this->PermiteImpersontate();

    	$this->validate($request, [
    		'email' => 'required|email|exists:users',
    	]);

    	$email = strtolower(trim($request->email));

    	$user = User::where('email', $email)->first();

    	session()->put('impersonate', $user->id_user);

        session()->put('impersonate_user_type', $user->user_type);

        if ($user->user_type === 'recomendante') {
            
            return redirect()->route('menu.recomendante');
        }

        if ($user->user_type === 'candidato') {
            
            return redirect()->route('menu.recomendante');
        }

    }

    public function destroy()
    {

        $this->PermiteImpersontate();
        
        session()->forget('impersonate');

        session()->forget('impersonate_user_type');

        return redirect('/');
    }
}
