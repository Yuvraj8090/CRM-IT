<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        // AJAX request (DataTable)
        if ($request->ajax()) {
            return DataTables::of(Role::query())
                ->addIndexColumn()
                ->addColumn('action', function ($role) {
                    return view('roles.partials.actions', compact('role'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        // Normal page load
        return view('roles.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Role created successfully']);
    }

    public function edit(Role $role)
    {
        return response()->json($role);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }
}
