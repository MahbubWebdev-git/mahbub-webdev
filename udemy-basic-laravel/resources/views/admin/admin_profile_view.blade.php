@extends('admin.admin_master')

@section('admin')
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="text-center">
                            <img src="{{ (!empty($adminData->profile_image)) ? asset('upload/admin_images/'.$adminData->profile_image) : asset('upload/no_image.jpg') }}" alt="Profile Image" class="img-fluid avatar-x1">
                        </div>
                        <div class="card-body">
                            <h4 class="card-text">Name: {{ $adminData->name }}</h4>
                            <h4 class="card-text">Email: {{ $adminData->email }}</h4>
                            <h4 class="card-text">User Name: {{ $adminData->username }}</h4>
                            <h4 class="card-text">Profile Image: {{ $adminData->profile_image }}</h4>
                            <div class="text-center">
                                <img src="{{ asset('upload/admin_images/'.$adminData->profile_image) }}" alt="Profile Image" class="rounded-circle avatar-x1 mt-4 mb-4" style="width: 150px; height: 150px; object-fit: cover;">
                            </div>
                            <h4 class="card-text">Last updated: {{ $adminData->updated_at->diffForHumans() }}</h4>
                            <a href="{{ route('edit.profile', $adminData->id) }}" class="btn btn-info btn-rounded waves-effect waves-light">Edit Profile</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection