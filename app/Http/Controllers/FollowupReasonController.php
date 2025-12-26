<?php

namespace App\Http\Controllers;

use App\Models\FollowupReason;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FollowupReasonController extends Controller
{
    /**
     * API: Get Followup Reasons (Dropdown / API)
     */
    public function indexApi(Request $request)
    {
        $reasons = FollowupReason::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'remark', 'date', 'time', 'email_template', 'whatsapp_template']);

        return response()->json([
            'success' => true,
            'data' => $reasons,
        ]);
    }

    /**
     * Datatable View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(FollowupReason::query())
                ->addColumn('remark', fn($row) => $row->remark ? 'Yes' : 'No')
                ->addColumn('date', fn($row) => $row->date ? 'Yes' : 'No')
                ->addColumn('time', fn($row) => $row->time ? 'Yes' : 'No')
                ->addColumn('is_active', fn($row) => $row->is_active ? 'Active' : 'Inactive')
                ->addColumn('action', function ($row) {
                    $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    $btn =
                        '<button type="button" x-data x-on:click="$dispatch(\'edit-followup\', ' .
                        $data .
                        ')" class="px-3 py-1 bg-blue-600 text-white rounded text-sm mr-2">
                            Edit
                        </button>';

                    $btn .=
                        '<button type="button"
                            data-id="' .
                        $row->id .
                        '"
                            class="delete-btn px-3 py-1 bg-red-600 text-white rounded text-sm">
                            Delete
                        </button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('followup_reasons.index');
    }

    /**
     * Store Followup Reason
     */
    public function store(Request $request)
    {
        $this->validateFields($request);

        $reason = FollowupReason::create([
            'name' => $request->name,
            'remark' => $request->remark,
            'date' => $request->date,
            'time' => $request->time,
            'email_template' => $request->email_template,
            'whatsapp_template' => $request->whatsapp_template,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Followup Reason saved successfully',
            'data' => $reason,
        ]);
    }

    /**
     * Update Followup Reason
     */
    public function update(Request $request, $id)
    {
        $this->validateFields($request, $id);

        $reason = FollowupReason::findOrFail($id);

        $reason->update([
            'name' => $request->name,
            'remark' => $request->remark,
            'date' => $request->date,
            'time' => $request->time,
            'email_template' => $request->email_template,
            'whatsapp_template' => $request->whatsapp_template,
            'is_active' => $request->is_active,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Followup Reason updated successfully',
            'data' => $reason,
        ]);
    }

    /**
     * Delete Followup Reason
     */
    public function destroy($id)
    {
        FollowupReason::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Followup Reason deleted successfully',
        ]);
    }

    /**
     * Common Validator
     */
    protected function validateFields(Request $request, $id = null)
    {
        $request->merge([
            'remark' => filter_var($request->remark, FILTER_VALIDATE_BOOLEAN),
            'date' => filter_var($request->date, FILTER_VALIDATE_BOOLEAN),
            'time' => filter_var($request->time, FILTER_VALIDATE_BOOLEAN),
            'is_active' => filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN),
        ]);

        $request->validate([
            'name' => 'required|string|unique:followup_reasons,name' . ($id ? ",$id" : ''),
            'remark' => 'required|boolean',
            'date' => 'required|boolean',
            'time' => 'required|boolean',
            'email_template' => 'nullable|string',
            'whatsapp_template' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);
    }
}
