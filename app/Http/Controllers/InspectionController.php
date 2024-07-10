<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Inspections;
use Illuminate\Http\Request;

class InspectionController extends Controller
{
    
    public function index()
    {
        $inspections = Inspections::all();
        return view('inspections.index', compact('inspections'));
    }

    public function create()
    {
        return view('inspections.create');
    }

    public function store(Request $request)
    {
        $inspection = Inspections::create($request->all());
        return redirect()->route('inspections.index');
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

