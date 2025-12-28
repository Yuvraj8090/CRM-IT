<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Package;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class LeadController extends Controller
{
    /**
     * Datatable view
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Lead::with('package');

            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('package', function ($row) {
                    return $row->package ? $row->package->name : '-';
                })

                ->addColumn('action', function ($row) {
                    $data = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');

                    $btn = '<button type="button"
                        x-data
                        x-on:click="$dispatch(\'edit-lead\', ' . $data . ')"
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

                ->rawColumns(['action'])
                ->make(true);
        }

        return view('leads.index');
    }

    /**
     * Store a new lead
     */
    public function store(Request $request)
    {
        $this->validateFields($request);

        $lead = Lead::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profession' => $request->profession,
            'lead_status' => $request->lead_status,
            'package_id' => $request->package_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead created successfully',
            'data' => $lead,
        ]);
    }

    /**
     * Update a lead
     */
    public function update(Request $request, $id)
    {
        $this->validateFields($request, $id);

        $lead = Lead::findOrFail($id);

        $lead->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'profession' => $request->profession,
            'lead_status' => $request->lead_status,
            'package_id' => $request->package_id,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Lead updated successfully',
            'data' => $lead,
        ]);
    }

    /**
     * Delete a lead
     */
    public function destroy($id)
    {
        Lead::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Lead deleted successfully',
        ]);
    }

    /**
     * Validator
     */
    protected function validateFields(Request $request, $id = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:leads,email' . ($id ? ",$id" : ''),
            'phone' => 'required|string|max:20',
            'profession' => 'nullable|string|max:255',
            'lead_status' => 'nullable|string|max:255',
            'package_id' => 'required|exists:packages,id',
        ]);
    }
}
