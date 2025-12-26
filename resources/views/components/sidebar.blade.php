<div
    class="h-screen w-64 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 flex flex-col fixed left-0 top-0 z-50 shadow-xl">

    <!-- Logo -->
    <div
        class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
        <div
            class="w-10 h-10 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-lg font-bold shadow-md ring-2 ring-blue-200 dark:ring-blue-900">
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
                ['route' => 'admin.lead-statuses.index', 'label' => 'Lead Status', 'icon' => 'fas fa-tags'],
                ['route' => 'admin.followup-reasons.index', 'label' => 'Followup Reasons', 'icon' => 'fas fa-comment-dots'],
                ['route' => 'admin.settings.index', 'label' => 'Site Settings', 'icon' => 'fas fa-cogs'],
                ['route' => 'admin.roles.index', 'label' => 'Roles & Permissions', 'icon' => 'fas fa-user-shield'],
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

        <!-- ADMIN -->
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

            <a href="{{ url('/deploy') }}"
                class="sidebar-action text-green-600 dark:text-green-400">
                🚀 Run Deploy
            </a>

            <a href="{{ url('/run-npm-build') }}"
                class="sidebar-action text-indigo-600 dark:text-indigo-400">
                📦 NPM Build
            </a>

            <a href="{{ url('/optimize-app') }}"
                class="sidebar-action text-yellow-600 dark:text-yellow-400">
                ⚡ Optimize
            </a>

            <a href="{{ url('/link-storage') }}"
                class="sidebar-action text-blue-600 dark:text-blue-400">
                🔗 Storage Link
            </a>
        </div>

    </nav>

    <!-- Footer -->
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-500">
        © {{ date('Y') }} Yashi Associates
    </div>
</div>
