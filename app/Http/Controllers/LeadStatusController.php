<?php

namespace App\Http\Controllers;

use App\Models\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class LeadStatusController extends Controller
{
    /**
     * API: Get lead statuses for dropdowns
     */
    public function indexApi(Request $request)
    {
        $statuses = LeadStatus::where('is_active', true)
            ->orderBy('order_by')
            ->orderBy('name')
            ->get(['id', 'name', 'color', 'order_by']);

        return response()->json([
            'success' => true,
            'data' => $statuses,
        ]);
    }

    /**
     * Datatable View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(LeadStatus::query())
                ->addColumn('is_active', fn ($row) =>
                    $row->is_active
                        ? '<span class="text-green-600 font-semibold">Active</span>'
                        : '<span class="text-red-600 font-semibold">Inactive</span>'
                )
                ->addColumn('color', fn ($row) =>
                    '<span class="px-2 py-1 rounded text-white ' . $row->color . '">' . $row->color . '</span>'
                )
                ->addColumn('action', function ($row) {
                    $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    $btn =
                        '<button type="button" x-data x-on:click="$dispatch(\'edit-lead\', ' .
                        $data .
                        ')" class="px-3 py-1 bg-blue-600 text-white rounded text-sm mr-2">
                            Edit
                        </button>';

                    $btn .=
                        '<button type="button"
                            data-id="' . $row->id . '"
                            class="delete-btn px-3 py-1 bg-red-600 text-white rounded text-sm">
                            Delete
                        </button>';

                    return $btn;
                })
                ->rawColumns(['action', 'is_active', 'color'])
                ->make(true);
        }

        return view('lead_statuses.index');
    }

    /**
     * Store new Lead Status
     */
    public function store(Request $request)
    {
        $this->validateFields($request);

        $status = LeadStatus::create([
            'name' => $request->name,
            'color' => $request->color,
            'order_by' => $request->order_by ?? 0,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead Status saved successfully',
            'data' => $status,
        ]);
    }

    /**
     * Update Lead Status
     */
    public function update(Request $request, $id)
    {
        $this->validateFields($request, $id);

        $status = LeadStatus::findOrFail($id);

        $status->update([
            'name' => $request->name,
            'color' => $request->color,
            'order_by' => $request->order_by ?? 0,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead Status updated successfully',
            'data' => $status,
        ]);
    }

    /**
     * Delete Lead Status
     */
    public function destroy($id)
    {
        LeadStatus::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Lead Status deleted successfully',
        ]);
    }

    /**
     * Common validator
     */
    protected function validateFields(Request $request, $id = null)
    {
        $request->merge([
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        $request->validate([
            'name' => 'required|string|unique:lead_statuses,name' . ($id ? ",$id" : ''),
            'color' => 'required|string',
            'order_by' => 'nullable|integer',
            'is_active' => 'required|boolean',
        ]);
    }
}
