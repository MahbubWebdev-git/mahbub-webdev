@extends('admin.admin_master')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<div class="page-content">
<div class="container-fluid">
<div>
    <!-- <script src="https://tiny.cloud{{ env('VITE_TINYMCE_API_KEY') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> -->
<script>
    tinymce.init({ selector: 'textarea' });
</script>
<!-- When there is no desire, all things are at peace. - Laozi -->
</div>
</div>
</div>
@endsection