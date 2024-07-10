<?php

namespace App\Http\Controllers;

use App\Models\Manager;
use Illuminate\Http\Request;

class ManagerController extends Controller
{
    public function index()
    {
        $managers = Manager::all();
        return view('managers.index', compact('managers'));
    }

    public function create()
    {
        return view('managers.create');
    }

    public function store(Request $request)
    {
        $manager = Manager::create($request->all());
        return redirect()->route('managers.index');
    }

    public function show(Manager $manager)
    {
        return view('managers.show', compact('manager'));
    }

    public function edit(Manager $manager)
    {
        return view('managers.edit', compact('manager'));
    }

    public function update(Request $request, Manager $manager)
    {
        $manager->update($request->all());
        return redirect()->route('managers.index');
    }

    public function destroy(Manager $manager)
    {
        $manager->delete();
        return redirect()->route('managers.index');
    }
}

