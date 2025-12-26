@php
    $isActive = request()->routeIs(Str::replaceLast('.index', '*', $link['route']));
@endphp

<a href="{{ route($link['route']) }}"
    class="group relative flex items-center gap-3 px-3 py-2 rounded-lg transition-all
    {{ $isActive
        ? 'bg-blue-50 text-blue-600 font-semibold dark:bg-blue-900 dark:text-blue-400'
        : 'text-gray-700 dark:text-gray-200 hover:bg-blue-50 hover:text-blue-600 dark:hover:bg-blue-800 dark:hover:text-blue-400' }}">

    <!-- Active Indicator -->
    @if($isActive)
        <span class="absolute left-0 top-2 bottom-2 w-1 bg-blue-600 rounded-r"></span>
    @endif

    <i class="{{ $link['icon'] }} w-5 text-sm"></i>
    <span class="text-sm">{{ $link['label'] }}</span>
</a>
