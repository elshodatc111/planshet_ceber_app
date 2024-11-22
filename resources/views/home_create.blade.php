@extends('layouts.app')

@section('content')
    <link href="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">Yangi post</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form action="{{ route('home_create_post') }}" method="post">
                        @csrf 
                        <label for="name">Post nomi</label>
                        <input type="text" name="name" required class="form-control">
                        <label for="json">Post uchun JSON</label>
                        <textarea name="json" class="form-control"></textarea>
                        <div class="w-100 text-center">
                            <button class="btn btn-primary w-50 mt-3">Postni saqlash</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="https://atko.tech/NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="https://atko.tech/NiceAdmin/assets/js/main.js"></script>
@endsection
