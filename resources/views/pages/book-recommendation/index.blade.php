@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="attribute-identification-tab" data-bs-toggle="tab"
                            href="#attribute-identification" role="tab" aria-controls="attribute-identification"
                            aria-selected="true">Identifikasi Atribut</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="decision-matrix-tab" data-bs-toggle="tab" href="#decision-matrix"
                            role="tab" aria-controls="decision-matrix" aria-selected="false" tabindex="-1">Matriks
                            Keputusan</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="matrix-normalization-tab" data-bs-toggle="tab" href="#matrix-normalization"
                            role="tab" aria-controls="matrix-normalization" aria-selected="false"
                            tabindex="-1">Normalisasi Matriks</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="test-optimization-tab" data-bs-toggle="tab" href="#test-optimization"
                            role="tab" aria-controls="test-optimization" aria-selected="false" tabindex="-1">Optimasi
                            Atribut</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="value-ranking-tab" data-bs-toggle="tab" href="#value-ranking" role="tab"
                            aria-controls="value-ranking" aria-selected="false" tabindex="-1">Perangkingan Nilai</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                @if ($budget && $budget->exists)
                    <a href="/book-recommendations/print" class="btn btn-outline-dark mb-3 ms-2 mt-1 user-create-btn"
                        target="_blank">Cetak Rekomendasi</a>
                @endif

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="attribute-identification" role="tabpanel"
                        aria-labelledby="attribute-identification-tab">
                        @include('pages.book-recommendation.atribut')
                    </div>
                    <div class="tab-pane fade" id="decision-matrix" role="tabpanel" aria-labelledby="decision-matrix-tab">
                        @include('pages.book-recommendation.decision')
                    </div>
                    <div class="tab-pane fade" id="matrix-normalization" role="tabpanel"
                        aria-labelledby="matrix-normalization-tab">
                        @include('pages.book-recommendation.normalization')
                    </div>
                    <div class="tab-pane fade" id="test-optimization" role="tabpanel"
                        aria-labelledby="test-optimization-tab">
                        @include('pages.book-recommendation.optimization')
                    </div>
                    <div class="tab-pane fade" id="value-ranking" role="tabpanel" aria-labelledby="value-ranking-tab">
                        @include('pages.book-recommendation.rangking')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/table_recom.js') }}"></script>
@endpush
