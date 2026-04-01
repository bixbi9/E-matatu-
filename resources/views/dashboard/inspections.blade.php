@extends('dashboard.layout')

@section('pageKicker', 'Safety')
@section('pageTitle', 'Inspections')
@section('pageSubtitle', 'Inspection reports are now displayed as real database records with filter controls for vehicle and result.')

@section('content')
    <section class="summary-grid">
        <article class="card">
            <h3>Total Inspection Reports</h3>
            <div class="metric-value">{{ $inspections->count() }}</div>
            <div class="metric-meta">Filtered reports currently visible from the database.</div>
        </article>

        <article class="card">
            <h3>Passed</h3>
            <div class="metric-value">{{ $inspections->where('result', 'Pass')->count() }}</div>
            <div class="metric-meta">Reports with a recorded pass result.</div>
        </article>

        <article class="card">
            <h3>Failed</h3>
            <div class="metric-value">{{ $inspections->where('result', 'Fail')->count() }}</div>
            <div class="metric-meta">Reports that need attention or corrective action.</div>
        </article>
    </section>

    <section class="panel-stack" style="margin-top: 24px;">
        <article class="card">
            <h3>Filter Inspection Reports</h3>
            <p class="metric-meta">Use the controls below to narrow inspection reports by vehicle or result.</p>

            <form method="GET" action="{{ route('inspection') }}">
                <div class="form-grid">
                    <div class="field">
                        <label for="inspection_vehicle_id">Vehicle</label>
                        <select class="select" id="inspection_vehicle_id" name="vehicle_id">
                            <option value="">All vehicles</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->vehicle_id }}" @selected((string) $selectedVehicleId === (string) $vehicle->vehicle_id)>
                                    {{ $vehicle->license_plate ?: 'Vehicle #' . $vehicle->vehicle_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="inspection_result">Result</label>
                        <select class="select" id="inspection_result" name="result">
                            <option value="">All results</option>
                            <option value="Pass" @selected($selectedResult === 'Pass')>Pass</option>
                            <option value="Fail" @selected($selectedResult === 'Fail')>Fail</option>
                        </select>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button primary" type="submit">
                        <i class='bx bx-filter-alt'></i>
                        <span>Apply Filters</span>
                    </button>
                    <a class="button secondary" href="{{ route('inspection') }}">
                        <i class='bx bx-reset'></i>
                        <span>Clear</span>
                    </a>
                </div>
            </form>
        </article>

        @if ($inspections->isNotEmpty())
            <section class="report-grid">
                @foreach ($inspections->take(3) as $inspection)
                    <article class="report-card">
                        <span class="chip {{ $inspection->result === 'Pass' ? 'success' : 'danger' }}">
                            {{ $inspection->result ?: 'Pending' }}
                        </span>
                        <h3 style="margin-top: 14px;">Vehicle {{ $inspection->vehicle_id }}</h3>
                        <p><strong>Inspector:</strong> {{ $inspection->inspector_name ?: 'Not set' }}</p>
                        <p><strong>Date:</strong> {{ $inspection->inspection_date ?: 'Not set' }}</p>
                        <p><strong>Report:</strong> {{ \Illuminate\Support\Str::limit($inspection->comments ?: $inspection->evaluation_form ?: 'No report notes available.', 130) }}</p>
                    </article>
                @endforeach
            </section>
        @endif

        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Inspection Log</h3>
                    <p>Full inspection reports from the database, sorted by latest inspection date first.</p>
                </div>
            </div>

            @if ($inspections->isEmpty())
                <div class="empty-state">No inspection records are available for the selected filters.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Inspector</th>
                                <th>Result</th>
                                <th>Rating</th>
                                <th>Date</th>
                                <th>Report Notes</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($inspections as $inspection)
                                <tr>
                                    <td>{{ $inspection->inspection_id }}</td>
                                    <td>{{ $inspection->vehicle_id }}</td>
                                    <td>{{ $inspection->inspector_name ?: 'Not set' }}</td>
                                    <td>
                                        <span class="chip {{ $inspection->result === 'Pass' ? 'success' : 'danger' }}">
                                            {{ $inspection->result ?: 'Pending' }}
                                        </span>
                                    </td>
                                    <td>{{ $inspection->rating ?: 'Not set' }}</td>
                                    <td>{{ $inspection->inspection_date ?: 'Not set' }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($inspection->comments ?: $inspection->evaluation_form ?: 'No notes recorded.', 90) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </article>
    </section>
@endsection
