@php
    $user = auth()->user();
    $isLibraryTeam = $user?->hasAnyRole(['Super Admin', 'Librarian', 'Staff']);
    $canManageReports = $user?->hasAnyRole(['Super Admin', 'Librarian']);
    $canManageSystem = $user?->hasRole('Super Admin');

    $menuGroups = [
        'Utama' => [
            ['label' => 'Dashboard', 'route' => 'dashboard', 'active' => 'dashboard'],
            ['label' => 'Portal Publik', 'route' => 'home', 'active' => 'home'],
        ],
        'Layanan Anggota' => [
            ['label' => 'Cari Koleksi', 'route' => 'collections', 'active' => 'collections'],
            ['label' => 'E-Library', 'route' => 'ebooks', 'active' => 'ebooks'],
            ['label' => 'Repository', 'route' => 'repositories', 'active' => 'repositories'],
            ['label' => 'Profil Saya', 'route' => 'profile.edit', 'active' => 'profile.*'],
        ],
    ];

    if ($isLibraryTeam) {
        $menuGroups['Operasional'] = [
            ['label' => 'Admin Overview', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
            ['label' => 'Peminjaman', 'route' => 'admin.crud.index', 'params' => 'borrowings', 'active' => 'admin/borrowings*'],
            ['label' => 'Denda', 'route' => 'admin.crud.index', 'params' => 'fines', 'active' => 'admin/fines*'],
        ];

        $menuGroups['Master Data'] = [
            ['label' => 'Buku', 'route' => 'admin.crud.index', 'params' => 'books', 'active' => 'admin/books*'],
            ['label' => 'Kategori', 'route' => 'admin.crud.index', 'params' => 'book-categories', 'active' => 'admin/book-categories*'],
            ['label' => 'Penulis', 'route' => 'admin.crud.index', 'params' => 'authors', 'active' => 'admin/authors*'],
            ['label' => 'Penerbit', 'route' => 'admin.crud.index', 'params' => 'publishers', 'active' => 'admin/publishers*'],
            ['label' => 'Rak Buku', 'route' => 'admin.crud.index', 'params' => 'shelves', 'active' => 'admin/shelves*'],
            ['label' => 'User', 'route' => 'admin.crud.index', 'params' => 'users', 'active' => 'admin/users*'],
        ];

        $menuGroups['Konten Portal'] = [
            ['label' => 'Slider', 'route' => 'admin.crud.index', 'params' => 'sliders', 'active' => 'admin/sliders*'],
            ['label' => 'Berita', 'route' => 'admin.crud.index', 'params' => 'news', 'active' => 'admin/news*'],
            ['label' => 'Event', 'route' => 'admin.crud.index', 'params' => 'events', 'active' => 'admin/events*'],
            ['label' => 'FAQ', 'route' => 'admin.crud.index', 'params' => 'faqs', 'active' => 'admin/faqs*'],
            ['label' => 'Testimoni', 'route' => 'admin.crud.index', 'params' => 'testimonials', 'active' => 'admin/testimonials*'],
            ['label' => 'Galeri', 'route' => 'admin.crud.index', 'params' => 'galleries', 'active' => 'admin/galleries*'],
            ['label' => 'Halaman', 'route' => 'admin.crud.index', 'params' => 'pages', 'active' => 'admin/pages*'],
            ['label' => 'Ebook', 'route' => 'admin.crud.index', 'params' => 'ebooks', 'active' => 'admin/ebooks*'],
            ['label' => 'Repository', 'route' => 'admin.crud.index', 'params' => 'repositories', 'active' => 'admin/repositories*'],
        ];
    }

    if ($canManageReports) {
        $menuGroups['Laporan'] = [
            ['label' => 'Rekap Laporan', 'route' => 'admin.reports.index', 'active' => 'admin/reports*'],
        ];
    }

    if ($canManageSystem) {
        $menuGroups['Sistem'] = [
            ['label' => 'Role', 'route' => 'admin.crud.index', 'params' => 'roles', 'active' => 'admin/roles*'],
            ['label' => 'Permission', 'route' => 'admin.crud.index', 'params' => 'permissions', 'active' => 'admin/permissions*'],
            ['label' => 'Setting', 'route' => 'admin.crud.index', 'params' => 'settings', 'active' => 'admin/settings*'],
        ];
    }
@endphp

<aside class="flex min-h-screen w-72 shrink-0 flex-col bg-[#004225] text-white">
    <div class="border-b border-white/10 p-5">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
            <span class="grid h-11 w-11 place-items-center rounded-md bg-white font-bold text-[#006633]">UML</span>
            <span>
                <span class="block text-sm font-bold">E-Library UML</span>
                <span class="block text-xs text-emerald-100">Library Management</span>
            </span>
        </a>
    </div>

    <nav class="flex-1 space-y-5 overflow-y-auto p-4">
        @foreach($menuGroups as $group => $items)
            <div>
                <p class="px-3 text-xs font-bold uppercase tracking-wide text-[#D4AF37]">{{ $group }}</p>
                <div class="mt-2 space-y-1">
                    @foreach($items as $item)
                        @php
                            $href = isset($item['params']) ? route($item['route'], $item['params']) : route($item['route']);
                            $active = str_contains($item['active'], '*') ? request()->is($item['active']) : request()->routeIs($item['active']);
                        @endphp
                        <a href="{{ $href }}" class="flex items-center justify-between rounded-md px-3 py-2 text-sm font-medium transition {{ $active ? 'bg-white text-[#006633]' : 'text-emerald-50 hover:bg-white/10' }}">
                            <span>{{ $item['label'] }}</span>
                            @if($active)
                                <span class="h-2 w-2 rounded-full bg-[#D4AF37]"></span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>

    <div class="border-t border-white/10 p-4">
        <div class="rounded-md bg-white/10 p-3">
            <p class="text-sm font-semibold">{{ $user?->name }}</p>
            <p class="mt-1 truncate text-xs text-emerald-100">{{ $user?->email }}</p>
            <p class="mt-2 inline-block rounded bg-[#D4AF37] px-2 py-1 text-xs font-bold text-[#17351f]">{{ $user?->getRoleNames()->first() ?? 'Anggota' }}</p>
        </div>
        <form class="mt-3" method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full rounded-md border border-white/20 px-3 py-2 text-sm font-semibold text-white hover:bg-white/10">Logout</button>
        </form>
    </div>
</aside>
