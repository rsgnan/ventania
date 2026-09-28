<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('login');
    }

    public function login(Request $request)
    {

        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('username', $credentials['username'])->first();

        if (
            $user !== null &&
            ! $user->is_active &&
            Hash::check($credentials['password'], $user->password)
        ) {
            return back()->withErrors([
                'username' => 'Este usuário está desativado.',
            ]);
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'username' => 'Usuário ou senha inválidos.',
            ]);
        }

        $request->session()->regenerate();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
