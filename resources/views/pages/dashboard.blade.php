@extends('layouts.main')

@section('title', 'Dashboard')

@section('header')

<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div><!-- /.col -->
    </div><!-- /.row -->
</div>

@endsection

@section('content')

<!-- Main content -->
<div class="container-fluid">
    <!-- Small boxes (Stat box) -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $tahun_count }}</h3>

                    <p>Total Tahun</p>
                </div>
                <div class="icon">
                    <i class="ion ion-stats-bars"></i>
                </div>
                <a href="{{ route('tahun') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $kecamatan_count }}</h3>

                    <p>Total Kecamatan</p>
                </div>
                <div class="icon">
                    <i class="ion ion-pie-graph"></i>
                </div>
                <a href="{{ route('kecamatan') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $penyakit_count }}</h3>

                    <p>Total Penyakit Endemik</p>
                </div>
                <div class="icon">
                    <i class="ion ion-medkit"></i>
                </div>
                <a href="{{ route('penyakit') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $user_count }}</h3>

                    <p>Total User</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person-add"></i>
                </div>
                @if(auth()->check() && auth()->user()->role == 1)
                <a href="{{ route('user') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                @else
                <div class="small-box-footer">&nbsp;</div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection