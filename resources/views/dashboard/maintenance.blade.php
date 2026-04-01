@extends('dashboard.layout')

@section('pageKicker', 'Service')
@section('pageTitle', 'Maintenance')
@section('pageSubtitle', 'Maintenance now opens as a separate page from the dashboard menu so service work is easier to review.')

@section('content')
    <section class="metric-grid">
        <article class="card">
            <h3>Total Records</h3>
            <div class="metric-value">{{ $maintenances->count() }}</div>
            <div class="metric-meta">Maintenance jobs currently tracked in the database.</div>
        </article>

        <article class="card">
            <h3>In Progress</h3>
            <div class="metric-value">{{ $maintenances->where('status', 'In Progress')->count() }}</div>
            <div class="metric-meta">Jobs that still need follow-up.</div>
        </article>
    </section>

    <section class="table-card">
        <div class="table-card-header">
            <div>
                <h3>Maintenance Log</h3>
                <p>Fleet service records from the maintenance table.</p>
            </div>
        </div>

        @if ($maintenances->isEmpty())
            <div class="empty-state">No maintenance records are available yet.</div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vehicle</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Cost</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($maintenances as $maintenance)
                            <tr>
                                <td>{{ $maintenance->maintenance_id }}</td>
                                <td>{{ $maintenance->vehicle_id }}</td>
                                <td>{{ $maintenance->date ?: 'Not set' }}</td>
                                <td>{{ $maintenance->maintenance_type ?: 'Not set' }}</td>
                                <td>{{ $maintenance->cost !== null ? number_format((float) $maintenance->cost, 2) : 'Not set' }}</td>
                                <td>
                                    <span class="chip {{ strtolower($maintenance->status) === 'in progress' ? 'warning' : 'success' }}">
                                        {{ $maintenance->status ?: 'Unknown' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
@endsection
