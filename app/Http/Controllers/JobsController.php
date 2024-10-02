<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobType;

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
        return view('front.account.job.jobDetail',['job'=>$job]);
    }

}
