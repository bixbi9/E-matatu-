<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Insurance;
use App\Models\Inspections;
use App\Models\Maintenance;
use App\Models\Route as FleetRoute;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('adminpanel', $this->overviewData());
    }

    public function matatus(): View
    {
        $vehicles = $this->vehicles();
        $drivers = $this->drivers();
        $routes = $this->routeRecords();

        return view('dashboard.matatus', [
            ...$this->fleetConnectionViewData(),
            'vehicles' => $vehicles,
            'drivers' => $drivers,
            'routes' => $routes,
            'routePersistenceReady' => $this->routePersistenceReady(),
            'assignedVehicles' => $this->assignedVehicles($vehicles, $drivers, $routes),
            'activeVehicles' => $vehicles->where('status', 'Active')->count(),
        ]);
    }

    public function assignMatatu(Request $request): RedirectResponse
    {
        if (! $this->routePersistenceReady()) {
            return back()->withErrors([
                'route_assignment' => 'Route assignments need the latest migration before they can be saved.',
            ]);
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,vehicle_id',
            'route_id' => 'required|integer|exists:routes,route_id',
            'current_driver_id' => 'nullable|integer|exists:drivers,driver_id',
            'status' => 'nullable|string|max:20',
        ]);

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $vehicle->route_id = $validated['route_id'];
        $vehicle->current_driver_id = $validated['current_driver_id'] ?? null;
        $vehicle->status = $validated['status'] ?? $vehicle->status;
        $vehicle->save();

        $route = FleetRoute::find($validated['route_id']);
        if ($route) {
            $route->driver_id = $validated['current_driver_id'] ?? $route->driver_id;
            $route->save();
        }

        return redirect()->route('matatus')->with('status', 'Matatu assignment saved.');
    }

    public function crew(): View
    {
        $drivers = $this->drivers();
        $vehicles = $this->vehicles();
        $routes = $this->routeRecords();

        return view('dashboard.crew', [
            ...$this->fleetConnectionViewData(),
            'drivers' => $drivers,
            'vehicles' => $vehicles,
            'routes' => $routes,
            'routePersistenceReady' => $this->routePersistenceReady(),
            'driverAssignments' => $this->driverAssignments($drivers, $vehicles, $routes),
        ]);
    }

    public function assignCrewRoute(Request $request): RedirectResponse
    {
        if (! $this->routePersistenceReady()) {
            return back()->withErrors([
                'crew_assignment' => 'Crew route assignments need the latest migration before they can be saved.',
            ]);
        }

        $validated = $request->validate([
            'driver_id' => 'required|integer|exists:drivers,driver_id',
            'route_id' => 'required|integer|exists:routes,route_id',
            'vehicle_id' => 'nullable|integer|exists:vehicles,vehicle_id',
            'status' => 'nullable|string|max:20',
        ]);

        $route = FleetRoute::findOrFail($validated['route_id']);
        $route->driver_id = $validated['driver_id'];
        $route->save();

        if (! empty($validated['vehicle_id'])) {
            $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
            $vehicle->current_driver_id = $validated['driver_id'];
            $vehicle->route_id = $validated['route_id'];
            $vehicle->save();
        }

        if (! empty($validated['status'])) {
            $driver = Driver::findOrFail($validated['driver_id']);
            $driver->status = $validated['status'];
            $driver->save();
        }

        return redirect()->route('crew')->with('status', 'Crew driver assignment saved.');
    }

    public function inspections(Request $request): View
    {
        $vehicles = $this->vehicles();

        return view('dashboard.inspections', [
            ...$this->fleetConnectionViewData(),
            'inspections' => $this->filteredInspections($request),
            'vehicles' => $vehicles,
            'selectedVehicleId' => $request->input('vehicle_id'),
            'selectedResult' => $request->input('result'),
        ]);
    }

    public function maintenance(): View
    {
        return view('dashboard.maintenance', [
            ...$this->fleetConnectionViewData(),
            'maintenances' => $this->maintenances(),
        ]);
    }

    public function insurance(): View
    {
        return view('dashboard.insurance', [
            ...$this->fleetConnectionViewData(),
            'insurances' => $this->insurances(),
            'vehicles' => $this->vehicles(),
            'expiringInsurance' => $this->expiringInsuranceCount(),
        ]);
    }

    public function storeInsurance(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,vehicle_id',
            'policy_number' => 'required|string|max:50',
            'provider' => 'required|string|max:50',
            'start_date' => 'required|date',
            'expiry_date' => 'required|date|after_or_equal:start_date',
            'coverage_details' => 'nullable|string',
        ]);

        Insurance::create($validated);

        return redirect()->route('insurance')->with('status', 'Insurance policy added.');
    }

    private function overviewData(): array
    {
        $vehicles = $this->vehicles();
        $drivers = $this->drivers();
        $routes = $this->routeRecords();
        $inspections = $this->inspectionsCollection();
        $maintenances = $this->maintenances();

        return [
            ...$this->fleetConnectionViewData(),
            'totalVehicles' => $vehicles->count(),
            'activeVehicles' => $vehicles->where('status', 'Active')->count(),
            'totalDrivers' => $drivers->count(),
            'activeDrivers' => $drivers->where('status', 'Active')->count(),
            'pendingMaint' => $maintenances->where('status', 'In Progress')->count(),
            'expiringInsurance' => $this->expiringInsuranceCount(),
            'recentInspections' => $inspections->take(5)->values(),
            'recentDrivers' => $drivers->take(8)->values(),
            'assignedVehicles' => $this->assignedVehicles($vehicles, $drivers, $routes)->take(8)->values(),
            'routeCatalog' => $routes,
        ];
    }

    private function fleetConnectionViewData(): array
    {
        try {
            Vehicle::query()->limit(1)->get();

            return [
                'fleetConnectionOk' => true,
                'fleetConnectionMessage' => 'Live fleet records are loading from Supabase.',
            ];
        } catch (\Throwable) {
            return [
                'fleetConnectionOk' => false,
                'fleetConnectionMessage' => 'Fleet data is not loading from Supabase. Check the Supabase DB host, SSL settings, and cached Laravel config.',
            ];
        }
    }

    private function vehicles(): Collection
    {
        if (! $this->tableExists('vehicles')) {
            return collect();
        }

        return Vehicle::orderBy('vehicle_id')->get();
    }

    private function drivers(): Collection
    {
        if (! $this->tableExists('drivers')) {
            return collect();
        }

        return Driver::orderByDesc('driver_id')->get();
    }

    private function inspectionsCollection(): Collection
    {
        if (! $this->tableExists('inspections')) {
            return collect();
        }

        return Inspections::orderByDesc('inspection_date')->get();
    }

    private function filteredInspections(Request $request): Collection
    {
        if (! $this->tableExists('inspections')) {
            return collect();
        }

        $query = Inspections::query();

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->input('vehicle_id'));
        }

        if ($request->filled('result')) {
            $query->where('result', $request->input('result'));
        }

        return $query->orderByDesc('inspection_date')->get();
    }

    private function maintenances(): Collection
    {
        if (! $this->tableExists('maintenance')) {
            return collect();
        }

        return Maintenance::orderByDesc('date')->get();
    }

    private function insurances(): Collection
    {
        if (! $this->tableExists('insurance')) {
            return collect();
        }

        return Insurance::orderByDesc('expiry_date')->get();
    }

    private function expiringInsuranceCount(): int
    {
        return $this->insurances()
            ->filter(function ($insurance) {
                if (empty($insurance->expiry_date)) {
                    return false;
                }

                return $insurance->expiry_date <= now()->addDays(30)->toDateString();
            })
            ->count();
    }

    private function routeRecords(): Collection
    {
        if (! $this->tableExists('routes')) {
            return collect(config('digital_matatus.routes', []))
                ->map(fn (array $route) => $this->mapRouteArray(null, $route['code'], $route['destination'], null));
        }

        if ($this->routePersistenceReady()) {
            $this->syncRouteCatalog();

            return FleetRoute::whereNotNull('route_code')
                ->orderBy('route_code')
                ->get()
                ->map(function (FleetRoute $route) {
                    return $this->mapRouteArray(
                        $route->route_id,
                        $route->route_code,
                        $route->route_name,
                        $route->driver_id
                    );
                });
        }

        return FleetRoute::orderBy('route_id')
            ->get()
            ->map(function (FleetRoute $route) {
                return $this->mapRouteArray(
                    $route->route_id,
                    'Route ' . $route->route_id,
                    $route->end_location ?: $route->start_location ?: 'Nairobi Route',
                    $route->driver_id
                );
            });
    }

    private function syncRouteCatalog(): void
    {
        foreach (config('digital_matatus.routes', []) as $route) {
            FleetRoute::updateOrCreate(
                ['route_code' => $route['code']],
                [
                    'route_name' => $route['destination'],
                    'start_location' => 'Nairobi CBD',
                    'end_location' => $route['destination'],
                    'status' => 'Active',
                    'source_label' => config('digital_matatus.source_label'),
                ]
            );
        }
    }

    private function assignedVehicles(Collection $vehicles, Collection $drivers, Collection $routes): Collection
    {
        return $vehicles->values()->map(function ($vehicle) use ($drivers, $routes) {
            $route = $this->resolveVehicleRoute($vehicle, $routes);
            $driverId = $vehicle->current_driver_id ?: data_get($route, 'driver_id');
            $driver = $drivers->firstWhere('driver_id', $driverId);
            $status = $vehicle->status ?: 'Unknown';

            return [
                'vehicle_id' => $vehicle->vehicle_id,
                'vehicle_name' => $vehicle->vin ? 'Matatu ' . $vehicle->vin : 'Matatu #' . $vehicle->vehicle_id,
                'license_plate' => $vehicle->license_plate ?: 'Not set',
                'inspection_date' => $vehicle->inspection_date ?: 'Not set',
                'route_id' => data_get($route, 'route_id'),
                'route_label' => data_get($route, 'route_label', 'Not assigned'),
                'driver_name' => $driver ? trim($driver->first_name . ' ' . $driver->last_name) : 'No driver assigned',
                'status' => $status,
                'status_tone' => $this->statusTone($status),
            ];
        });
    }

    private function driverAssignments(Collection $drivers, Collection $vehicles, Collection $routes): Collection
    {
        return $drivers->values()->map(function ($driver) use ($vehicles, $routes) {
            $vehicle = $vehicles->firstWhere('current_driver_id', $driver->driver_id);
            $route = $routes->first(function ($route) use ($driver) {
                return (int) data_get($route, 'driver_id') === (int) $driver->driver_id;
            });

            if (! $route && $vehicle) {
                $route = $routes->first(function ($route) use ($vehicle) {
                    return (int) data_get($route, 'route_id') === (int) $vehicle->route_id;
                });
            }

            $status = $driver->status ?: 'Unknown';

            return [
                'driver_id' => $driver->driver_id,
                'driver_name' => trim($driver->first_name . ' ' . $driver->last_name) ?: 'Crew Member ' . $driver->driver_id,
                'phone_number' => $driver->phone_number ?: 'Not set',
                'license_number' => $driver->license_number ?: 'Not set',
                'route_label' => data_get($route, 'route_label', 'Not assigned'),
                'vehicle_label' => $vehicle ? ($vehicle->license_plate ?: 'Vehicle #' . $vehicle->vehicle_id) : 'Not assigned',
                'status' => $status,
                'status_tone' => $this->statusTone($status),
            ];
        });
    }

    private function resolveVehicleRoute(Vehicle $vehicle, Collection $routes): ?array
    {
        if ($routes->isEmpty()) {
            return null;
        }

        $matched = $routes->first(function ($route) use ($vehicle) {
            return (int) data_get($route, 'route_id') === (int) $vehicle->route_id;
        });

        if ($matched) {
            return $matched;
        }

        return null;
    }

    private function mapRouteArray(?int $routeId, ?string $routeCode, ?string $routeName, $driverId): array
    {
        $code = $routeCode ?: 'Route';
        $name = $routeName ?: 'Nairobi Route';

        return [
            'route_id' => $routeId,
            'route_code' => $code,
            'route_name' => $name,
            'route_label' => trim($code . ' ' . $name),
            'driver_id' => $driverId,
        ];
    }

    private function routePersistenceReady(): bool
    {
        return $this->tableExists('routes')
            && $this->tableExists('vehicles')
            && $this->hasColumn('routes', 'route_code')
            && $this->hasColumn('routes', 'route_name')
            && $this->hasColumn('vehicles', 'route_id');
    }

    private function statusTone(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'active', 'pass', 'completed' => 'success',
            'in progress', 'inactive', 'pending' => 'warning',
            'failed', 'expired' => 'danger',
            default => 'neutral',
        };
    }

    private function tableExists(string $table): bool
    {
        try {
            return Schema::connection('supabase')->hasTable($table);
        } catch (\Throwable) {
            return false;
        }
    }

    private function hasColumn(string $table, string $column): bool
    {
        try {
            return Schema::connection('supabase')->hasColumn($table, $column);
        } catch (\Throwable) {
            return false;
        }
    }
}
