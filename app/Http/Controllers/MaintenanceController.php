<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        return redirect()->route('maintenance');
    }

    public function create()
    {
        return view('maintenances.create');
    }

    public function store(Request $request)
    {
        $maintenance = Maintenance::create($request->all());
        return redirect()->route('maintenances.index');
    }

    public function show(Maintenance $maintenance)
    {
        return view('maintenances.show', compact('maintenance'));
    }

    public function edit(Maintenance $maintenance)
    {
        return view('maintenances.edit', compact('maintenance'));
    }

    public function update(Request $request, Maintenance $maintenance)
    {
        $maintenance->update($request->all());
        return redirect()->route('maintenances.index');
    }

    public function destroy(Maintenance $maintenance)
    {
        $maintenance->delete();
        return redirect()->route('maintenances.index');
    }
}
