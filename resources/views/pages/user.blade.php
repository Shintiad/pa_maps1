@extends('layouts.main')

@section('title', 'User')

@section('header')
@if(auth()->check() && auth()->user()->role == 1)
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Daftar User</h1>
        </div><!-- /.col -->
        <!-- <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="#" class="btn btn-success"><i class="fa-solid fa-circle-plus"></i> Tambah</a>
            </ol>
        </div>col -->
    </div><!-- /.row -->
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
    <div class="card rounded-2xl ps-3 pe-3">
        <div class="card-body">
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
    </div>
    @endforeach
</div>
@endif

@endsection