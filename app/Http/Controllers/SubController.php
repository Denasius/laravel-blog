<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subscription;
use App\Mail\SubscribeEmail;

class SubController extends Controller
{
    public function subscribe(Request $request)
    {
    	$this->validate($request, [
    		'email'	=> 'required|email|unique:subscriptions'
    	]);

    	$subs = Subscription::add($request->get('email'));
        $subs->generateToken();

    	\Mail::to($subs)->send(new SubscribeEmail($subs));

    	return redirect()->back()->with('status', 'Проверте вашу почту');
    }

    public function verify($token)
    {
    	$subs = Subscription::where('token', $token)->firstOrFail();
    	$subs->token = null;
    	$subs->save();

    	return redirect('')->with('status', 'Ваша почта подтверждена!');
    }
}
