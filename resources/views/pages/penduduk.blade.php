@extends('layouts.main')

@section('title', 'Penduduk')

@section('header')

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Jumlah Penduduk Tiap Kecamatan di Kab. Lamongan</h1>
        </div><!-- /.col -->
        @if(auth()->check() && auth()->user()->role == 1)
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <a href="{{ route('add-penduduk') }}" class="btn btn-success"><i class="fa-solid fa-circle-plus"></i> Penduduk</a>
            </ol>
        </div>
        @endif
    </div><!-- /.row -->
</div>

@endsection

@section('content')

<!-- Main content -->
<div class="container-fluid">
    <!-- Filter Form -->
    <div class="mb-4 ms-2">
        <form method="GET" action="{{ route('penduduk') }}" class="flex items-end space-x-4">
            <div>
                <label for="tahun_id" class="block font-bold ms-2 mb-1">Filter Tahun:</label>
                <select id="tahun_id" name="tahun_id" class="form-select max-w-40 rounded-md">
                    <option value="">Semua Tahun</option>
                    @foreach ($tahun as $item)
                    <option value="{{ $item->id }}" {{ request('tahun_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->tahun }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="kecamatan_id" class="block font-bold ms-2 mb-1">Filter Kecamatan:</label>
                <select id="kecamatan_id" name="kecamatan_id" class="form-select max-w-48 rounded-md">
                    <option value="">Semua kecamatan</option>
                    @foreach ($kecamatan as $item)
                    <option value="{{ $item->id }}" {{ request('kecamatan_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama_kecamatan }}
                    </option>
                    @endforeach
                </select>
            </div>
            <!-- Filter Button -->
            <div>
                <button type="submit" class="btn bg-teal-400 text-white hover:bg-teal-500 h-[38px]">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <div class="card rounded-2xl ps-3 pe-3">
        <table class="table text-center">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tahun</th>
                    <th>Kecamatan</th>
                    <th>Jumlah Penduduk</th>
                    @if(auth()->check() && auth()->user()->role == 1)
                    <th>Action</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($penduduk as $index => $pendudukList)
                <tr>
                    <td>{{ $penduduk->firstItem() + $index }}</td>
                    <td>{{ $pendudukList->tahun->tahun }}</td>
                    <td>{{ $pendudukList->kecamatan->nama_kecamatan }}</td>
                    <td>{{ number_format($pendudukList->jumlah_penduduk, 0, ',', '.') }}</td>
                    @if(auth()->check() && auth()->user()->role == 1)
                    <td class="text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="/penduduk/{{ $pendudukList->id }}/edit" class="btn btn-primary">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </a>
                            <form action="/penduduk/{{ $pendudukList->id }}" method="POST" class="inline-block">
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
            <div class="col-md-6">
                <p class="text-muted ms-2">
                    Menampilkan {{ $penduduk->firstItem() }} hingga {{ $penduduk->lastItem() }} dari {{ $penduduk->total() }} data
                </p>
            </div>
            <div class="col-md-6 d-flex justify-content-end">
                {{ $penduduk->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

@endsection