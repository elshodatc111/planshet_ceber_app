@extends('layouts.app')

@section('content')
    <link href="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">Barcha Postlar</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table text-center datatable" srtle="font-size:12px;">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Post Name</th>
                                <th class="text-center">Hodim</th>
                                <th class="text-center">Create Post</th>
                                <th class="text-center">Update Post</th>
                                <th class="text-center"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($Planshet as $item)
                            <tr class="text-center">
                                <td>{{ $loop->index+1 }}</td>
                                <td><a href="{{ route('home_show', $item['id']) }}">{{ $item['name'] }}</a></td>
                                <td>{{ $item['user'] }}</td>
                                <td>{{ $item['created_at'] }}</td>
                                <td>{{ $item['updated_at'] }}</td>
                                <td>
                                    <form action="{{ route('home_del',$item['id']) }}" method="POST">@csrf @method('delete')<button class="p-0 btn btn-danger">delete</button></form>
                                </td>
                            </tr>
                            @empty
                                <tr>
                                    <td colspan='6' class="text-center">Postlar mavjud emas</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js"></script>
    <script src="https://atko.tech/NiceAdmin/assets/vendor/tinymce/tinymce.min.js"></script>
    <script src="https://atko.tech/NiceAdmin/assets/js/main.js"></script>
@endsection
