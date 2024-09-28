<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
        $id = Auth::user()->id;
        $user = User::where('id',$id)->first();
        return view('front.account.profile', ['user'=> $user]);
    }
    //to update profile
    public function updateProfile(Request $request){
        $id = Auth::user()->id;
        $validator = validator::make($request->all(),[
            'name'=>'required|min:5|max:30',
            'email'=>'required|email|unique:users,email,'.$id.',id',
        ]);
        if($validator->passes()){
            $user=User::find(Auth::user()->id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->mobile=$request->mobile;
            $user->designation=$request->designation;
            $user->save();
            return redirect()->route('account.profile')->with('success','profile updated successfully');
        }else{
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
    }
    //update profile picture
    public function updateProfilePic(Request $request) {
        $id = Auth::user()->id;
        $validator = Validator::make($request->all(), [
            'image' => 'required|image'
        ]);

        if (!empty($request->image)) {
            $image = $request->image;
            $ext = $image->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $image->move(public_path('/profile_pic/'), $imageName);

            //to delete old image
            if (File::exists(public_path('/profile_pic/'.Auth::user()->image))) {
                File::delete(public_path('/profile_pic/'.Auth::user()->image));
            }
            User::where('id', $id)->update(['image' => $imageName]);

            session()->flash('success', "Profile Picture Updated Successfully");

            return response()->json([
                'status' => true,
                'errors' => []
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    //logout
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
