<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index(){
        $users= User::orderBy('created_at','DESC')->paginate(2);
        return view('admin.users.list',['users'=>$users]);
    }

    public function edit($id){
        $user=User::findOrFail($id);
        return view('admin.users.edit',['user'=>$user]);
    }

    public function update(Request $request,$id){
        $validator = validator::make($request->all(),[
            'name'=>'required|min:3|max:30',
            'email'=>'required|email|unique:users,email,'.$id.',id',
        ]);
        if($validator->passes()){
            $user=User::find($id);
            $user->name=$request->name;
            $user->email=$request->email;
            $user->mobile=$request->mobile;
            $user->designation=$request->designation;
            $user->save();
            return redirect()->route('admin.users')->with('success','User information updated successfully');
        }else{
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function destroy(Request $request, $id) {
        $user = User::find($id);

        if ($user == null) {
            session()->flash('error', 'User not found');
            return response()->json(['status' => false]);
        }

        $user->delete();
        session()->flash('success', 'User deleted successfully');

        return response()->json(['status' => true]);
    }
}
