@push('style')
    <style>

    </style>
@endpush
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/dashboard') }}" style="color: transparent !important;"><img
                            src="{{ asset('dist/images/logo/logo-poliwangi.png') }}" class="me-3" alt="Logo"
                            style="max-height: 75px; height: 65px;">SiPekan</a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            {{-- @php
                $roles = [
                    'Pustakawan' => config('menu.admin'),
                    // 'Pemustaka' => config('menu.pemustaka'),
                    'Penerbit' => config('menu.penerbit'),
                ];
            @endphp
            <ul class="menu">
                @foreach ($roles as $role => $menu)
                    @role($role)
                        @foreach ($menu as $category)
                            <li class="sidebar-title " data-bs-toggle="collapse" data-bs-target="#submenu">
                                {{ $category['title'] }}</li>
                            @foreach ($category['items'] as $item)
                                <li class="sidebar-item {{ Request::is("{$item['route']}*") ? 'active' : '' }}"
                                    id="submenu">
                                    <a href="{{ url('/' . $item['route']) }}" class="sidebar-link">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endforeach
                    @endrole
                @endforeach
            </ul> --}}

            {{-- Admin --}}

            @php
                $penerbitMenu = config('menu.penerbit');
            @endphp

            @role('Penerbit')
                <div class="sidebar-menu">
                    <ul class="menu">
                        @foreach ($penerbitMenu as $category)
                            <li class="sidebar-title" data-bs-toggle="collapse"
                                data-bs-target="#submenu-{{ $loop->index }}">
                                {{ $category['title'] }}


                            </li>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                class="bi bi-chevron-down bi-primary" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708" />
                            </svg>
                            @foreach ($category['items'] as $item)
                                <li class="sidebar-item {{ Request::is("{$item['route']}*") ? 'active' : '' }}"
                                    id="submenu-{{ $loop->parent->index }}">
                                    <a href="{{ url('/' . $item['route']) }}" class="sidebar-link">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endrole



            {{-- Pustakawan --}}
            @php
                $pustakawanMenu = config('menu.admin');
            @endphp
            @role('Pustakawan') <div class="sidebar-menu">
                    <ul class="menu">
                        @foreach ($pustakawanMenu as $category)
                            <ul class="sidebar-title d-flex justify-content-between" data-bs-toggle="collapse"
                                aria-controls="submenu-" aria-expanded="false"
                                data-bs-target="#submenu-{{ $loop->index }}">
                                <span>{{ $category['title'] }}</span>
                                <div class="rotate">
                                    <span class="bi bi-chevron-right chev"></span>
                                </div>

                            </ul>

                            @foreach ($category['items'] as $item)
                                <li class="sidebar-item {{ Request::is("{$item['route']}*") ? 'active' : '' }}"
                                    id="submenu-{{ $loop->parent->index }}"> <a href="{{ url('/' . $item['route']) }}"
                                        class="sidebar-link"> <i class="bi {{ $item['icon'] }}"></i>
                                        <span>{{ $item['label'] }}</span> </a> </li>
                            @endforeach
                        @endforeach
                    </ul>
            </div> @endrole

        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>

@push('script')
    <script defer>
        document.querySelectorAll('.sidebar-title').forEach(item => {
            item.addEventListener('click', function() {
                let icon = this.querySelector('.rotate .chev');
                if (this.getAttribute('aria-expanded') === 'true') {
                    icon.classList.remove('bi-chevron-right');
                    icon.classList.add('bi-chevron-down');
                } else {
                    icon.classList.remove('bi-chevron-down');
                    icon.classList.add('bi-chevron-right');
                }
            });
        });
    </script>
@endpush
