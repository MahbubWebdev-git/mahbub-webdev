@extends('admin.admin_master')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">User Management (WordPress Style Roles)</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Approval Status</th>
                                    <th>User Role</th>
                                    <th>Allowed Pages to Edit</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <!-- স্লাইডার বাটন স্টাইল অ্যাকশন -->
                                        <form action="{{ route('admin.user.toggle', $user->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm {{ $user->is_approved ? 'btn-success' : 'btn-danger' }}">
                                                <i class="fas {{ $user->is_approved ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i> 
                                                {{ $user->is_approved ? 'Approved / Active' : 'Pending / Blocked' }}
                                            </button>
                                        </form>
                                    </td>
                                    
                                    <!-- রোল এবং পারমিশন ফর্ম -->
                                    <form action="{{ route('admin.user.update.role', $user->id) }}" method="POST">
                                        @csrf
                                        <td>
                                            <select name="role" class="form-select form-select-sm" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                                <option value="editor" {{ $user->role == 'editor' ? 'selected' : '' }}>Editor</option>
                                                <option value="subscriber" {{ $user->role == 'subscriber' ? 'selected' : '' }}>Subscriber</option>
                                            </select>
                                        </td>
                                        <td>
                                            @php 
                                                $currentPermissions = $user->allowed_pages ?? []; 
                                            @endphp
                                            <!-- নির্দিষ্ট পেজের পারমিশন চেকবক্স সমূহ -->
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="allowed_pages[]" value="about" id="about_{{$user->id}}" {{ in_array('about', $currentPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="about_{{$user->id}}">About</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="allowed_pages[]" value="portfolio" id="port_{{$user->id}}" {{ in_array('portfolio', $currentPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="port_{{$user->id}}">Portfolio</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="allowed_pages[]" value="blog" id="blog_{{$user->id}}" {{ in_array('blog', $currentPermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="blog_{{$user->id}}">Blog</label>
                                            </div>
                                        </td>
                                        <td>
                                            <button type="submit" class="btn btn-primary btn-sm" {{ $user->id == auth()->id() ? 'disabled' : '' }}>
                                                <i class="fas fa-save"></i> Update
                                            </button>
                                        </td>
                                    </form>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            </div> 
        </div>

    </div>
</div>

@endsection
