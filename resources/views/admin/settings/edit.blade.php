<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Edit Setting
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

            <div class="bg-white dark:bg-gray-800 shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700 p-6"
                 x-data="{ type: '{{ old('type', $setting->type) }}' }">
                <form action="{{ route('admin.settings.update', $setting->id) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Key</label>
                        <input type="text" name="key" required
                               value="{{ old('key', $setting->key ?? '') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Display Name</label>
                        <input type="text" name="display_name" required
                               value="{{ old('display_name', $setting->display_name ?? '') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    {{-- Conditional Value Input --}}
                    <div x-show="type !== 'image'">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Value</label>
                        <input type="text" name="value"
                               value="{{ old('value', $setting->value ?? '') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div x-show="type === 'image'">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Upload Image</label>
                        <input type="file" name="image"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                        @if(!empty($setting->value))
                            <div class="mt-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Current Image:</p>
                                <img src="{{ asset('storage/' . $setting->value) }}" alt="Setting Image"
                                     class="max-h-40 rounded border border-gray-300 dark:border-gray-600">
                            </div>
                        @endif
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Details</label>
                        <textarea name="details"
                                  class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500"
                                  rows="3">{{ old('details', $setting->details ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Type</label>
                        <select name="type" x-model="type" required
                                class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="text">Text</option>
                            <option value="image">Image</option>
                            <option value="boolean">Boolean</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Order</label>
                        <input type="number" name="order"
                               value="{{ old('order', $setting->order ?? '') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300">Group</label>
                        <input type="text" name="group"
                               value="{{ old('group', $setting->group ?? '') }}"
                               class="w-full mt-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center px-5 py-2 bg-gray-700 text-white font-semibold text-sm rounded-lg shadow hover:from-green-600 hover:to-emerald-600 transition">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
