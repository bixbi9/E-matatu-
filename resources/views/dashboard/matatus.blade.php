@extends('dashboard.layout')

@section('pageKicker', 'Fleet')
@section('pageTitle', 'Matatus')
@section('pageSubtitle', 'Assign routes and drivers from the database, while keeping the route catalogue anchored to the Digital Matatus Nairobi map.')

@section('content')
    <section class="summary-grid">
        <article class="highlight-card">
            <h3>Route Assignment Desk</h3>
            <p class="page-subtitle">This page now saves route and driver assignments for each matatu instead of only showing a visual mockup.</p>
            <div class="meta-row">
                <span class="meta-pill"><i class='bx bxs-bus'></i>{{ $vehicles->count() }} vehicles</span>
                <span class="meta-pill"><i class='bx bxs-map'></i>{{ $routes->count() }} routes</span>
                <span class="meta-pill"><i class='bx bxs-user-badge'></i>{{ $drivers->count() }} drivers</span>
            </div>
        </article>

        <article class="card">
            <h3>Traffic Lights Status</h3>
            <div class="traffic-lights topbar-lights" style="margin-top: 18px;">
                <span class="traffic-dot stop"></span>
                <span class="traffic-dot wait"></span>
                <span class="traffic-dot go"></span>
            </div>
            <p class="metric-meta">The old dashboard traffic lights are now part of the new login-inspired theme so the pages still feel like the original system.</p>
        </article>
    </section>

    <section class="panel-stack" style="margin-top: 24px;">
        <article class="card">
            <h3>Assign Route To Matatu</h3>
            <p class="metric-meta">Choose a vehicle, pick a Nairobi route, and optionally link a driver in one save action.</p>

            @if (! $routePersistenceReady)
                <div class="flash-card error-card" style="margin-top: 18px; margin-bottom: 0;">
                    <i class='bx bxs-error-circle'></i>
                    <span>Run the latest migration first so route assignments can be stored in the database.</span>
                </div>
            @else
                <form method="POST" action="{{ route('matatus.assign') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label for="vehicle_id">Matatu</label>
                            <select class="select" id="vehicle_id" name="vehicle_id" required>
                                <option value="">Select matatu</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->vehicle_id }}">
                                        {{ $vehicle->license_plate ?: 'Vehicle #' . $vehicle->vehicle_id }} {{ $vehicle->vin ? ' - ' . $vehicle->vin : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="route_id">Route</label>
                            <select class="select" id="route_id" name="route_id" required>
                                <option value="">Select route</option>
                                @foreach ($routes as $route)
                                    @if ($route['route_id'])
                                        <option value="{{ $route['route_id'] }}">{{ $route['route_label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="current_driver_id">Driver</label>
                            <select class="select" id="current_driver_id" name="current_driver_id">
                                <option value="">Assign later</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->driver_id }}">
                                        {{ trim($driver->first_name . ' ' . $driver->last_name) ?: 'Driver ' . $driver->driver_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="status">Status</label>
                            <select class="select" id="status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="In Progress">In Progress</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="button primary" type="submit">
                            <i class='bx bxs-save'></i>
                            <span>Save Assignment</span>
                        </button>
                    </div>
                </form>
            @endif
        </article>

        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Database Assignments</h3>
                    <p>These assignments are pulled from the database and show the currently linked route and driver for each matatu.</p>
                </div>
            </div>

            @if ($assignedVehicles->isEmpty())
                <div class="empty-state">No vehicles are available yet.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Matatu</th>
                                <th>Plate</th>
                                <th>Driver</th>
                                <th>Assigned Route</th>
                                <th>Inspection Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignedVehicles as $assignment)
                                <tr>
                                    <td>{{ $assignment['vehicle_name'] }}</td>
                                    <td>{{ $assignment['license_plate'] }}</td>
                                    <td>{{ $assignment['driver_name'] }}</td>
                                    <td>{{ $assignment['route_label'] }}</td>
                                    <td>{{ $assignment['inspection_date'] }}</td>
                                    <td>
                                        <span class="chip {{ $assignment['status_tone'] }}">
                                            {{ $assignment['status'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
    </section>
@endsection
