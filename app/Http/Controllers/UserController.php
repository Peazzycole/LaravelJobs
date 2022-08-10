<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function create()
    {
        return view('users.register');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => 'required|confirmed|min:6'
        ]);

        // Hash Password
        $formFields['password'] = bcrypt($formFields['password']);

        // create User
        $user = User::create($formFields);

        auth()->login($user);
        return redirect('/')->with('message', 'User created and Logged in');
    }

    // logout User

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('message', 'You have been Logged Out');
    }

    public function login()
    {
        return view('users.login');
    }

    // authenticate Login

    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            'email' => ['required', 'email'],
            'password' => 'required'
        ]);

        if (auth()->attempt($formFields)) {      // attempt to log the user in
            $request->session()->regenerate();

            return redirect('/')->with('message', 'Successfully Logged In');
        }

        return back()->withErrors(['email' => 'Invalid Crdentials'])->onlyInput('email');
    }
}
