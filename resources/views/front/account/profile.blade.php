@extends('front.layout.app')
@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>
            <div class="col-lg-9">
                @include('front.layout.message')
                <div class="card border-0 shadow mb-4">
                    <form action="{{route('account.updateProfile')}}" method="post" id="userForm" name="userForm">
                        @csrf
                        @method('PUT')
                        <div class="card-body  p-4">
                            <h3 class="fs-4 mb-1">My Profile</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Name*</label>
                                <input type="text" name="name" id="name" value="{{$user->name}}" placeholder="Enter Name" class="form-control @error('name') is-invalid @enderror" >
                                @error('name')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" value="{{$user->email}}" placeholder="Enter Email" class="form-control @error('email') is-invalid @enderror">
                                @error('email')
                                <p class="invaid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Designation*</label>
                                <input type="text" name="designation" id="designation" value="{{$user->designation}}" placeholder="Designation" class="form-control @error('designation') is-invalid @enderror">
                                @error('designation')
                                <p class="invaid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Mobile*</label>
                                <input type="text" name="mobile" id="mobile" value="{{$user->mobile}}" placeholder="Mobile" class="form-control @error('designation') is-invalid @enderror">
                                @error('mobile')
                                <p class="invaid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
                <form action="{{ route('account.updatePassword') }}" method="POST">
                    @csrf
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">Change Password</h3>
                            <div class="mb-4">
                                <label for="" class="mb-2">Old Password*</label>
                                <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control @error('old_password') is-invalid @enderror">
                                @error('old_password')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">New Password*</label>
                                <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control @error('new_password') is-invalid @enderror">
                                @error('new_password')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label for="" class="mb-2">Confirm Password*</label>
                                <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" class="form-control @error('confirm_password') is-invalid @enderror">
                                @error('confirm_password')
                                <p class="invalid-feedback">{{$message}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer  p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
