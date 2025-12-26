<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Create Setting
            </h2>
            <a href="{{ route('admin.settings.index') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-700 text-white font-semibold text-sm rounded-lg shadow hover:from-indigo-600 hover:to-purple-600 transition">
                <i class="fas fa-arrow-left mr-2"></i> Back to Settings
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded relative dark:bg-green-700 dark:text-white dark:border-green-600 mb-6">
                    <strong class="font-bold"><i class="fas fa-check-circle mr-2"></i>Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <form action="{{ route('admin.settings.store') }}" method="POST" class="space-y-5">
                    @csrf

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Key</label>
                        <input type="text" name="key" required
                               value="{{ old('key') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Display Name</label>
                        <input type="text" name="display_name" required
                               value="{{ old('display_name') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Value</label>
                        <input type="text" name="value"
                               value="{{ old('value') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Details</label>
                        <textarea name="details"
                                  class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                  rows="3">{{ old('details') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Type</label>
                        <select name="type" required
                                class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="text" {{ old('type') == 'text' ? 'selected' : '' }}>Text</option>
                            <option value="number" {{ old('type') == 'number' ? 'selected' : '' }}>Number</option>
                            <option value="boolean" {{ old('type') == 'boolean' ? 'selected' : '' }}>Boolean</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Order</label>
                        <input type="number" name="order"
                               value="{{ old('order') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Group</label>
                        <input type="text" name="group"
                               value="{{ old('group') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-gray-700 text-white font-semibold text-sm rounded-lg shadow hover:from-green-600 hover:to-emerald-600 transition">
                            <i class="fas fa-plus-circle mr-2"></i> Create Setting
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
