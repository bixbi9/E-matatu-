<?php

namespace App\Http\Controllers;

use App\Models\Inspections;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    public function index()
    {
        return redirect()->route('inspection');
    }

    public function create()
    {
        return view('inspections.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_id'       => 'required|integer',
            'inspector_name'   => 'required|string|max:50',
            'result'           => 'required|string|max:20',
            'comments'         => 'nullable|string',
            'rating'           => 'nullable|string|max:50',
            'status'           => 'nullable|string|max:50',
            'inspection_date'  => 'required|date',
            'evaluation_form'  => 'nullable|string',
            'maintenance_type' => 'nullable|string|max:50',
        ]);

        Inspections::create($request->all());

        return redirect()->route('inspections.index')->with('success', 'Inspection created successfully.');
    }

    public function show(Inspections $inspection)
    {
        return view('inspections.show', compact('inspection'));
    }

    public function edit(Inspections $inspection)
    {
        return view('inspections.edit', compact('inspection'));
    }

    public function update(Request $request, Inspections $inspection)
    {
        $inspection->update($request->all());
        return redirect()->route('inspections.index');
    }

    public function destroy(Inspections $inspection)
    {
        $inspection->delete();
        return redirect()->route('inspections.index');
    }
}
