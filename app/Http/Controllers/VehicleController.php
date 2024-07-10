<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController
{
        public function index()
        {
            $vehicles = Vehicle::all();
            return view('vehicles.index', compact('vehicles'));
        }
    
        public function create()
        {
            return view('vehicles.create');
        }
    
        public function store(Request $request)
        {
            $vehicle = Vehicle::create($request->all());
            return redirect()->route('vehicles.index');
        }
    
        public function show(Vehicle $vehicle)
        {
            return view('vehicles.show', compact('vehicle'));
        }
    
        public function edit(Vehicle $vehicle)
        {
            return view('vehicles.edit', compact('vehicle'));
        }
    
        public function update(Request $request, Vehicle $vehicle)
        {
            $vehicle->update($request->all());
            return redirect()->route('vehicles.index');
        }
    
        public function destroy(Vehicle $vehicle)
        {
            $vehicle->delete();
            return redirect()->route('vehicles.index');
        }
    }
    
