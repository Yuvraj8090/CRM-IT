<?php

namespace App\Http\Controllers;

use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class PackageController extends Controller
{
    /**
     * Datatable View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            // ADMIN (role_id = 1) → see all (including deleted)
            $query = Auth::user()->role_id == 1
                ? Package::withTrashed()
                : Package::query();

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('pdf', function ($row) {
                    if ($row->pdf_path) {
                        return '<a href="' . asset('storage/' . $row->pdf_path) . '" target="_blank"
                            class="text-blue-600 underline">View PDF</a>';
                    }
                    return '-';
                })

                ->addColumn('is_active', function ($row) {
                    if ($row->deleted_at) {
                        return '<span class="text-gray-500 font-semibold">Deleted</span>';
                    }

                    return $row->is_active
                        ? '<span class="text-green-600 font-semibold">Active</span>'
                        : '<span class="text-red-600 font-semibold">Inactive</span>';
                })

                ->addColumn('action', function ($row) {

                    // RESTORE BUTTON (only admin & deleted)
                    if ($row->deleted_at && Auth::user()->role_id == 1) {
                        return '<button
                            data-id="' . $row->id . '"
                            class="restore-btn px-3 py-1 bg-green-600 text-white rounded text-sm">
                            Restore
                        </button>';
                    }

                    // NORMAL ACTIONS
                    $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    $btn = '<button type="button"
                        x-data
                        x-on:click="$dispatch(\'edit-package\', ' . $data . ')"
                        class="px-3 py-1 bg-blue-600 text-white rounded text-sm mr-2">
                        Edit
                    </button>';

                    $btn .= '<button
                        data-id="' . $row->id . '"
                        class="delete-btn px-3 py-1 bg-red-600 text-white rounded text-sm">
                        Delete
                    </button>';

                    return $btn;
                })

                ->rawColumns(['pdf', 'is_active', 'action'])
                ->make(true);
        }

        return view('packages.index');
    }

    /**
     * Store
     */
    public function store(Request $request)
    {
        $this->validateFields($request);

        $pdfPath = $request->hasFile('pdf')
            ? $request->file('pdf')->store('packages', 'public')
            : null;

        $package = Package::create([
            'name' => $request->name,
            'description' => $request->description,
            'pdf_path' => $pdfPath,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Package created successfully',
            'data' => $package,
        ]);
    }

    /**
     * Update
     */
    public function update(Request $request, $id)
    {
        $this->validateFields($request, $id);

        $package = Package::findOrFail($id);

        if ($request->hasFile('pdf')) {
            if ($package->pdf_path) {
                Storage::disk('public')->delete($package->pdf_path);
            }
            $package->pdf_path = $request->file('pdf')->store('packages', 'public');
        }

        $package->update([
            'name' => $request->name,
            'description' => $request->description,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Package updated successfully',
            'data' => $package,
        ]);
    }

    /**
     * Soft Delete
     */
    public function destroy($id)
    {
        Package::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Package deleted successfully',
        ]);
    }

    /**
     * Restore (ADMIN ONLY)
     */
    public function restore($id)
    {
        abort_if(Auth::user()->role_id != 1, 403);

        Package::withTrashed()->findOrFail($id)->restore();

        return response()->json([
            'status' => true,
            'message' => 'Package restored successfully',
        ]);
    }

    /**
     * Validator
     */
    protected function validateFields(Request $request, $id = null)
    {
        $request->merge([
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        $request->validate([
            'name' => 'required|string|max:255|unique:packages,name' . ($id ? ",$id" : ''),
            'description' => 'nullable|string',
            'pdf' => 'nullable|mimes:pdf|max:5120',
            'is_active' => 'required|boolean',
        ]);
    }
}
