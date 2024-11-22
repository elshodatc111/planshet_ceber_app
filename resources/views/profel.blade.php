@extends('layouts.app')

@section('content')
    <link href="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">Profel malumotlari</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <label for="">FIO</label>
                    <input type="text" required disabled value="{{ auth()->user()->name }}" class="form-control">
                    <label for="">Email</label>
                    <input type="text" required disabled value="{{ auth()->user()->email }}" class="form-control">
                    <label for="">Lavozim</label>
                    <input type="text" required disabled value="{{ auth()->user()->type }}" class="form-control">
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">Parolni yangilash</div>
                <div class="card-body">
                    <form action="{{ route('update_password') }}" method="POST">
                        @csrf
                        <label for="current_password">Joriy parol:</label>
                        <input type="password" class="form-control" name="current_password" id="current_password" required>
                        
                        <label for="new_password">Yangi parol:</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" required>
                        
                        <label for="new_password_confirmation">Yangi parolni tasdiqlang:</label>
                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" required>
                        
                        <div class="w-100 text-center mt-3">
                        <button type="submit" class="btn btn-primary w-50">Parolni Yangilash</button>
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
