<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
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

    //create job
    public function createJob(){
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes= JobType::orderBy('name','ASC')->where('status',1)->get();
        return view('front.account.job.create',[
            'categories'=>$categories,
            'jobTypes'=>$jobTypes
        ]);
    }

    //save job
    public function saveJob(Request $request){
        $rules=[
            'title'=>'required|min:10|max:200',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|max:50',
            'description'=>'required',
            'company_name'=>'required|min:3|max:50',
        ];
        $validator=validator::make($request->all(),$rules);
        if($validator->passes()){
            $job=new Job();
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->jobType;
            $job->vacancy=$request->vacancy;
            $job->user_id=Auth::user()->id;
            $job->salary=$request->salary;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualification=$request->qualification;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->website;
            $job->save();
            return redirect()->route('account.myJob')->with('success', 'Job added successfully');
        }else{
            return redirect()->route('account.createJob')->withInput()->withErrors($validator);
        }
    }

    //myjob
    public function myJob(){
        $jobs=Job::where('user_id',Auth::user()->id)->with('jobType')->orderBy('created_at','DESC')->paginate(5);
        return view('front.account.job.my-jobs',[
            'jobs'=>$jobs
        ]);
    }

    //to show the page which edit my job
    public function editJob(Request $request ,$id){
        $categories=Category::orderBy('name','ASC')->where('status',1)->get();
        $jobTypes = JobType::orderBy('name', 'ASC')->where('status', 1)->get();
        $job=Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$id
        ])->firstOrFail();
        if($job==null){
            abort(404);
        }
        return view('front.account.job.edit',[
            'categories'=>$categories,
            'jobTypes'=>$jobTypes,
            'job'=>$job
            ]);
    }

    //to update my job
    public function updateJob(Request $request, $id){
        $rules=[
            'title'=>'required|min:10|max:200',
            'category'=>'required',
            'jobType'=>'required',
            'vacancy'=>'required|integer',
            'location'=>'required|max:50',
            'description'=>'required',
            'company_name'=>'required|min:3|max:50',
        ];
        $validator=validator::make($request->all(),$rules);
        if($validator->passes()){
            $job = Job::findOrFail($id);
            $job->title=$request->title;
            $job->category_id=$request->category;
            $job->job_type_id=$request->jobType;
            $job->vacancy=$request->vacancy;
            $job->user_id=Auth::user()->id;
            $job->salary=$request->salary;
            $job->location=$request->location;
            $job->description=$request->description;
            $job->benefits=$request->benefits;
            $job->responsibility=$request->responsibility;
            $job->qualification=$request->qualification;
            $job->keywords=$request->keywords;
            $job->experience=$request->experience;
            $job->company_name=$request->company_name;
            $job->company_location=$request->company_location;
            $job->company_website=$request->website;
            $job->save();
            return redirect()->route('account.myJob',$id)->with('success', 'Job updated successfully');
        }else{
            return redirect()->route('account.editJob', $id)->withInput()->withErrors($validator);
        }
    }

    //delete job
    public function destroy(Request $request){
        $job=Job::where([
            'user_id'=>Auth::user()->id,
            'id'=>$request->jobId
        ])->first();
        if($job==null){
            session()->flash('error','job not found');
            return response()->json([
                'status'=>true
            ]);

        }
        Job::where('id',$request->jobId)->delete();
        session()->flash('success','job deleted successfully');
            return response()->json([
                'status'=>true
            ]);
    }
    //to show my job application
    public function myJobApplication(){
        $JobApplications= JobApplication::where('user_id',Auth::user()->id)->with(['job','job.jobType','job.applications'])->orderBy('created_at','DESC')->paginate(10);
        return view('front.account.job.my-job-application',[
            'JobApplications'=>$JobApplications
        ]);
    }
    //to delete my ob application
    public function removeJob(Request $request){
        $jobApplication= JobApplication::where([
            'id'=>$request->id,
            'user_id'=>Auth::user()->id
        ])->first();
        if($jobApplication==null){
            session()->flash('error','Job Application not found');
            return response([
                'status'=>false
            ]);
        }
        JobApplication::find($request->id)->delete();
        session()->flash('success','Job Application removed successfully');
            return response([
                'status'=>true
            ]);
    }

    //to show the page of saved jobs
    public function savedJobs(){
        $savedJobs=SavedJob::where([
            'user_id'=>Auth::user()->id
        ])->with('job','job.jobType','job.applications')->orderBy('created_at','DESC')->paginate(10);
        return view('front.account.job.saved-jobs',[
            'savedJobs'=>$savedJobs
        ]);
    }
    //remove saved jobs
    public function removeSavedJob(Request $request){
        $savedJob=SavedJob::where([
            'id'=>$request->id,
            'user_id'=>Auth::user()->id
        ]);
        if($savedJob==null){
            session()->flash('error','job not found');
            return response([
                'status'=>false,
                'message' => ' job not found'
            ]);
        }
        SavedJob::find($request->id)->delete();
        session()->flash('success','saved job deleted successfully');
            return response([
                'status'=>true,
                'message' => 'saved job deleted successfully'
            ]);
    }
    //to update password
    public function updatePassword(Request $request){
        $validator=Validator::make($request->all(),[
            'old_password'=>'required',
            'new_password'=>'required|min:5',
            'confirm_password'=>'required|same:new_password'
        ]);
        if ($validator->fails()) {
        return redirect()->back()->withInput()->withErrors($validator);
    }

        if (!Hash::check($request->old_password, Auth::user()->password)) {
        return redirect()->back()->with('error', 'Your old password is incorrect');
        }
        
        if ($validator->passes()){
            $user= User::find(Auth::user()->id);
            $user->password=Hash::make($request->new_password);
            $user->save();
            return redirect()->route('account.updatePassword')->with('success','you updated password successfuly');
        }
    return redirect()->back()->with('success', 'You updated your password successfully');
    }



}
