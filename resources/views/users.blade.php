@extends('layouts.app')

@section('content')
    <link href="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">Barcha Hodimlar</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table text-center" srtle="font-size:12px;">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">FIO</th>
                                <th class="text-center">Lovozim</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">Yaratilgan vaqti</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($User as $item)
                            <tr class="text-center">
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['type'] }}</td>
                                <td>{{ $item['email'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>
                                    <form action="{{ route('user_del',$item['id']) }}" method="post">
                                        @csrf 
                                        @method('delete')
                                        <button class="btn btn-danger p-0 m-0 px-1">o'chirish</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan=6 class="text-center">Hodimlar mavjud emas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <br>
            <div class="card">
                <div class="card-header">Yangi hodim qo'shish</div>
                <div class="card-body">
                    <form action="{{ route('users_create') }}" method="post">
                        @csrf
                        <label for="name">Yangi hodimning FIO</label>
                        <input type="text" name="name" required class="form-control">
                        <label for="type">Hodim uchun ruxsatlar</label>
                        <select name="type" class="form-select">
                            <option value="">Tanlang</option>
                            <option value="admin">Admin</option>
                            <option value="foydalanuvchi">Foydalanuvchi</option>
                        </select>
                        <label for="email">Hodimning email</label>
                        <input type="email" name="email" required class="form-control">
                        <div class="text-center w-100">
                            <button class="btn btn-primary w-50 mt-3" type="submit">Saqlash</button>
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
