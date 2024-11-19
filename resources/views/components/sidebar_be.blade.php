@push('style')
    <style>

    </style>
@endpush
<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex flex-column justify-content-center align-items-center ">
                <div class="logo mx-auto">
                    <div class="rounded rounded-circle">
                        <a href="{{ url('/dashboard') }}" style="color: transparent !important;" class="">
                            <img src="{{ asset('dist/images/logo/logo-poliwangi.png') }}" class="" alt="Logo"
                                style="max-height: 3rem; height: 3rem; margin:0.8rem">
                        </a>
                    </div>
                </div>
                <div class="user-name text-center mx-auto mt-3">
                    <h6 class="mb-0 text-gray-600">{{ auth()->user()->name }}</h6>
                    <p class="mb-0 text-sm text-gray-600">
                        {{ auth()->user()->getRoleNames()->contains('Penerbit') ? 'Penyedia ' . auth()->user()->getRoleNames()->implode(',') : auth()->user()->getRoleNames()->implode(',') }}
                    </p>
                </div>
                {{-- <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div> --}}
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

            {{-- Pustakawan --}}

            @php
                $penerbitMenu = config('menu.penerbit');
            @endphp

            @role('Penerbit')
                <div class="sidebar-menu">
                    <ul class="menu">
                        @foreach ($penerbitMenu as $category)
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

            {{-- Admin --}}
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
                                        <span>{{ $item['label'] }}</span> </a></li>
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
