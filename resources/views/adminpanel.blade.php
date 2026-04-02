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

    {{-- ═══════════════════════════════════════════════════════
         ANIMATED ROUTE ASSIGNMENT CHART
         ═══════════════════════════════════════════════════════ --}}
    <section style="margin-top: 24px;">
        <article class="table-card" style="padding: 0; overflow: hidden;">
            <div class="table-card-header" style="padding: 18px 22px 14px;">
                <div>
                    <h3>Route Assignment Overview</h3>
                    <p>Live driver ↔ route pairing across all Digital Matatus Nairobi routes.</p>
                </div>
                <a class="action-link primary" href="{{ route('routes') }}">
                    <i class='bx bx-map'></i>
                    Open Route Map
                </a>
            </div>

            <div style="padding: 0 22px 22px; display: grid; grid-template-columns: 1fr 220px; gap: 24px; align-items: center;">

                {{-- ── Bar chart ───────────────────────────────── --}}
                <div style="position: relative; overflow: hidden;">
                    <div id="rcBarChart" style="display: grid; gap: 7px;"></div>
                </div>

                {{-- ── Donut + legend ──────────────────────────── --}}
                <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                    <div style="position: relative; width: 140px; height: 140px;">
                        <svg id="rcDonut" viewBox="0 0 120 120" width="140" height="140">
                            <circle id="rcDonutBg"   cx="60" cy="60" r="46" fill="none" stroke="var(--border)"   stroke-width="14"/>
                            <circle id="rcDonutArc"  cx="60" cy="60" r="46" fill="none" stroke="#0f766e"         stroke-width="14"
                                stroke-dasharray="0 289" stroke-linecap="round"
                                transform="rotate(-90 60 60)"
                                style="transition: stroke-dasharray 1.2s cubic-bezier(0.34,1.56,0.64,1);"/>
                            <circle id="rcDonutArc2" cx="60" cy="60" r="46" fill="none" stroke="var(--accent)"   stroke-width="14"
                                stroke-dasharray="0 289" stroke-linecap="round"
                                transform="rotate(-90 60 60)"
                                style="transition: stroke-dasharray 1.2s cubic-bezier(0.34,1.56,0.64,1); transition-delay: 0.1s;"/>
                        </svg>
                        <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;pointer-events:none;">
                            <span id="rcDonutPct" style="font-size:1.55rem;font-weight:800;color:var(--sidebar);line-height:1;">0%</span>
                            <span style="font-size:0.7rem;color:var(--muted);font-weight:600;">assigned</span>
                        </div>
                    </div>

                    <div style="display:grid;gap:8px;width:100%;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;">
                            <span style="width:12px;height:12px;border-radius:3px;background:#0f766e;flex-shrink:0;"></span>
                            <span style="flex:1;">Assigned</span>
                            <strong id="rcLegAssigned">0</strong>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;">
                            <span style="width:12px;height:12px;border-radius:3px;background:var(--accent);flex-shrink:0;"></span>
                            <span style="flex:1;">Unassigned</span>
                            <strong id="rcLegUnassigned">0</strong>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:0.82rem;padding-top:4px;border-top:1px solid var(--border);">
                            <span style="flex:1;color:var(--muted);">Total routes</span>
                            <strong id="rcLegTotal">0</strong>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </section>

    <style>
    /* ── Route chart bar rows ─────────────────────────────── */
    .rc-bar-row {
        display: grid;
        grid-template-columns: 72px 1fr 110px;
        align-items: center;
        gap: 10px;
        font-size: 0.8rem;
    }

    .rc-bar-code {
        font-weight: 700;
        color: var(--text);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .rc-bar-track {
        height: 10px;
        border-radius: 30px;
        background: var(--surface-alt);
        border: 1px solid var(--border);
        overflow: hidden;
        position: relative;
    }

    .rc-bar-fill {
        height: 100%;
        border-radius: 30px;
        width: 0;
        transition: width 1s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
    }

    /* Animated shimmer on assigned bars */
    .rc-bar-fill.assigned::after {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.35) 50%, transparent 100%);
        animation: shimmer 2.2s ease-in-out infinite;
        background-size: 200% 100%;
    }

    @keyframes shimmer {
        0%   { background-position: -200% 0; }
        100% { background-position:  200% 0; }
    }

    .rc-bar-driver {
        color: var(--muted);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.75rem;
    }

    .rc-bar-driver.assigned-driver {
        color: var(--success);
        font-weight: 600;
    }

    /* Moving matatu dot on assigned rows */
    .rc-bus-dot {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid rgba(255,255,255,0.6);
        pointer-events: none;
        animation: busDrive 3s linear infinite;
    }

    @keyframes busDrive {
        0%   { left: -12px; opacity: 0; }
        8%   { opacity: 1; }
        92%  { opacity: 1; }
        100% { left: calc(100% + 4px); opacity: 0; }
    }
    </style>

    <script>
    (function () {
        const RC_DATA   = @json($routeChartData);
        const COLORS    = [
            '#e74c3c','#e67e22','#f39c12','#27ae60','#16a085',
            '#3498db','#8e44ad','#c0392b','#2980b9','#1abc9c',
            '#d4ac0d','#2ecc71','#9b59b6','#f1c40f','#34495e',
            '#e91e63','#00bcd4','#4caf50','#ff5722','#795548',
            '#607d8b','#ff9800'
        ];

        const assigned   = RC_DATA.filter(r => r.assigned).length;
        const unassigned = RC_DATA.length - assigned;
        const pct        = RC_DATA.length ? Math.round((assigned / RC_DATA.length) * 100) : 0;
        const circumf    = 2 * Math.PI * 46; // ≈ 289

        // ── Build bar rows ────────────────────────────────────
        const chart = document.getElementById('rcBarChart');

        RC_DATA.forEach((route, i) => {
            const color    = COLORS[i % COLORS.length];
            const barWidth = route.assigned ? 100 : 22;
            const driverTxt = route.driver_name || 'Unassigned';

            const row = document.createElement('div');
            row.className = 'rc-bar-row';
            row.innerHTML = `
                <span class="rc-bar-code" title="${route.label}">${route.code}</span>
                <div class="rc-bar-track">
                    <div class="rc-bar-fill ${route.assigned ? 'assigned' : ''}"
                         data-w="${barWidth}"
                         style="background:${color};">
                        ${route.assigned ? '<span class="rc-bus-dot"></span>' : ''}
                    </div>
                </div>
                <span class="rc-bar-driver ${route.assigned ? 'assigned-driver' : ''}"
                      title="${driverTxt}">${driverTxt}</span>
            `;
            chart.appendChild(row);
        });

        // ── Animate bars in on load (staggered) ──────────────
        requestAnimationFrame(() => {
            const fills = chart.querySelectorAll('.rc-bar-fill');
            fills.forEach((fill, i) => {
                setTimeout(() => {
                    fill.style.width = fill.dataset.w + '%';
                }, i * 45);
            });
        });

        // ── Animate donut ─────────────────────────────────────
        setTimeout(() => {
            const arc   = document.getElementById('rcDonutArc');
            const arc2  = document.getElementById('rcDonutArc2');
            const pctEl = document.getElementById('rcDonutPct');

            // Arc 1 = assigned (teal)
            const dash1 = (assigned / RC_DATA.length) * circumf;
            arc.style.strokeDasharray  = `${dash1} ${circumf}`;

            // Arc 2 = unassigned (gold), offset after arc1
            const dash2 = (unassigned / RC_DATA.length) * circumf;
            arc2.setAttribute('stroke-dashoffset', -dash1);
            arc2.style.strokeDasharray = `${dash2} ${circumf}`;

            // Count-up percentage
            let count = 0;
            const step = pct / 40;
            const interval = setInterval(() => {
                count = Math.min(count + step, pct);
                pctEl.textContent = Math.round(count) + '%';
                if (count >= pct) clearInterval(interval);
            }, 30);
        }, 300);

        // ── Legend numbers ────────────────────────────────────
        document.getElementById('rcLegAssigned').textContent   = assigned;
        document.getElementById('rcLegUnassigned').textContent = unassigned;
        document.getElementById('rcLegTotal').textContent      = RC_DATA.length;
    })();
    </script>

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
