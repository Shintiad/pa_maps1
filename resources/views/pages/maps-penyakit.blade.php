@extends('layouts.main')

@section('title', 'Maps')

@section('header')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6">
            <h1 class="m-0">Maps Pemetaan Sebaran Penyakit Endemik</h1>
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
    <div class="card rounded-2xl">
        <div class="card-header ps-4 pe-4 pt-2">
            <div class="d-flex justify-content-between align-items-center">
                <button id="prev-btn" class="btn btn-outline-dark">&lt; Prev</button>
                <ul class="nav nav-tabs card-header-tabs" id="year-tabs">
                    @foreach($tahun as $index => $thn)
                    <li class="nav-item year-tab @if($index >= 5) d-none @endif">
                        <a class="nav-link text-gray-600 hover:text-teal-500 @if ($loop->first) active @endif"
                            aria-current="true" href="#"
                            data-year-id="{{ $thn->id }}">
                            {{ $thn->tahun }}
                        </a>
                    </li>
                    @endforeach
                </ul>
                <button id="next-btn" class="btn btn-outline-dark">Next &gt;</button>
            </div>
        </div>
        <div class="card-body h-fit">
            <div class="h-full">
                <div class="flex flex-wrap justify-center items-center -mt-2 -ml-2 mb-4" id="disease-buttons">
                    @foreach($penyakit as $penyakitList)
                    <a href="#" class="btn text-white p-2 mt-2 ml-2 transition-colors duration-200 ease-in-out @if ($loop->first) bg-teal-700 @else bg-teal-500 @endif hover:bg-teal-600" data-disease-id="{{ $penyakitList->id }}">
                        {{ $penyakitList->nama_penyakit }}
                    </a>
                    @endforeach
                </div>

                <iframe id="map-iframe" src="" frameborder="0" allowtransparency class="h-screen w-full hidden"></iframe>
                <div class="flex flex-col items-center justify-center">
                    <p id="no-map-message" class="text-center text-gray-500 pb-2 hidden">Belum ada peta yang tersedia untuk kombinasi tahun dan penyakit ini.</p>
                    <a id="create-map-btn" href="#" class="btn btn-info mt-2 text-white text-center hidden w-auto px-4"
                        data-user-role="{{ auth()->check() ? auth()->user()->role : 0 }}">Buat Peta Sebaran</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const navLinks = document.querySelectorAll('#year-tabs .nav-link');
        const yearTabs = document.querySelectorAll('#year-tabs .year-tab');
        const iframe = document.getElementById('map-iframe');
        const noMapMessage = document.getElementById('no-map-message');
        const createMapBtn = document.getElementById('create-map-btn');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        let currentIndex = 0;
        const visibleTabs = 5;
        let currentYearId = '{{ $tahun->first()->id }}';
        let currentDiseaseId = '{{ $penyakit->first()->id }}';
        let dataAvailability = {};

        function disableElement(element) {
            element.classList.add('disabled');
            element.style.opacity = '0.5';
            element.style.cursor = 'not-allowed';
            element.style.pointerEvents = 'none';
            // Add aria-disabled for accessibility
            element.setAttribute('aria-disabled', 'true');
        }

        function enableElement(element) {
            element.classList.remove('disabled');
            element.style.opacity = '1';
            element.style.cursor = 'pointer';
            element.style.pointerEvents = 'auto';
            element.removeAttribute('aria-disabled');
        }

        function updateTabsAndButtonsState() {
            // Update year tabs
            navLinks.forEach(link => {
                const yearId = link.getAttribute('data-year-id');
                const key = `${yearId}-${currentDiseaseId}`;
                const availability = dataAvailability[key] || {
                    has_data: false,
                    is_complete: false
                };

                if (!availability.has_data || !availability.is_complete) {
                    disableElement(link);
                } else {
                    enableElement(link);
                }
            });

            // Update disease buttons
            document.querySelectorAll('#disease-buttons .btn').forEach(button => {
                const diseaseId = button.getAttribute('data-disease-id');
                const key = `${currentYearId}-${diseaseId}`;
                const availability = dataAvailability[key] || {
                    has_data: false,
                    is_complete: false
                };

                if (!availability.has_data || !availability.is_complete) {
                    disableElement(button);
                    button.classList.add('bg-gray-400');
                    button.classList.remove('bg-teal-500', 'bg-teal-700', 'hover:bg-teal-600');
                } else {
                    enableElement(button);
                    if (diseaseId === currentDiseaseId) {
                        button.classList.add('bg-teal-700');
                        button.classList.remove('bg-teal-500', 'bg-gray-400');
                    } else {
                        button.classList.add('bg-teal-500');
                        button.classList.remove('bg-teal-700', 'bg-gray-400');
                    }
                    button.classList.add('hover:bg-teal-600');
                }
            });
        }

        function setActiveTab(clickedTab) {
            navLinks.forEach(tab => {
                tab.classList.remove('active');
                tab.classList.add('text-gray-600', 'hover:text-teal-500');
            });

            clickedTab.classList.add('active');
            clickedTab.classList.remove('text-gray-600', 'hover:text-teal-500');
        }

        function updateVisibleTabs() {
            const startIndex = Math.max(0, Math.min(currentIndex - visibleTabs + 1, yearTabs.length - visibleTabs));

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

        // Update fungsi updateIframe()
        function updateIframe() {
            fetch(`{{ route('getMapLink') }}?tahun_id=${currentYearId}&penyakit_id=${currentDiseaseId}`)
                .then(response => response.json())
                .then(data => {
                    iframe.classList.add('hidden');
                    noMapMessage.classList.add('hidden');
                    createMapBtn.classList.add('hidden');

                    // Update data availability and UI states
                    dataAvailability = data.data_availability;
                    updateTabsAndButtonsState();

                    // Update tombol create map href
                    createMapBtn.href = `/regenerate-disease/${currentYearId}/${currentDiseaseId}`;

                    switch (data.status) {
                        case 'has_map':
                            iframe.src = data.link_metabase;
                            iframe.classList.remove('hidden');
                            break;
                        case 'no_data':
                            noMapMessage.textContent = 'Belum ada data untuk kombinasi tahun dan penyakit ini.';
                            noMapMessage.classList.remove('hidden');
                            if (createMapBtn) {
                                const userRole = createMapBtn.getAttribute('data-user-role');
                                if (userRole == 1) {
                                    createMapBtn.classList.remove('hidden');
                                } else {
                                    createMapBtn.classList.add('hidden');
                                }
                            }
                            break;
                        case 'incomplete_data':
                            noMapMessage.textContent = 'Data belum lengkap untuk semua kecamatan pada kombinasi tahun dan penyakit ini.';
                            noMapMessage.classList.remove('hidden');
                            if (createMapBtn) {
                                const userRole = createMapBtn.getAttribute('data-user-role');
                                if (userRole == 1) {
                                    createMapBtn.classList.remove('hidden');
                                } else {
                                    createMapBtn.classList.add('hidden');
                                }
                            }
                            break;
                        case 'no_map':
                            noMapMessage.textContent = 'Tidak ada peta yang tersedia untuk kombinasi tahun dan penyakit ini.';
                            noMapMessage.classList.remove('hidden');
                            if (createMapBtn) {
                                const userRole = createMapBtn.getAttribute('data-user-role');
                                if (userRole == 1) {
                                    createMapBtn.classList.remove('hidden');
                                } else {
                                    createMapBtn.classList.add('hidden');
                                }
                            }
                            break;
                    }
                })
                .catch(error => {
                    console.error('Error fetching the map link:', error);
                    noMapMessage.textContent = 'Terjadi kesalahan saat memuat data.';
                    noMapMessage.classList.remove('hidden');
                    createMapBtn.classList.remove('hidden');
                    iframe.classList.add('hidden');
                    // Update href untuk keadaan error
                    createMapBtn.href = `/regenerate-disease/${currentYearId}/${currentDiseaseId}`;
                });
        }

        function handleTabClick(e) {
            e.preventDefault();
            if (!e.currentTarget.classList.contains('disabled')) {
                setActiveTab(e.currentTarget);
                currentYearId = e.currentTarget.getAttribute('data-year-id');
                currentIndex = Array.from(yearTabs).indexOf(e.currentTarget.closest('.year-tab'));
                updateVisibleTabs();
                updateIframe();
            }
        }

        // Event Listeners
        navLinks.forEach(link => {
            link.addEventListener('click', handleTabClick);
        });

        document.querySelectorAll('#disease-buttons .btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                if (!this.classList.contains('disabled')) {
                    currentDiseaseId = this.getAttribute('data-disease-id');
                    document.querySelectorAll('#disease-buttons .btn').forEach(btn => {
                        btn.classList.remove('bg-teal-700');
                        btn.classList.add('bg-teal-500');
                    });
                    this.classList.remove('bg-teal-500');
                    this.classList.add('bg-teal-700');
                    updateIframe();
                }
            });
        });

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateVisibleTabs();
                const visibleLinks = Array.from(navLinks).filter(link =>
                    !link.closest('.year-tab').classList.contains('d-none')
                );
                const targetLink = visibleLinks[0];
                if (targetLink && !targetLink.classList.contains('disabled')) {
                    targetLink.click();
                }
            } 
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < yearTabs.length - 1) {
                currentIndex++;
                updateVisibleTabs();
                const visibleLinks = Array.from(navLinks).filter(link =>
                    !link.closest('.year-tab').classList.contains('d-none')
                );
                const targetLink = visibleLinks[visibleLinks.length - 1];
                if (targetLink && !targetLink.classList.contains('disabled')) {
                    targetLink.click();
                }
            }
        });

        // Initialize with first tab active and iframe loaded
        if (navLinks.length > 0) {
            setActiveTab(navLinks[0]);
            updateIframe();
        }

        updateVisibleTabs();
    });
</script>
@endsection