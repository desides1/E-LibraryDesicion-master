@extends('layouts.main')

@section('backend_main')
    <section class="section">
        <div class="card">
            <div class="card-header">
                Tabel Data {{ $title }}
            </div>
            <div class="card-body">
                @role('Pustakawan')
                    <button type="button" class="btn btn-warning text-dark fw-bolder icon icon-left  mb-3 user-create-btn"
                        data-bs-toggle="modal" data-bs-target="#userCreateModal"><i class="fas fa-plus me-2"></i>Tambah
                        Pengguna</button>
                @endrole

                @if (session()->has('success'))
                    <div class="alert alert-primary alert-dismissible show fade mt-2" id="success-alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <table class="table" id="table1">
                    <thead class="table-light">
                        <tr>
                            <th class="text-start">No</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Peran</th>
                            <th>ID Anggota</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                            <tr>
                                <td class="text-start">{{ $loop->iteration }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->email }}</td>
                                <td>
                                    <button
                                        class="btn {{ $item->hasRole('Pemustaka') ? 'btn-primary' : ($item->hasRole('Penerbit') ? 'btn-danger' : ($item->hasRole('Pustakawan') ? 'btn-success' : '')) }} role-container icon icon-left"
                                        id="role-container" data-user-id="{{ $item->id }}"><i
                                            class="fas fa-users-cog me-2"></i>
                                        {{ implode(', ', $item->getRoleNames()->toArray()) }}
                                    </button>
                                </td>
                                <td>{{ $item->number_id }}</td>
                                <td>
                                    <button
                                        class="btn {{ $item->hasPermissionTo('Aktif') ? 'btn-success' : 'btn-danger' }} status-container icon icon-left"
                                        id="status-container" data-user-id="{{ $item->id }}"><i
                                            class="fas fa-cogs me-1"></i>
                                        {{ implode(', ', $item->getPermissionNames()->toArray()) }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('user-management.show', encrypt($item->id)) }}"
                                        class="text-decoration-none btn btn-navy text-light user-detail-btn me-1 icon icon-left"
                                        data-user-id="{{ $item->id }}"><i
                                            class="fas fa-info-circle me-1"></i>Detail</a>
                                    <button class="text-decoration-none btn btn-blues text-light user-edit-btn icon icon-left"
                                        data-user-id="{{ $item->id }}"><i class="fas fa-key me-2"></i>Reset
                                        Password</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    {{-- modal --}}
    @include('pages.user.update')
    @include('pages.user.create')
    @include('pages.user.dist.pass')
    {{-- end modal --}}
@endsection

@push('styles')
    @include('pages.user.dist.styles')
@endpush

@push('scripts')
    <script defer src="{{ asset('dist/vendors/simple-datatables/simple-datatables.js') }}"></script>
    <script defer src="{{ asset('dist/js/data_tables.js') }}"></script>
    @include('pages.user.dist.handler')
@endpush
