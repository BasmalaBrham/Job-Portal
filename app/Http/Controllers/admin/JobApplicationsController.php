<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class JobApplicationsController extends Controller
{
    public function index(){
        $jobApllications=JobApplication::orderBy('created_at','DESC')
        ->with('job','user','employer')
        ->paginate(10);
        return view('admin.job-applications.list',[
            'jobApllications'=>$jobApllications
        ]);
    }
    public function destroy(Request $request, $id) {
        $user = JobApplication::find($id);
        if ($user == null) {
            session()->flash('error', 'Either job apllication deleted or not found');
            return response()->json(['status' => false]);
        }
        $user->delete();
        session()->flash('success', 'Job apllication deleted successfully');
        return response()->json(['status' => true ]);
    }
}
