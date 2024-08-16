@extends('layouts.main')

@section('title', 'Kecamatan')

@section('header')

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Daftar Kecamatan di Kab. Lamongan</h1>
        </div><!-- /.col -->
        @if(auth()->check() && auth()->user()->role == 1)
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('add-kecamatan') }}" class="btn btn-success"><i class="fa-solid fa-circle-plus"></i> Kecamatan</a>
            </ol>
        </div>
        @endif
    </div><!-- /.row -->
</div>

@endsection

@section('content')

<!-- Main content -->
<div class="container-fluid">
    <div class="card rounded-2xl ps-3 pe-3">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>
                        <a href="{{ route('kecamatan', ['sort' => 'nama_kecamatan', 'direction' => $direction === 'asc' ? 'desc' : 'asc']) }}"
                            class="flex items-center justify-center text-gray-700 hover:text-gray-900">
                            <span>Nama Kecamatan</span>
                            @if($sort === 'nama_kecamatan')
                            <span class="ml-2 flex items-center">
                                <i class="fas fa-sort-{{ $direction === 'asc' ? 'up translate-y-1' : 'down -translate-y-0.5' }} text-sm"></i>
                            </span>
                            @else
                            <span class="ml-2 flex items-center">
                                <i class="fas fa-sort text-sm text-gray-400"></i>
                            </span>
                            @endif
                        </a>
                    </th>
                    @if(auth()->check() && auth()->user()->role == 1)
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($kecamatan as $index => $kecamatanList)
                <tr>
                    <td>{{ $kecamatan->firstItem() + $index }}</td>
                    <td>{{ $kecamatanList->nama_kecamatan }}</td>
                    @if(auth()->check() && auth()->user()->role == 1)
                    <td class="text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="/kecamatan/{{ $kecamatanList->id }}/edit" class="btn btn-primary">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="/kecamatan/{{ $kecamatanList->id }}" method="POST" class="inline-block">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4 pb-4">
        <div class="row">
            <div class="col-md-6 mb-3">
                <p class="text-muted ms-2">
                    Menampilkan {{ $kecamatan->firstItem() }} hingga {{ $kecamatan->lastItem() }} dari {{ $kecamatan->total() }} data
                </p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $kecamatan->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection