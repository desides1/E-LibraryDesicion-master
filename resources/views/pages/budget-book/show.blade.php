@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                <a href="{{ url('/book-budget') }}"
                    class="btn btn-reds text-light fw-bolder icon icon-left me-2 user-create-btn"><i
                        class="fas fa-chevron-left me-2"></i>Kembali</a>

                @if (count($alternative) > 1)
                    <a href="{{ route('book-budget.print', ['id' => encrypt($budget->id), 'recom' => $selectedRecom, 'major' => $selectedMajor], ) }}"
                        target="_blank" class="btn btn-orange text-light fw-bolder icon icon-left me-2 user-print-btn"  id="printLink"><i
                            class="fas fa-print me-2"></i>Cetak Rekomendasi</a>
                    @if ($selectedRecom !== 'calculate')
                        <a href="#" class="btn btn-warning text-dark icon icon-left fw-bolder me-2  user-detail-btn"
                            data-bs-toggle="modal" data-bs-target="#detailRecom"><i
                                class="fas fa-info-circle me-2"></i>Detail
                            Rekomendasi</a>
                    @endif
                    @if ($selectedMajor !== 'all')
                        {{-- <a href="#" class="btn btn-dark user-manage-btn" id="manageBook">Kelola Pengadaan</a> --}}
                    @endif
                @endif

                <form id="filterForm" action="{{ route('book-budget.show', encrypt($budget->id)) }}" method="GET">
                    <div class="row">
                        <div class="col-md-2 mt-3">
                            <select class="js-example-basic-single form-select" aria-label="Default select example"
                                name="recom" id="mainSelect">
                                <option value="moora" {{ $selectedRecom === 'moora' ? 'selected' : '' }}>Moora</option>
                                <option value="topsis" {{ $selectedRecom === 'topsis' ? 'selected' : '' }}>Topsis</option>
                                <option value="saw" {{ $selectedRecom === 'saw' ? 'selected' : '' }}>SAW</option>
                                <option value="wpm" {{ $selectedRecom === 'wpm' ? 'selected' : '' }}>WPM</option>
                                <option value="calculate" {{ $selectedRecom === 'calculate' ? 'selected' : '' }}>Kalkulasi
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mt-3">
                            <select class="js-example-basic-single" aria-label="Default select example" name="major" id="selectMajor">
                                <option value="all" {{ $selectedMajor === 'all' ? 'selected' : '' }}>
                                    Keseluruhan Prodi
                                </option>
                                @foreach ($major as $majors)
                                    <option value="{{ $majors->name }}"
                                        {{ $selectedMajor === $majors->name ? 'selected' : '' }}>{{ $majors->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt-2" id="calculateDropdown" style="display: none">
                            <ul class="list-unstyled mb-0">
                                @foreach (['calmoora' => 'MOORA', 'caltopsis' => 'TOPSIS', 'calsaw' => 'SAW', 'calwpm' => 'WPM'] as $value => $label)
                                    <li class="d-inline-block me-2 mb-1">
                                        <div class="form-check">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                    class="form-check-input form-check-primary form-check-glow"
                                                    name="calavg[]" id="checkboxGlow{{ $label }}"
                                                    value="{{ $value }}"
                                                    {{ in_array($value, $calulateSelected) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="checkboxGlow{{ $label }}">{{ $label }}</label>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                                <Button class="btn btn-dark fw-bolder" id="btn-preview" type="submit"><i
                                        class="fas fa-arrow-right me-2"></i>Preview</Button>
                            </ul>
                        </div>
                    </div>
                </form>

                {{-- <a href="{{ route('book-budget.export-excel', encrypt($budget->id)) }}" target="_blank">Excel</a> --}}
            </div>
            <div class="card-body">
                @if (count($alternative) > 1)
                    @include('pages.budget-book.rangking')
                @elseif (count($alternative) == 1)
                    @include('pages.budget-book.atribute')
                @else
                    <h4 class="text-center">Data Alternatif Tidak Tersedia</h4>
                @endif
            </div>
        </div>
    </section>

    {{-- modal detail recommendation --}}
    @include('pages.budget-book.detail_recommendation')
    @include('pages.budget-book.purchase')
    {{-- end modal detail recommendation --}}
@endsection

@include('pages.budget-book.dist.styles')

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>

    @if ($selectedRecom !== 'calculate')
        <script defer src="{{ asset('dist/js/table_recom.js') }}"></script>
    @endif

    <script defer src="{{ asset('dist/js/modal_tables.js') }}"></script>
@endpush

@include('pages.budget-book.dist.select2')
