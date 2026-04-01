<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;


class DriverController extends Controller
{
    public function index()
    {
        return redirect()->route('crew');
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $driver = Driver::create($request->all());
        return redirect()->route('drivers.index');
    }

    public function show(Driver $driver)
    {
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $driver->update($request->all());
        return redirect()->route('drivers.index');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return redirect()->route('drivers.index');
    }

}
