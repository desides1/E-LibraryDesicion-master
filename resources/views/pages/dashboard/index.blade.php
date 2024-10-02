@extends('layouts.main')

@section('backend_main')
    <div class="page-content">
        <section class="row">
            <div class="col-12 col-lg-12 col-md-12 col-sm-12">
                <div class="row mb-3">
                    @role('Pustakawan')
                        @php
                            $cards = [
                                [
                                    'icon' => 'iconly-boldProfile',
                                    'color' => 'purple',
                                    'title' => 'Pengguna',
                                    'value' => $user,
                                ],
                                [
                                    'icon' => 'iconly-boldUpload',
                                    'color' => 'blue',
                                    'title' => 'Usulan Buku',
                                    'value' => $request_book,
                                ],
                                [
                                    'icon' => 'iconly-boldDocument',
                                    'color' => 'green',
                                    'title' => 'Jumlah Buku',
                                    'value' => $book_library,
                                ],
                                [
                                    'icon' => 'iconly-boldBookmark',
                                    'color' => 'red',
                                    'title' => 'Anggaran Buku',
                                    'value' => 'Rp' . number_format($price_book, 0, ',', '.'),
                                ],
                            ];
                        @endphp

                        @foreach ($cards as $card)
                            <div class="col-6 col-lg-3 col-md-6">
                                <div class="card">
                                    <div class="card-body px-4 py-4-5">
                                        <div class="row">
                                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-5 d-flex justify-content-start">
                                                <div class="stats-icon {{ $card['color'] }} mb-2">
                                                    <i class="{{ $card['icon'] }}"></i>
                                                </div>
                                            </div>
                                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-7">
                                                <h6 class="text-muted font-semibold">{{ $card['title'] }}</h6>
                                                <h6 class="font-extrabold mb-0">{{ $card['value'] }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endrole
                    @role('Pemustaka|Penerbit')
                        <p style="text-align: justify">Selamat datang di SiPekan, Sistem Rekomendasi Pengadaan Buku Pada
                            Perpustakaan Politeknik Negeri Banyuwangi! 🌟 Kami dengan senang hati menyambut Anda yang telah
                            bergabung dalam layanan
                            perpustakaan kami. Terima kasih atas partisipasi dan kerjasamanya dalam pengembangan koleksi
                            literasi digital kami. Kami berkomitmen untuk menyediakan layanan yang memenuhi kebutuhan literasi
                            dan pengetahuan Anda. Selamat menikmati eksplorasi buku-buku berkualitas! 👤</p>
                    @endrole
                </div>
            </div>
        </section>
    </div>
@endsection
