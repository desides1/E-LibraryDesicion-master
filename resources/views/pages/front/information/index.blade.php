@extends('layouts.front')

@section('content_fe')
    <div class="container-fluid pb-5 wow fadeInUp" data-wow-delay="0.1s">
        <div class="container">
            <div class="section-title text-center position-relative pb-3 mb-5 mx-auto" style="max-width: 600px;">
                <h5 class="fw-bold text-primary text-uppercase">{{ $title_head }}</h5>
                <h1 class="mb-0">Informasi Mengenai Perpustakaan Poliwangi</h1>
            </div>
            <div class="card"
                style="border-radius: 15px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
                <div class="card-body">
                    <section class="container w-fit m-auto">
                        <div class="card rounded-lg p-4 border-0 shadow-md mt-8">
                            <h4 class="mb-4">Informasi Perpustakaan</h4>
                            <hr>
                            <h3>Kontak</h3>

                            <p><strong>Alamat :</strong><br />
                                Jalan Raya Jember KM 13, Rogojampi, Banyuwangi<br />
                                <strong>Phone Number :</strong><br />
                                (0333) 636780&nbsp;<br />
                                <strong>Fax Number :</strong><br />
                                (0333) 636780
                            </p>

                            <p><strong>Email Perpustakaan :</strong><br />
                                perpustakaan@poliwangi.ac.id</p>

                            <h3>Jam Buka</h3>

                            <p><strong>Senin - Jumat :</strong><br />
                                Buka&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : 08.00<br />
                                Istirahat : 12.00 - 13.30<br />
                                Tutup&nbsp;&nbsp;&nbsp;&nbsp; : 15.30</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection
