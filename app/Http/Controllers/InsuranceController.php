<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    public function index()
    {
        $insurances = Insurance::all();
        return view('insurances.index', compact('insurances'));
    }

    public function create()
    {
        return view('insurances.create');
    }

    public function store(Request $request)
    {
        $insurance = Insurance::create($request->all());
        return redirect()->route('insurances.index');
    }

    public function show(Insurance $insurance)
    {
        return view('insurances.show', compact('insurance'));
    }

    public function edit(Insurance $insurance)
    {
        return view('insurances.edit', compact('insurance'));
    }

    public function update(Request $request, Insurance $insurance)
    {
        $insurance->update($request->all());
        return redirect()->route('insurances.index');
    }

    public function destroy(Insurance $insurance)
    {
        $insurance->delete();
        return redirect()->route('insurances.index');
    }
}
