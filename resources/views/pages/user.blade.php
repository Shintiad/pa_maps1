@extends('layouts.main')

@section('title', 'User')

@section('header')
@if(auth()->check() && auth()->user()->role == 1)
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Daftar User</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('add-user') }}" class="btn btn-success"><i class="fa-solid fa-circle-plus"></i> Tambah</a>
            </ol>
        </div>
    </div><!-- /.row -->
    <!-- Search Form -->
    <form method="GET" action="{{ route('user-search') }}" class="mt-3 mb-3">
        <div class="input-group">
            <input type="text" name="keyword" class="form-control rounded-l-lg" placeholder="Cari pengguna..." value="{{ request('keyword') }}">
            <button class="btn btn-primary rounded-r-lg" type="submit"><i class="fa-solid fa-search"></i> Cari</button>
        </div>
    </form>
</div>
@else
<div class="container-fluid">
    <div class="row mb-2 text-center">
        <div class="col">
            <h1 class="m-0 text-red-400">Mohon maaf! Halaman hanya dapat diakses oleh Admin.</h1>
        </div>
    </div>
</div>
@endif

@endsection

@section('content')

<!-- Main content -->
@if(auth()->check() && auth()->user()->role == 1)
<div class="container-fluid">
    @foreach($user as $index => $userList)
    <div class="card rounded-2xl pt-2 ps-3 pe-3 mb-3 transition ease-in-out hover:scale-95">
        <div class="card-body">
            <div class="d-flex justify-between">
                <div>
                    <h5 class="card-title text-lg text-teal-400 font-bold">{{ $userList->name }}</h5>
                    <p class="card-text">
                    <table class="table table-borderless">
                        <tr>
                            <td>E-mail</td>
                            <td>:</td>
                            <td>{{ $userList->email }}</td>
                        </tr>
                        <tr>
                            <td>No Telepon</td>
                            <td>:</td>
                            <td>{{ $userList->phone }}</td>
                        </tr>
                    </table>
                    </p>
                </div>
                <div class="d-flex flex-column justify-center items-end">
                    <a href="/user/{{ $userList->id }}/edit" class="btn btn-primary mb-2">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="/user/{{ $userList->id }}" method="POST" class="d-inline-block">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                    @if(is_null($userList->email_verified_at))
                    <form action="{{ route('verify-email', $userList->id) }}" method="POST" class="d-inline-block mt-2">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-envelope-check"></i> Verifikasi Email
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pagination Links -->
    <div class="mt-4 pb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="text-muted ms-2">
                    Menampilkan {{ $user->firstItem() }} hingga {{ $user->lastItem() }} dari {{ $user->total() }} data
                </p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $user->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endif

@endsection