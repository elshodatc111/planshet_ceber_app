@extends('layouts.app')

@section('content')
    <link href="https://atko.tech/NiceAdmin/assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <div class="container">
        <div class="justify-content-center">
            <div class="card">
                <div class="card-header">Barcha Diagrammalar</div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <table class="table text-center datatable" style="font-size:12px;">
                        <thead class="text-center">
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Diagramma nomi</th>
                                <th class="text-center">Hodim</th>
                                <th class="text-center">Yaratilgan vaqti</th>
                                <th class="text-center">Yangilangan vaqti</th>
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
                                    <form action="{{ route('home_del',$item['id']) }}" method="POST">
                                        @csrf @method('delete')
                                        <button class="p-0 btn btn-danger px-1">o'chirish</button>
                                    </form>
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
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const uzbekLang = {
                perPage: "Sahifa uchun yozuvlar",
                noRows: "Hech qanday ma'lumot topilmadi",
                info: "",
                next: "Keyingi",
                prev: "Oldingi",
                search: "Qidirish",
            };

            const table = document.querySelector('.datatable');
            if (table) {
                const dataTable = new simpleDatatables.DataTable(table, {
                    labels: {
                        placeholder: uzbekLang.search, // "Search" -> "Qidirish"
                        perPage: uzbekLang.perPage, // "entries per page"
                        noRows: uzbekLang.noRows, // "No rows to display"
                        info: uzbekLang.info, // "Showing 1 to X of Y entries"
                        next: uzbekLang.next, // "Next"
                        prev: uzbekLang.prev, // "Previous"
                    }
                });
            }
        });
    </script>
@endsection
