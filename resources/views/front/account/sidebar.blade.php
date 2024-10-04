<div class="card border-0 shadow mb-4 p-3">
    <div class="s-body text-center mt-3">
        @if (Auth::user()->image !="")
        <div style="width: 150px; height: 150px; margin-top: 1rem; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0; border-radius: 50%; overflow: hidden; margin: 0 auto;">
            <img src="{{asset('profile_pic/'.Auth::user()->image)}}" style="width: 100%; height: 100%; object-fit: cover;" class="img-fluid" alt="{{ Auth::user()->name }}">
        </div>
        @else
        <div style="width: 150px; height: 150px; margin-top: 1rem; display: flex; justify-content: center; align-items: center; background-color: #f0f0f0; border-radius: 50%; overflow: hidden; margin: 0 auto;">
            <img src="{{asset('assets/images/avatar7.png')}}" alt="avatar" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
        @endif
        <h5 class="mt-3 pb-0">{{Auth::user()->name}}</h5>
        <p class="text-muted mb-1 fs-6">Full Stack Developer</p>
        <div class="d-flex justify-content-center mb-2">
            <button data-bs-toggle="modal" data-bs-target="#exampleModal" type="button" class="btn btn-primary">Change Profile Picture</button>
        </div>
    </div>
</div>
<div class="card account-nav border-0 shadow mb-4 mb-lg-0">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush ">
            <li class="list-group-item d-flex justify-content-between p-3">
                <a href="{{route('account.profile')}}">Account Settings</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.createJob')}}">Post a Job</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.myJob')}}">My Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.myJobApplication')}}">Jobs Applied</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="saved-jobs.html">Saved Jobs</a>
            </li>
            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                <a href="{{route('account.logout')}}">logout</a>
            </li>
        </ul>
    </div>
</div>
