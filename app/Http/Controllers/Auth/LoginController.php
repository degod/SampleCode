<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    /**
     * Get a validator for an authentication request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        return Validator::make($data, [
            'username' => 'required',
            'password' => 'required'
        ]);
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function logout()
    {
        request()->session()->flush();
        \Auth::logout();
        
        return redirect()->route('partner.home');
    }

    public function login(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $field = filter_var($request->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $request->merge([$field => $request->username]);

        if (\Auth::attempt($request->only($field, 'password'))){
            return redirect()->route('partner.dashboard');
        } else {
            return redirect()->back()->withErrors("Invalid login username or password")->withInput();
        }
        
    }
}