@extends('dashboard.layout')

@section('pageKicker', 'People')
@section('pageTitle', 'Crew')
@section('pageSubtitle', 'Assign real routes to crew drivers, connect them to matatus, and review the live driver list from the database.')

@section('content')
    <section class="summary-grid">
        <article class="card">
            <h3>Crew Routing</h3>
            <div class="metric-value">{{ $driverAssignments->where('route_label', '!=', 'Not assigned')->count() }}</div>
            <div class="metric-meta">Drivers already attached to a Nairobi route.</div>
        </article>

        <article class="card">
            <h3>Assigned Vehicles</h3>
            <div class="metric-value">{{ $driverAssignments->where('vehicle_label', '!=', 'Not assigned')->count() }}</div>
            <div class="metric-meta">Crew members already paired to a matatu.</div>
        </article>
    </section>

    <section class="panel-stack" style="margin-top: 24px;">
        <article class="card">
            <h3>Assign Route To Driver</h3>
            <p class="metric-meta">Choose a crew driver, connect them to a route, and optionally pin them to a specific matatu.</p>

            @if (! $routePersistenceReady)
                <div class="flash-card error-card" style="margin-top: 18px; margin-bottom: 0;">
                    <i class='bx bxs-error-circle'></i>
                    <span>Run the latest migration first so crew route assignments can be saved in the database.</span>
                </div>
            @else
                <form method="POST" action="{{ route('crew.assign') }}">
                    @csrf
                    <div class="form-grid">
                        <div class="field">
                            <label for="driver_id">Driver</label>
                            <select class="select" id="driver_id" name="driver_id" required>
                                <option value="">Select driver</option>
                                @foreach ($drivers as $driver)
                                    <option value="{{ $driver->driver_id }}">
                                        {{ trim($driver->first_name . ' ' . $driver->last_name) ?: 'Driver ' . $driver->driver_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="crew_route_id">Route</label>
                            <select class="select" id="crew_route_id" name="route_id" required>
                                <option value="">Select route</option>
                                @foreach ($routes as $route)
                                    @if ($route['route_id'])
                                        <option value="{{ $route['route_id'] }}">{{ $route['route_label'] }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="crew_vehicle_id">Matatu</label>
                            <select class="select" id="crew_vehicle_id" name="vehicle_id">
                                <option value="">Link later</option>
                                @foreach ($vehicles as $vehicle)
                                    <option value="{{ $vehicle->vehicle_id }}">
                                        {{ $vehicle->license_plate ?: 'Vehicle #' . $vehicle->vehicle_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field">
                            <label for="crew_status">Status</label>
                            <select class="select" id="crew_status" name="status">
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button class="button primary" type="submit">
                            <i class='bx bxs-user-check'></i>
                            <span>Save Crew Assignment</span>
                        </button>
                    </div>
                </form>
            @endif
        </article>

        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Crew Directory</h3>
                    <p>The table below is pulled from the database and now includes each driver’s current route and matatu assignment.</p>
                </div>
            </div>

            @if ($driverAssignments->isEmpty())
                <div class="empty-state">No crew members are available yet.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>License</th>
                                <th>Assigned Route</th>
                                <th>Assigned Matatu</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($driverAssignments as $assignment)
                                <tr>
                                    <td>{{ $assignment['driver_id'] }}</td>
                                    <td>{{ $assignment['driver_name'] }}</td>
                                    <td>{{ $assignment['phone_number'] }}</td>
                                    <td>{{ $assignment['license_number'] }}</td>
                                    <td>{{ $assignment['route_label'] }}</td>
                                    <td>{{ $assignment['vehicle_label'] }}</td>
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
