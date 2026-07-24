@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<div class="page-content">
<div class="container-fluid">

<div class="row">
<div class="col-12">
    <div class="card">
        <div class="card-body"> 

            <h4 class="card-title">Add Blog Category Page</h4><br><br>

            <form method="post" id="myForm" action="{{ route('store.blog.category') }}" >
                @csrf

            <div class="row mb-3">
                <label for="example-text-input" class="col-sm-2 col-form-label">Blog Category Name</label>
                <div class="col-sm-10 form-group">
                    <input name="blog_category" class="form-control" type="text" id="example-text-input">
                </div>
            </div>
            <!-- end row -->

            <input type="submit" class="btn btn-info waves-effect waves-light" value="Insert Blog Category">
            </form>
             
        </div>
    </div>
</div> <!-- end col -->
</div>

</div>
</div>

{{-- Without loading the page, this script validates the form input for the blog category name. It ensures that the field is not left empty and provides error messages accordingly. The script uses jQuery to handle the validation logic and display error messages in a user-friendly manner. --}}
<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                blog_category: {
                    required : true,
                }, 
            },
            messages :{
                blog_category: {
                    required : 'Please Enter Blog Category Name',
                },
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection 
