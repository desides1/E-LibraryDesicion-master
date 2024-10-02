<div id="header-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-inner">
        @foreach ($carouselItems as $index => $item)
            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                <img class="w-100" src="{{ asset($item['image']) }}" alt="{{ $item['alt'] }}">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3" style="max-width: 900px;">
                        <h5 class="text-white text-uppercase mb-3 animated slideInDown">Perpustakaan Poliwangi</h5>
                        <h1 class="display-1 text-white mb-md-4 animated zoomIn">Sistem Usulan Buku Pustaka</h1>
                        <a href="{{ url('/front-request') }}" class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft">Usulan
                            Buku</a>
                        <a href="{{ url('/front-information') }}"
                            class="btn btn-outline-light py-md-3 px-md-5 animated slideInRight">Informasi</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

@push('scripts')
    <script defer>
        var carousel = document.getElementById('header-carousel');

        carousel.addEventListener('slide.bs.carousel', function() {
            setTimeout(function() {
                var next = (document.querySelector('.carousel-item.active').nextElementSibling || document
                    .querySelector('.carousel-item:first-child'));
                next.classList.add('active');
                document.querySelector('.carousel-item.active').classList.remove('active');
            }, 3000);
        });

        try {
            var carouselInstance = new bootstrap.Carousel(carousel, {
                interval: false
            });
        } catch (error) {
        }
    </script>
@endpush
