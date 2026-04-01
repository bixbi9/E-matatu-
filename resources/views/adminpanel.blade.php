@extends('dashboard.layout')

@section('pageKicker', 'Operations')
@section('pageTitle', 'Dashboard')
@section('pageSubtitle', 'Monitor the fleet, confirm crew activity, and keep matatu assignments tied to real Nairobi routes from the Digital Matatus map.')

@section('content')
    <section class="metric-grid">
        <article class="card">
            <h3>Total Matatus</h3>
            <div class="metric-value">{{ $totalVehicles }}</div>
            <div class="metric-meta">{{ $activeVehicles }} active and ready for route duty.</div>
        </article>

        <article class="card">
            <h3>Crew Members</h3>
            <div class="metric-value">{{ $totalDrivers }}</div>
            <div class="metric-meta">{{ $activeDrivers }} active drivers and conductors in the system.</div>
        </article>

        <article class="card">
            <h3>Pending Maintenance</h3>
            <div class="metric-value">{{ $pendingMaint }}</div>
            <div class="metric-meta">Units still marked as in progress.</div>
        </article>

        <article class="card">
            <h3>Insurance Closing Soon</h3>
            <div class="metric-value">{{ $expiringInsurance }}</div>
            <div class="metric-meta">Policies expiring within the next 30 days.</div>
        </article>
    </section>

    <section class="content-grid">
        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Recent Inspections</h3>
                    <p>Latest inspection outcomes from the fleet records.</p>
                </div>
                <a class="action-link" href="{{ route('inspection') }}">All inspections</a>
            </div>

            @if ($recentInspections->isEmpty())
                <div class="empty-state">No inspections have been recorded yet.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Vehicle</th>
                                <th>Inspector</th>
                                <th>Result</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentInspections as $inspection)
                                <tr>
                                    <td>{{ $inspection->vehicle_id }}</td>
                                    <td>{{ $inspection->inspector_name }}</td>
                                    <td>
                                        <span class="chip {{ $inspection->result === 'Pass' ? 'success' : 'danger' }}">
                                            {{ $inspection->result }}
                                        </span>
                                    </td>
                                    <td>{{ $inspection->inspection_date }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>

        <article class="highlight-card">
            <h3>Route Source</h3>
            <p class="page-subtitle">Matatu assignments now use real route labels from the public Digital Matatus Nairobi map instead of placeholder names.</p>

            <div class="list-stack">
                @foreach ($routeCatalog->take(4) as $route)
                    <div class="list-item">
                        <div>
                            <strong>{{ $route['route_label'] }}</strong>
                            <div class="muted">{{ config('digital_matatus.source_label') }}</div>
                        </div>
                        <span class="chip neutral">Real route</span>
                    </div>
                @endforeach
            </div>
        </article>
    </section>

    <section class="summary-grid" style="margin-top: 24px;">
        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Matatu Route Assignments</h3>
                    <p>Vehicles matched to real route names from the Digital Matatus reference map.</p>
                </div>
                <a class="action-link" href="{{ route('matatus') }}">Open matatus page</a>
            </div>

            @if ($assignedVehicles->isEmpty())
                <div class="empty-state">Add vehicles to start assigning them to the Nairobi route catalogue.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Matatu</th>
                                <th>Plate</th>
                                <th>Assigned Route</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignedVehicles->take(6) as $assignment)
                                <tr>
                                    <td>{{ $assignment['vehicle_name'] }}</td>
                                    <td>{{ $assignment['license_plate'] }}</td>
                                    <td>{{ $assignment['route_label'] }}</td>
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

        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Crew Snapshot</h3>
                    <p>The latest crew records linked from the dashboard menu.</p>
                </div>
                <a class="action-link" href="{{ route('crew') }}">Open crew page</a>
            </div>

            @if ($recentDrivers->isEmpty())
                <div class="empty-state">No crew members have been added yet.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentDrivers as $driver)
                                <tr>
                                    <td>{{ trim($driver->first_name . ' ' . $driver->last_name) ?: 'Crew Member ' . $driver->driver_id }}</td>
                                    <td>{{ $driver->phone_number ?: 'Not set' }}</td>
                                    <td>
                                        <span class="chip {{ strtolower($driver->status) === 'active' ? 'success' : 'warning' }}">
                                            {{ $driver->status ?: 'Unknown' }}
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
