@extends('front.layout.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('admin.job')}}">Jobs</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('admin.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.layout.message')
                <form action="{{ route('admin.job.update', $job->id) }}" method="POST" id="editJobForm" name="editJobForm">
                    @csrf
                    @method('PUT')
                    <div class="card border-0 shadow mb-4 ">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Edit Job Details</h3>
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" id="title" value="{{$job->title}}" name="title" class="form-control @error('title') is-invalid @enderror">
                                    @error('title')
                                        <p class="invalid-feedback">{{$message}}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" value="{{$job->category}}" id="category" class="form-control @error('category') is-invalid @enderror">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category )
                                            <option {{($job->category_id==$category->id) ? 'selected' : ''}} value="{{$category->id}}">{{$category->name}}</option>
                                            @endforeach
                                        @endif
                                        @error('category')
                                        <p class="invalid-feedback">{{$message}}</p>
                                        @enderror
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label for="" class="mb-2">Job Type<span class="req">*</span></label>
                                    <select name="jobType" id="jobType" class="form-control @error('jobType') is-invalid @enderror ">
                                        <option value="">Select Job Natural</option>
                                        @if ($jobTypes->isNotEmpty())
                                            @foreach ($jobTypes as $jobType)
                                                <option {{($job->job_type_id==$jobType->id) ? 'selected' : ''}} value="{{$jobType->id}}">{{$jobType->name}}</option>
                                            @endforeach
                                        @endif
                                        @error('jobType')
                                        <p class="invalid-feedback">{{$message}}</p>
                                        @enderror
                                    </select>
                                </div>
                                <div class="col-md-6  mb-4">
                                    <label for="" class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" min="1" value="{{$job->vacancy}}" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control @error('vacancy') is-invalid @enderror">
                                    @error('Vacancy')
                                        <p class="invalid-feedback">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Salary</label>
                                    <input type="text" placeholder="Salary" id="salary" value="{{$job->salary}}" name="salary" class="form-control">
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="location" id="location" value="{{$job->location}}" name="location" class="form-control @error('Location') is-invalid @enderror">
                                    @error('Location')
                                        <p class="invalid-feedback">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-4 col-md-6" >
                                    <div class="form-check">
                                        <input {{($job->isFeatured==1)?'checked':''}} class="form-check-input" type="checkbox" value="1" id="isFeatured" name="isFeatured">
                                        <label class="form-check-label" for="isFeatured">
                                          Featured
                                        </label>
                                      </div>
                                </div>
                                <div class="mb-4 col-md-6" >
                                    <div class="form-check-inline">
                                        <input {{($job->status==1)?'checked':''}} class="form-check-input" type="radio" value="1" id="status-active" name="status">
                                        <label class="form-check-label" for="status">
                                          Active
                                        </label>
                                      </div>
                                    <div class="form-check-inline">
                                        <input {{($job->status==0)?'checked':''}} class="form-check-input" type="radio" value="0" id="status-block" name="status">
                                        <label class="form-check-label" for="status">
                                          Block
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="textarea" name="description" id="description" cols="5" rows="5" placeholder="Description @error('description') is-invalid @enderror">{{$job->description}}</textarea>
                                @error('description')
                                        <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Benefits</label>
                                <textarea class="textarea" name="benefits" id="benefits" cols="5" rows="5" placeholder="Benefits"> {{$job->benefits}}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Responsibility</label>
                                <textarea class="textarea" name="responsibility"  id="responsibility" cols="5" rows="5" placeholder="Responsibility">{{$job->responsibility}}</textarea>
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Qualifications</label>
                                <textarea class="textarea" name="qualification" id="qualification" cols="5" rows="5" placeholder="Qualifications">{{$job->qualification}}</textarea>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Experience<span class="req">*</label>
                                <select name="experience" id="experience" class="form-control" value="{{old('experience')}}">
                                    <option value="1" {{($job->experience==1)?'selected':''}}>1 Years</option>
                                    <option value="2" {{($job->experience==2)?'selected':''}}>2 Years</option>
                                    <option value="3" {{($job->experience==3)?'selected':''}}>3 Years</option>
                                    <option value="4" {{($job->experience==4)?'selected':''}}>4 Years</option>
                                    <option value="5" {{($job->experience==5)?'selected':''}}>5 Years</option>
                                    <option value="6" {{($job->experience==6)?'selected':''}}>6 Years</option>
                                    <option value="7" {{($job->experience==7)?'selected':''}}>7 Years</option>
                                    <option value="8" {{($job->experience==8)?'selected':''}}>8 Years</option>
                                    <option value="9" {{($job->experience==9)?'selected':''}}>9 Years</option>
                                    <option value="10" {{($job->experience==10)?'selected':''}}>10 Years</option>
                                    <option value="10_plus" {{($job->experience=='10_plus')?'selected':''}}>10+ Years</option>
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Keywords</span></label>
                                <input type="text" placeholder="keywords" id="keywords" value="{{$job->keywords}}" name="keywords" class="form-control">
                            </div>

                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" value="{{$job->company_name}}"  id="company_name" name="company_name" class="form-control @error('company_name') is-invalid @enderror">
                                    @error('company_name')
                                    <p class="invalid-feedback">{{$message}}</p>
                                     @enderror
                                </div>

                                <div class="mb-4 col-md-6">
                                    <label for="" class="mb-2">Location</label>
                                    <input type="text" placeholder="Location" id="company_location" value="{{$job->company_location}}" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="" class="mb-2">Website</label>
                                <input type="text" placeholder="Website" id="website" value="{{$job->company_website}}" name="website" class="form-control">
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update Job</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
