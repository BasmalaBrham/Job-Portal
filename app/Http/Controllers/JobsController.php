<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJob;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class JobsController extends Controller
{
    public function index(Request $request){
        $categories = Category::where('status', '1')->get();
        $jobTypes = JobType::where('status', '1')->get();
        // ابدأ الاستعلام من دون استخدام get()
        $Jobs = Job::where('status', '1');

        //search using keyword
        if(!empty($request->Keyword)){
            $Jobs = $Jobs->where(function($query) use($request){
                $query->orwhere('title','like','%'.$request->Keyword.'%');
                $query->orwhere('keywords','like','%'.$request->Keyword.'%');
            });
        }

        //search using location
        if(!empty($request->location)){
            $Jobs = $Jobs->where('location',$request->location);
        }

        //search using category
        if(!empty($request->category)){
            $Jobs = $Jobs->where('category_id',$request->category);
        }

        //search using Job Type
        $jobTypeArray = [];
        if (!empty($request->jobType)) {
            $jobTypeArray = explode(',', $request->jobType);
            $Jobs = $Jobs->whereIn('job_type_id', $jobTypeArray);
        }

        //search using experience
        if(!empty($request->experience)){
            $Jobs = $Jobs->where('experience', $request->experience);
        }

        // ترتيب النتائج حسب تاريخ الإنشاء
        if(!empty($request->sort) && $request->sort == '0'){
            $Jobs = $Jobs->orderBy('created_at', 'ASC');
        }else{
            $Jobs = $Jobs->orderBy('created_at', 'DESC');
        }

        // تطبيق العلاقات بعد إتمام التصفية والترتيب
        $Jobs = $Jobs->with(['jobtype', 'category'])->paginate(9);

        return view("front.account.job.jobs", [
            'categories' => $categories,
            'jobTypes' => $jobTypes,
            'Jobs' => $Jobs,
            'jobTypeArray' => $jobTypeArray
        ]);
    }

    //show job detail page
    public function detail($id){
        $job = Job::where(['id'=>$id ,'status'=>1])->with(['jobType','category'])->first();
        if($job == null){
            abort(404);
        }
        $count=0;
        if(Auth::user()){
            $count= SavedJob::where([
                'user_id'=>Auth::user()->id,
                'job_id'=>$id
            ])->count();
        }

        //fetch applications
        $applications= JobApplication::where('job_id',$id)->with('user')->get();
        return view('front.account.job.jobDetail',['job'=>$job,
                                                    'count'=>$count,
                                                    'applications'=>$applications]);
    }

    //to apply on job
    public function applyJob(Request $request){
        $id = $request->id;

        // to get job
        $job = Job::where('id', $id)->first();

        // check the job if it in db
        if (!$job) {
            session()->flash('error', 'Job does not exist');
            return response()->json([
                'status' => false,
                'message' => 'Job does not exist'
            ]);
        }

        // check if the user apply to its job
        if ($job->user_id == Auth::user()->id) {
            session()->flash('error', 'You cannot apply to your own job');
            return response()->json([
                'status' => false,
                'message' => 'You cannot apply to your own job'
            ]);
        }

        // check if user olready apply to this job
        $jobApplicationExists = JobApplication::where([
            'user_id' => Auth::user()->id,
            'job_id' => $id
        ])->exists(); // استخدم exists للتحقق بسرعة أكبر

        if ($jobApplicationExists) {
            session()->flash('error', 'You have already applied for this job');
            return response()->json([
                'status' => false,
                'message' => 'You have already applied for this job'
            ]);
        }

        // create new job application
        $application = new JobApplication();
        $application->job_id = $id;
        $application->user_id = Auth::user()->id;
        $application->employer_id = $job->user_id;
        $application->created_at = now();
        $application->save();

        if ($application->save()) {
            session()->flash('success', 'You have successfully applied');
            return response()->json([
                'status' => true,
                'message' => 'You have successfully applied'
            ]);
        } else {
            session()->flash('error', 'Failed to apply. Please try again.');
            return response()->json([
                'status' => false,
                'message' => 'Failed to apply. Please try again.'
            ]);
        }

        //send notificatio email to employer
        $employer=User::where('id',$job->user_id)->first();
        $mailData=[
            'employer'=>$employer,
            'user'=>Auth::user(),
            'job'=>$job
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($mailData));
    }
    //to save job
    public function saveJob(Request $request){
        $id = $request->id;
        $job = Job::find($id);
        if($job==null){
            session()->flash('error', 'Job not found');
            return response()->json([
                'status' => false,
                'message' => 'Job not found'
            ]);
        }
        //if already save
        $count= SavedJob::where([
            'user_id'=>Auth::user()->id,
            'job_id'=>$id
        ])->count();

        if($count >0){
            session()->flash('error', 'You already saved this job');
            return response()->json([
                'status' => false,
                'message' => 'Job already saved this job'
            ]);
        }

        $savedJob= new SavedJob;
        $savedJob->job_id=$id;
        $savedJob->user_id=Auth::user()->id;
        $savedJob->save();
        if ($savedJob->save()) {
            session()->flash('success', 'You have successfully saved job');
            return response()->json([
                'status' => true,
                'message' => 'You have successfully saved job'
            ]);
        }

    }
}
