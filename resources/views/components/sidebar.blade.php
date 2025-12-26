<div
    class="h-screen w-64 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200 flex flex-col fixed left-0 top-0 z-50 shadow-lg">

    <!-- Logo -->
    <div
    class="p-4 text-xl font-bold border-b border-gray-200 dark:border-gray-700 flex items-center gap-3">
    <div
        class="w-8 h-8 flex items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-600 text-white text-lg font-bold">
        YA
    </div>
    <span class="tracking-wide text-gray-800 dark:text-gray-100">
        Yashi Associates
    </span>
</div>


    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('error') }}",
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                });
            });
        </script>
    @endif

    <nav class="flex-1 p-4 space-y-2 overflow-y-auto">

        @php
            $links = [
                ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'fas fa-chart-line'],

                ['route' => 'profile.show', 'label' => 'Profile', 'icon' => 'fas fa-user-circle'],

                ['route' => 'admin.lead-statuses.index', 'label' => 'Lead Status', 'icon' => 'fas fa-tags'],
                ['route' => 'admin.followup-reasons.index', 'label' => 'Followup Reasons', 'icon' => 'fas fa-tags'],

                ['route' => 'admin.roles.index', 'label' => 'Roles & Permissions', 'icon' => 'fas fa-user-shield'],
            ];
        @endphp


        @foreach ($links as $link)
            <a href="{{ route($link['route']) }}"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg
                transition-all
                {{ request()->routeIs(Str::replaceLast('.index', '*', $link['route']))
                    ? 'bg-blue-50 text-blue-600 font-semibold dark:bg-blue-900 dark:text-blue-400'
                    : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-800 dark:hover:text-blue-400' }}">
                <i class="{{ $link['icon'] }} w-5"></i>
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>

        <!-- Action Buttons -->
        <div class="space-y-1">
            <a href="{{ url('/deploy') }}"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg
                      transition-all text-gray-700 dark:text-gray-200
                      hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900 dark:hover:text-green-400">
                <span>🚀 Run Deploy</span>
            </a>
            <a href="{{ url('/run-npm-build') }}"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg
                      transition-all text-gray-700 dark:text-gray-200
                      hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900 dark:hover:text-green-400">
                <span>📦 NPM Build</span>
            </a>
            <a href="{{ url('/optimize-app') }}"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg
                      transition-all text-gray-700 dark:text-gray-200
                      hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900 dark:hover:text-green-400">
                <span>⚡ Optimize</span>
            </a>
            <a href="{{ url('/link-storage') }}"
                class="flex items-center space-x-3 px-3 py-2 rounded-lg
                      transition-all text-gray-700 dark:text-gray-200
                      hover:bg-green-50 hover:text-green-600 dark:hover:bg-green-900 dark:hover:text-green-400">
                <span>🔗 Storage Link</span>
            </a>
        </div>

    </nav>
</div>
