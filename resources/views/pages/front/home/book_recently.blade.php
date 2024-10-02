<div class="container-fluid pb-5 wow fadeInUp" data-wow-delay="0.1s">
    <div class="container py-3">
        <div class="row g-5">
            <div class="col-lg-8">
                <div class="section-title position-relative pb-3 mb-5">
                    <h5 class="fw-bold text-primary text-uppercase">Koleksi Buku Terbaru</h5>
                    <h1 class="mb-0">{{ Str::limit($book_recent->title, 48) }}</h1>
                </div>
                {{-- <p class="mb-4" style="text-align: justify;">
                    {{ Str::limit(strip_tags($book_recent->abstract), 234) }}
                </p> --}}
                <div class="row g-0 mb-3">
                    <div class="col-sm-6 wow zoomIn" data-wow-delay="0.2s">
                        <h5 class="mb-3 text-truncate"><i class="fa fa-address-book text-primary me-3"></i>Penerbit:
                            {{ $book_recent->publisher ? $book_recent->publisher : $book_recent->user->name }}
                        </h5>
                        <h5 class="mb-3 text-truncate"><i class="fa fa-book text-primary me-3"></i>Kategori:
                            {{ $book_recent->category->name }}
                        </h5>
                    </div>
                    <div class="col-sm-6 wow zoomIn" data-wow-delay="0.4s">
                        <h5 class="mb-3 text-truncate"><i class="fa fa-book-reader text-primary me-3"></i>ISBN:
                            {{ $book_recent->isbn }}
                        </h5>
                        <h5 class="mb-3 text-truncate"><i class="fa fa-store-alt text-primary me-3"></i>Stok:
                            {{ $book_recent->available_stock }}
                        </h5>
                    </div>
                </div>
                <a href="{{ url('/front-book/' . encrypt($book_recent->id)) }}"
                    class="btn btn-primary py-3 px-4 mt-3 wow zoomIn" data-wow-delay="0.9s">Baca
                    Selengkapnya</a>
            </div>
            <div class="col-lg-4" style="min-height: 320px;">
                <div class="position-relative">
                    <img class="position-absolute rounded wow zoomIn image-zoomable" data-wow-delay="0.9s"
                        id="book-zoom" loading="lazy"
                        src="{{ Storage::exists($book_recent->image) ? asset('storage/' . $book_recent->image) : asset('components/img/cover-book.png') . '?v=' . time() }}"
                        alt="book-image"
                        style="object-fit: contain; width: 100%; height: 220px;">
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .image-zoomable {
            transition: transform 0.5s ease;
        }

        .image-zoomable:hover {
            transform: scale(1.05);
        }
    </style>
@endpush
