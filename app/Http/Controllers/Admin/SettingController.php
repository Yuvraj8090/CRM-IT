<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::orderBy('order')->get();
        return view('admin.settings.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|unique:settings,key',
            'display_name' => 'required',
            'value' => 'nullable',
            'details' => 'nullable',
            'type' => 'required|in:text,image,boolean',
            'order' => 'nullable|integer',
            'group' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload if type is image
        if ($validated['type'] === 'image' && $request->hasFile('image')) {
            $path = $request->file('image')->store('settings', 'public');
            $validated['value'] = $path;
        }

        Setting::create($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Setting created successfully.');
    }

    public function edit(Setting $setting)
    {
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(Request $request, Setting $setting)
    {
        $validated = $request->validate([
            'key' => 'required|unique:settings,key,' . $setting->id,
            'display_name' => 'required',
            'value' => 'nullable',
            'details' => 'nullable',
            'type' => 'required|in:text,image,boolean',
            'order' => 'nullable|integer',
            'group' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle image upload if type is image
        if ($validated['type'] === 'image' && $request->hasFile('image')) {
            // Delete old image if exists
            if ($setting->value && Storage::disk('public')->exists($setting->value)) {
                Storage::disk('public')->delete($setting->value);
            }

            // Store new image
            $path = $request->file('image')->store('settings', 'public');
            $validated['value'] = $path;
        }

        $setting->update($validated);

        return redirect()->route('admin.settings.index')->with('success', 'Setting updated successfully.');
    }

    public function destroy(Setting $setting)
    {
        // Delete image if it's an image type
        if ($setting->type === 'image' && $setting->value && Storage::disk('public')->exists($setting->value)) {
            Storage::disk('public')->delete($setting->value);
        }

        $setting->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Setting deleted.');
    }
}
