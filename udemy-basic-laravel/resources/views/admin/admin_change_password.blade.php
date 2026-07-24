@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">

        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8">
                <div class="card overflow-hidden border border-secondary">
                    <div class="card-body p-4">

                        <h4 class="card-title">Change Password Page</h4><br>

                        @if(session('message'))
                        <div class="alert alert-{{ session('alert-type') }} alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif

                        <form method="POST" action="{{ route('update.password') }}" >
                            @csrf
                            <div class="mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Old Password</label>
                                <div class="col-sm-6">
                                    <input name="oldpassword" class="form-control" type="password" id="oldpassword">   
                                    @error('oldpassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div> 

                            <div class="mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">New Password</label>
                                <div class="col-sm-6">
                                    <input name="newpassword" class="form-control" type="password" id="newpassword">
                                    @error('newpassword')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="example-text-input" class="col-sm-2 col-form-label">Confirm Password</label>
                                <div class="col-sm-6">
                                    <input name="confirm_password" class="form-control" type="password" id="confirm_password">
                                    @error('confirm_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                           
                            <input type="submit" class="btn btn-info waves-effect waves-light" value="Change Password">
                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
