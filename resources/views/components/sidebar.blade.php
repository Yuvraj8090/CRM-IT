<div class="h-screen w-64 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 flex flex-col fixed left-0 top-0 z-50 shadow-xl">

    <!-- Logo -->
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
        <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-lg font-bold shadow-md ring-2 ring-blue-200 dark:ring-blue-900">
            YA
        </div>
        <div class="leading-tight">
            <div class="text-sm font-semibold tracking-wide">
                Yashi Associates
            </div>
            <div class="text-xs text-gray-500 dark:text-gray-400">
                Admin Panel
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-3 py-4 space-y-6 overflow-y-auto">

        @php
        $mainLinks = [
            ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fas fa-chart-line'],
            ['route' => 'profile.show', 'label' => 'Profile', 'icon' => 'fas fa-user-circle'],
        ];

        $adminLinks = [
            ['route' => 'admin.packages.index', 'label' => 'Packages', 'icon' => 'fas fa-box-open'],
            ['route' => 'admin.lead-statuses.index', 'label' => 'Lead Statuses', 'icon' => 'fas fa-tags'],
            ['route' => 'admin.followup-reasons.index', 'label' => 'Follow-up Reasons', 'icon' => 'fas fa-comment-dots'],
            ['route' => 'admin.settings.index', 'label' => 'Site Settings', 'icon' => 'fas fa-cogs'],
            ['route' => 'admin.roles.index', 'label' => 'Roles & Permissions', 'icon' => 'fas fa-user-shield'],
            ['route' => 'admin.leads.index', 'label' => 'Leads', 'icon' => 'fas fa-users'],
        ];

        $systemLinks = [
            ['url' => url('/deploy'), 'label' => 'Deploy', 'icon' => 'fas fa-rocket', 'color' => 'text-green-600 dark:text-green-400'],
            ['url' => url('/run-npm-build'), 'label' => 'NPM Build', 'icon' => 'fas fa-box', 'color' => 'text-indigo-600 dark:text-indigo-400'],
            ['url' => url('/optimize-app'), 'label' => 'Optimize', 'icon' => 'fas fa-bolt', 'color' => 'text-yellow-600 dark:text-yellow-400'],
            ['url' => url('/link-storage'), 'label' => 'Storage Link', 'icon' => 'fas fa-link', 'color' => 'text-blue-600 dark:text-blue-400'],
        ];
        @endphp

        <!-- MAIN -->
        <div>
            <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Main
            </p>
            @foreach ($mainLinks as $link)
                @include('partials.sidebar-link', ['link' => $link])
            @endforeach
        </div>

        <!-- ADMINISTRATION -->
        <div>
            <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                Administration
            </p>
            @foreach ($adminLinks as $link)
                @include('partials.sidebar-link', ['link' => $link])
            @endforeach
        </div>

        <!-- SYSTEM -->
        <div>
            <p class="px-3 mb-2 text-xs font-semibold uppercase tracking-wider text-gray-400">
                System
            </p>
            @foreach ($systemLinks as $link)
                <a href="{{ $link['url'] }}" class="flex items-center px-3 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 {{ $link['color'] }}">
                    <i class="{{ $link['icon'] }} w-5"></i>
                    <span class="ml-3 text-sm font-medium">{{ $link['label'] }}</span>
                </a>
            @endforeach
        </div>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500">
        © {{ date('Y') }} Yashi Associates
    </div>
</div>
