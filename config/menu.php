<?php

return [
    'admin' => [
        [
            'title' => 'Beranda',
            'items' => [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-fill'],
            ],
        ],
        [
            'title' => 'Data',
            'items' => [
                ['route' => 'book-collection', 'label' => 'Koleksi Buku', 'icon' => 'bi-book-half'],
                ['route' => 'book-classification', 'label' => 'Klasifikasi Buku', 'icon' => 'bi-calendar-event'],
                ['route' => 'book-publisher', 'label' => 'Penerbit', 'icon' => 'bi-journal-bookmark-fill'],
                ['route' => 'user-alternative', 'label' => 'Pengajuan Pemustaka', 'icon' => 'bi-person-lines-fill'],
                ['route' => 'major', 'label' => 'Program Studi', 'icon' => 'bi-layer-backward'],
                ['route' => 'unit', 'label' => 'Unit Bidang', 'icon' => 'bi-building'],
            ],
        ],
        [
            'title' => 'Sistem Pengadaan Buku',
            'items' => [
                ['route' => 'weight-criteria', 'label' => 'Bobot Kriteria', 'icon' => 'bi-file-earmark-text-fill'],
                ['route' => 'book-budget', 'label' => 'Usulan Pengadaan', 'icon' => 'bi-wallet-fill'],
                // ['route' => 'book-recommendation', 'label' => 'Rekomendasi', 'icon' => 'bi-journal-text'],
            ],
        ],
        [
            'title' => 'Manajemen',
            'items' => [
                ['route' => 'user-management', 'label' => 'Pengguna', 'icon' => 'bi-person-badge-fill'],
                ['route' => 'role-management', 'label' => 'Hak Akses', 'icon' => 'bi-person-check-fill'],
                ['route' => 'permission-management', 'label' => 'Perizinan', 'icon' => 'bi-shield-fill-check'],
            ],
        ],
    ],
    'pemustaka' => [
        [
            'title' => 'Beranda',
            'items' => [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-fill'],
            ],
        ],
        [
            'title' => 'Pengajuan Buku',
            'items' => [
                ['route' => 'user-book', 'label' => 'Alternatif Pemustaka', 'icon' => 'bi-person-lines-fill'],
                ['route' => 'user-realization', 'label' => 'Koleksi Terealisasi', 'icon' => 'bi-journal-text'],
            ],
        ],
        [
            'title' => 'Manajemen',
            'items' => [
                ['route' => 'profile-management', 'label' => 'Profil', 'icon' => 'bi-person-badge-fill'],
            ],
        ],
    ],
    'penerbit' => [
        [
            'title' => 'Beranda',
            'items' => [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'bi-grid-fill'],
            ],
        ],
        [
            'title' => 'Data',
            'items' => [
                ['route' => 'book-list', 'label' => 'Buku', 'icon' => 'bi-journal-bookmark-fill'],
            ],
        ],
        [
            'title' => 'Manajemen',
            'items' => [
                ['route' => 'profile-management', 'label' => 'Profil', 'icon' => 'bi-person-badge-fill'],
            ],
        ],
    ],
];
