@extends('dashboard.layout')

@section('pageKicker', 'Coverage')
@section('pageTitle', 'Insurance')
@section('pageSubtitle', 'Add insurance policies from the UI and review policy expiry straight from the database records.')

@section('content')
    <section class="summary-grid">
        <article class="card">
            <h3>Total Policies</h3>
            <div class="metric-value">{{ $insurances->count() }}</div>
            <div class="metric-meta">Insurance records currently available for the fleet.</div>
        </article>

        <article class="card">
            <h3>Expiring Within 30 Days</h3>
            <div class="metric-value">{{ $expiringInsurance }}</div>
            <div class="metric-meta">Policies that need renewal attention soon.</div>
        </article>
    </section>

    <section class="panel-stack" style="margin-top: 24px;">
        <article class="card">
            <h3>Add Insurance Policy</h3>
            <p class="metric-meta">Create a new insurance record for a matatu directly from this page.</p>

            <form method="POST" action="{{ route('insurance.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="field">
                        <label for="insurance_vehicle_id">Matatu</label>
                        <select class="select" id="insurance_vehicle_id" name="vehicle_id" required>
                            <option value="">Select matatu</option>
                            @foreach ($vehicles as $vehicle)
                                <option value="{{ $vehicle->vehicle_id }}">
                                    {{ $vehicle->license_plate ?: 'Vehicle #' . $vehicle->vehicle_id }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="policy_number">Policy Number</label>
                        <input class="input" id="policy_number" name="policy_number" type="text" required>
                    </div>

                    <div class="field">
                        <label for="provider">Provider</label>
                        <input class="input" id="provider" name="provider" type="text" required>
                    </div>

                    <div class="field">
                        <label for="start_date">Start Date</label>
                        <input class="input" id="start_date" name="start_date" type="date" required>
                    </div>

                    <div class="field">
                        <label for="expiry_date">Expiry Date</label>
                        <input class="input" id="expiry_date" name="expiry_date" type="date" required>
                    </div>

                    <div class="field" style="grid-column: 1 / -1;">
                        <label for="coverage_details">Coverage Details</label>
                        <textarea class="textarea" id="coverage_details" name="coverage_details" placeholder="Third party, comprehensive cover, limits, and any important notes."></textarea>
                    </div>
                </div>

                <div class="form-actions">
                    <button class="button primary" type="submit">
                        <i class='bx bxs-shield-plus'></i>
                        <span>Add Policy</span>
                    </button>
                </div>
            </form>
        </article>

        <article class="table-card">
            <div class="table-card-header">
                <div>
                    <h3>Policy Register</h3>
                    <p>Insurance records are now shown as live database entries instead of a placeholder section.</p>
                </div>
            </div>

            @if ($insurances->isEmpty())
                <div class="empty-state">No insurance records are available yet.</div>
            @else
                <div class="table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle</th>
                                <th>Provider</th>
                                <th>Policy Number</th>
                                <th>Start</th>
                                <th>Expiry</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($insurances as $insurance)
                                @php
                                    $isExpiring = !empty($insurance->expiry_date) && $insurance->expiry_date <= now()->addDays(30)->toDateString();
                                @endphp
                                <tr>
                                    <td>{{ $insurance->insurance_id }}</td>
                                    <td>{{ $insurance->vehicle_id }}</td>
                                    <td>{{ $insurance->provider ?: 'Not set' }}</td>
                                    <td>{{ $insurance->policy_number ?: 'Not set' }}</td>
                                    <td>{{ $insurance->start_date ?: 'Not set' }}</td>
                                    <td>{{ $insurance->expiry_date ?: 'Not set' }}</td>
                                    <td>
                                        <span class="chip {{ $isExpiring ? 'warning' : 'success' }}">
                                            {{ $isExpiring ? 'Renew soon' : 'Covered' }}
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
