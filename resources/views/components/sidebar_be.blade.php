<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo">
                    <a href="{{ url('/dashboard') }}" style="color: transparent !important;"><img src="{{ asset('dist/images/logo/logo-poliwangi.png') }}"
                            class="me-3" alt="Logo" style="max-height: 75px; height: 65px;">SiPekan</a>
                </div>
                <div class="toggler">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            @php
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
                            <li class="sidebar-title">{{ $category['title'] }}</li>
                            @foreach ($category['items'] as $item)
                                <li class="sidebar-item {{ Request::is("{$item['route']}*") ? 'active' : '' }}">
                                    <a href="{{ url('/' . $item['route']) }}" class="sidebar-link">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                        <span>{{ $item['label'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endforeach
                    @endrole
                @endforeach
            </ul>

        </div>
        <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
    </div>
</div>
