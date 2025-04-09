@extends('layouts.main')

@section('title', 'Maps Penduduk')

@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Maps Pemetaan Sebaran Penduduk</h1>
        </div>
    </div>
    <div class="mt-3">
        <!-- Success Alert -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Error Alert -->
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <!-- Validation Errors -->
        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
    </div>
</div>
@endsection

@section('content')
<!-- Main content -->
<div class="container-fluid pb-4">
    <div class="card text-center rounded-2xl">
        <div class="card-header ps-4 pe-4 pt-2">
            <div class="d-flex justify-content-between align-items-center">
                <button id="prev-btn" class="btn btn-outline-dark">&lt; Prev</button>
                <ul class="nav nav-tabs card-header-tabs" id="year-tabs">
                    @foreach($tahunData as $index => $thn)
                    <li class="nav-item year-tab @if($index >= 5) d-none @endif">
                        <a class="nav-link text-gray-600 hover:text-teal-500 
                            @if ($loop->first) active @endif
                            @if ($thn['data_status'] !== 'complete') disabled @endif"
                            aria-current="true"
                            href="#"
                            data-year-id="{{ $thn['id'] }}"
                            data-link-metabase="{{ $thn['link_metabase'] }}"
                            data-status="{{ $thn['data_status'] }}"
                            data-message="{{ $thn['status_message'] }}"
                            @if ($thn['data_status'] !=='complete' )
                            title="{{ $thn['status_message'] }}"
                            style="cursor: not-allowed; opacity: 0.6;"
                            @endif>
                            {{ $thn['tahun'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <button id="next-btn" class="btn btn-outline-dark">Next &gt;</button>
            </div>
        </div>
        <div class="card-body h-fit">
            <div class="h-full">
                @php
                $firstCompleteYear = collect($tahunData)->first(function($year) {
                return $year['data_status'] === 'complete';
                });
                @endphp
                <iframe id="map-iframe"
                    src="{{ $firstCompleteYear ? $firstCompleteYear['link_metabase'] : '' }}"
                    frameborder="0"
                    allowtransparency
                    class="h-screen w-full z-10 {{ !$firstCompleteYear ? 'hidden' : '' }}">
                </iframe>
                <p id="no-map-message" class="text-center text-gray-500 pt-2 pb-2 {{ $firstCompleteYear && $firstCompleteYear['link_metabase'] ? 'hidden' : '' }}">
                    Belum ada peta yang tersedia untuk tahun ini.
                </p>
                <a id="create-map-btn"
                    href="/regenerate-population/{{ $firstCompleteYear ? $firstCompleteYear['id'] : '' }}"
                    class="btn btn-info mt-2 text-white {{ $firstCompleteYear && $firstCompleteYear['link_metabase'] ? 'hidden' : '' }}"
                    data-user-role="{{ auth()->check() ? auth()->user()->role : 0 }}">
                    Buat Peta Sebaran
                </a>
                <p id="data-status-message" class="text-center text-gray-500 pt-2 pb-2 hidden"></p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('#year-tabs .nav-link:not(.disabled)');
        const yearTabs = document.querySelectorAll('#year-tabs .year-tab');
        const iframe = document.getElementById('map-iframe');
        const noMapMessage = document.getElementById('no-map-message');
        const createMapBtn = document.getElementById('create-map-btn');
        const dataStatusMessage = document.getElementById('data-status-message');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        let currentIndex = 0;
        const visibleTabs = 5;

        function setActiveTab(clickedTab) {
            if (clickedTab.classList.contains('disabled')) {
                return;
            }

            navLinks.forEach(tab => {
                tab.classList.remove('active');
                tab.classList.add('text-gray-600', 'hover:text-teal-500');
            });

            clickedTab.classList.add('active');
            clickedTab.classList.remove('text-gray-600', 'hover:text-teal-500');
        }

        function updateIframe(linkMetabase, status, message) {
            iframe.classList.add('hidden');
            noMapMessage.classList.add('hidden');
            dataStatusMessage.classList.add('hidden');
            createMapBtn.classList.add('hidden');

            if (status === 'complete') {
                if (linkMetabase) {
                    iframe.src = linkMetabase;
                    iframe.classList.remove('hidden');
                } else {
                    noMapMessage.classList.remove('hidden');
                    if (createMapBtn) {
                        const userRole = createMapBtn.getAttribute('data-user-role');
                        if (userRole == 1) {
                            createMapBtn.classList.remove('hidden');
                        } else {
                            createMapBtn.classList.add('hidden');
                        }
                    }
                }
            } else {
                dataStatusMessage.textContent = message;
                dataStatusMessage.classList.remove('hidden');

                if (createMapBtn) {
                    const userRole = createMapBtn.getAttribute('data-user-role');
                    if (userRole == 1) {
                        createMapBtn.classList.remove('hidden');
                    } else {
                        createMapBtn.classList.add('hidden');
                    }
                }
            }
        }

        function handleTabClick(e) {
            e.preventDefault();
            const tab = e.currentTarget;

            if (tab.classList.contains('disabled')) {
                return;
            }

            setActiveTab(tab);
            updateIframe(
                tab.getAttribute('data-link-metabase'),
                tab.getAttribute('data-status'),
                tab.getAttribute('data-message')
            );

            createMapBtn.href = `/regenerate-population/${tab.getAttribute('data-year-id')}`;
            currentIndex = Array.from(yearTabs).indexOf(tab.closest('.year-tab'));
            updateVisibleTabs();
        }

        function updateVisibleTabs() {
            let startIndex = Math.max(0, Math.min(currentIndex - visibleTabs + 1, yearTabs.length - visibleTabs));

            yearTabs.forEach((tab, index) => {
                if (index >= startIndex && index < startIndex + visibleTabs) {
                    tab.classList.remove('d-none');
                } else {
                    tab.classList.add('d-none');
                }
            });

            prevBtn.disabled = startIndex === 0;
            nextBtn.disabled = startIndex + visibleTabs >= yearTabs.length;
        }

        navLinks.forEach(link => {
            link.addEventListener('click', handleTabClick);
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateVisibleTabs();
                const activeTab = navLinks[currentIndex];
                if (activeTab && !activeTab.classList.contains('disabled')) {
                    activeTab.click();
                }
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < yearTabs.length - 1) {
                currentIndex++;
                updateVisibleTabs();
                const activeTab = navLinks[currentIndex];
                if (activeTab && !activeTab.classList.contains('disabled')) {
                    activeTab.click();
                }
            }
        });

        // Set the first available tab as active by default
        const firstAvailableTab = document.querySelector('#year-tabs .nav-link:not(.disabled)');
        if (firstAvailableTab) {
            setActiveTab(firstAvailableTab);
            updateIframe(
                firstAvailableTab.getAttribute('data-link-metabase'),
                firstAvailableTab.getAttribute('data-status'),
                firstAvailableTab.getAttribute('data-message')
            );
        }

        updateVisibleTabs();
    });
</script>
@endsection