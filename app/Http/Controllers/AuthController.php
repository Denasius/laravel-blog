<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class AuthController extends Controller
{
    public function registerForm()
    {
    	return view('pages.register');
    }

    public function register(Request $request)
    {
    	$this->validate($request, [
    		'name'		=> 'required',
    		'email'		=> 'required|email|unique:users',
    		'password'	=> 'required'
    	]);

    	$user = User::add($request->all());
    	$user->generatePassword($request->get('password'));

    	return redirect('/login');
    }

    public function loginForm()
    {
    	return view('pages.login');
    }

    public function login(Request $request)
    {
    	$this->validate($request, [
    		'email'		=> 'required|email',
    		'password'	=> 'required'
    	]);

    	// 1. Проверить и залогинить пользователя на основе email и password
    	// 2. Если пользователь ввел неправильный логин, или пароль, вывожу флеш сообщение
    	// 3. Иначе редирект на главную

    	$result = Auth::attempt([
    		'email'		=> $request->get('email'),
    		'password'	=> $request->get('password')
    	]);
    	if ( $result ) {
    		return redirect('/');
    	} 
    	return redirect()->back()->with('status', 'Неправильный логи, или пароль!');
    	
    }

    public function logout()
    {
    	Auth::logout();
    	return redirect('/login');
    }
}
