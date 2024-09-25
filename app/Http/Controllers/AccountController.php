<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function registration(){
        return view('front.account.registration');
    }

    //to save user info
    public function processRegistration(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => "required",
            'email' => "required|email|unique:users,email",
            'password' => 'required|min:5|same:confirm_password',
            'confirm_password' => 'required',
        ]);
        if($validator->fails()){
            return redirect()->route('account.registration')->withInput()->withErrors($validator);
        }
        if ($validator->passes()) {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('account.login')->with('success','you have register successfuly');
        }
    }
    public function login(){
        return view('front.account.login');
    }

    public function authenticate(Request $request){
        $validator= validator::make($request->all(),[
            'email'=>'required|email',
            'password'=>'required|min:5',
        ]);
       if($validator->passes()){
            if(Auth::attempt(['email'=>$request->email , 'password'=>$request->password])){
                return redirect()->route('account.profile');
            }else{
                return redirect()->route('account.login')->with('error','Either email/password is invalid');
            }

       }else{
            return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
       }
    }

    //to show user profile page
    public function profile(){
        $user = User::find(Auth::user()->id);
        return view('front.account.profile', compact('user'));
    }

    //logout
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
