<?php


namespace App\Http\Controllers;

use App\Models\Inspector;
use Illuminate\Http\Request;

class InspectorController extends Controller
{
    public function index()
    {
        $inspectors = Inspector::all();
        return view('inspectors.index', compact('inspectors'));
    }

    public function create()
    {
        return view('inspectors.create');
    }

    public function store(Request $request)
    {
        $inspector = Inspector::create($request->all());
        return redirect()->route('inspectors.index');
    }

    public function show(Inspector $inspector)
    {
        return view('inspectors.show', compact('inspector'));
    }

    public function edit(Inspector $inspector)
    {
        return view('inspectors.edit', compact('inspector'));
    }

    public function update(Request $request, Inspector $inspector)
    {
        $inspector->update($request->all());
        return redirect()->route('inspectors.index');
    }

    public function destroy(Inspector $inspector)
    {
        $inspector->delete();
        return redirect()->route('inspectors.index');
    }
}

