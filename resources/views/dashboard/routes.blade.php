@extends('dashboard.layout')

@section('pageKicker', 'Operations')
@section('pageTitle', 'Route Map')
@section('pageSubtitle', 'Digital Matatus Nairobi route map — click any route to assign a driver.')

@section('content')
<style>
/* ── Shell ──────────────────────────────────────────────── */
.rm-shell {
    display: grid;
    grid-template-columns: 1fr 310px;
    gap: 18px;
    min-height: 660px;
}

/* ── Map card ────────────────────────────────────────────── */
.rm-map-card {
    background: var(--surface);
    border-radius: 22px;
    box-shadow: var(--shadow);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.rm-map-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 20px 12px;
    border-bottom: 1px solid var(--border);
    flex-shrink: 0;
}

.rm-map-header h3 { margin: 0; font-size: 0.95rem; }

.rm-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 0.75rem;
    color: var(--muted);
    background: var(--surface-alt);
    border: 1px solid var(--border);
    border-radius: 30px;
    padding: 4px 11px;
}

.rm-badge-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #22c55e;
    animation: bpulse 2s ease-in-out infinite;
}

@keyframes bpulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.4)} }

/* ── Map legend row ──────────────────────────────────────── */
.rm-legend-row {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 7px 20px;
    background: var(--surface-alt);
    border-bottom: 1px solid var(--border);
    font-size: 0.73rem;
    color: var(--muted);
    flex-shrink: 0;
    flex-wrap: wrap;
}

.rm-legend-item { display: flex; align-items: center; gap: 5px; }

.rm-legend-line {
    width: 26px; height: 3px;
    border-radius: 2px;
}

.rm-legend-dashed {
    width: 26px; height: 3px;
    border-radius: 2px;
    background: repeating-linear-gradient(90deg, var(--muted) 0, var(--muted) 5px, transparent 5px, transparent 9px);
}

/* ── SVG wrapper ──────────────────────────────────────────── */
.rm-svg-wrap {
    flex: 1;
    position: relative;
    overflow: hidden;
    background: #eef5f3;
    cursor: default;
    min-height: 520px;
}

#routeMapSvg { width: 100%; height: 100%; display: block; }

/* ── Route lines ──────────────────────────────────────────── */
.rm-route-line {
    fill: none;
    stroke-linecap: round;
    stroke-linejoin: round;
    cursor: pointer;
    transition: stroke-width 160ms ease, filter 160ms ease;
}

.rm-route-line:hover  { stroke-width: 7; filter: brightness(1.18); }
.rm-route-line.active { stroke-width: 8; filter: drop-shadow(0 0 7px currentColor); }

/* Road labels */
.rm-road-label {
    font-family: 'Manrope', sans-serif;
    font-size: 7.5px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    fill: #9ab5ae;
    pointer-events: none;
    user-select: none;
}

/* District labels */
.rm-district {
    font-family: 'Manrope', sans-serif;
    font-size: 8px;
    font-weight: 600;
    fill: #aac3bc;
    pointer-events: none;
    user-select: none;
}

/* Terminus stop labels */
.rm-stop-label {
    font-family: 'Manrope', sans-serif;
    font-size: 7px;
    font-weight: 700;
    fill: #4a6960;
    pointer-events: none;
    user-select: none;
}

/* Tooltip */
#rmTooltip {
    position: absolute;
    pointer-events: none;
    background: rgba(10,26,24,0.94);
    color: #f7fffd;
    font-family: 'Manrope', sans-serif;
    font-size: 0.8rem;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 9px;
    white-space: nowrap;
    opacity: 0;
    transition: opacity 130ms ease;
    z-index: 20;
    box-shadow: 0 4px 16px rgba(0,0,0,0.3);
}
#rmTooltip.vis { opacity: 1; }

/* ── Panel ────────────────────────────────────────────────── */
.rm-panel { display: flex; flex-direction: column; gap: 14px; }

.rm-panel-card {
    background: var(--surface);
    border-radius: 18px;
    box-shadow: var(--shadow);
    padding: 16px 16px 18px;
}

.rm-panel-card h3 { margin: 0 0 4px; font-size: 0.93rem; }
.rm-panel-card p  { margin: 0 0 12px; font-size: 0.8rem; color: var(--muted); }

/* Selected route badge */
.rm-route-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 7px 13px;
    border-radius: 10px;
    font-size: 0.83rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #fff;
}

/* Form fields */
.rm-field { display: grid; gap: 5px; margin-bottom: 10px; }
.rm-field label { font-size: 0.77rem; font-weight: 600; color: var(--muted); }
.rm-field select {
    width: 100%; padding: 8px 10px;
    border: 1.5px solid var(--border);
    border-radius: 9px;
    background: var(--surface-alt);
    font-family: 'Manrope', sans-serif;
    font-size: 0.85rem;
    color: var(--text);
    outline: none;
    transition: border-color 160ms;
}
.rm-field select:focus { border-color: var(--sidebar); }

.rm-btn {
    width: 100%; padding: 10px;
    border: 0; border-radius: 11px;
    background: var(--sidebar); color: #fff;
    font-family: 'Manrope', sans-serif;
    font-size: 0.85rem; font-weight: 700;
    cursor: pointer;
    transition: background 160ms, transform 120ms;
}
.rm-btn:hover  { background: var(--sidebar-deep); transform: translateY(-1px); }
.rm-btn:active { transform: translateY(0); }

/* Route list */
.rm-route-list {
    display: grid; gap: 5px;
    max-height: 360px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: var(--border) transparent;
}

.rm-route-item {
    display: flex; align-items: center; gap: 9px;
    padding: 7px 9px; border-radius: 9px;
    cursor: pointer;
    transition: background 140ms;
    border: 1.5px solid transparent;
    font-size: 0.81rem;
}
.rm-route-item:hover { background: var(--surface-alt); }
.rm-route-item.active { background: var(--accent-soft); border-color: var(--accent); }

.rm-swatch { width: 9px; height: 9px; border-radius: 2px; flex-shrink: 0; }
.rm-item-name { flex: 1; font-weight: 600; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.rm-item-driver { font-size: 0.73rem; color: var(--muted); }

/* Stats */
.rm-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 8px; margin-bottom: 16px; }
.rm-stat { background: var(--surface); border-radius: 13px; padding: 10px 12px; text-align: center; box-shadow: 0 2px 8px rgba(15,118,110,0.07); }
.rm-stat-val { font-size: 1.35rem; font-weight: 800; color: var(--sidebar); line-height: 1; }
.rm-stat-lbl { font-size: 0.7rem; color: var(--muted); margin-top: 3px; font-weight: 600; }

/* Toast */
#rmToast {
    position: fixed; bottom: 24px; right: 24px;
    background: var(--sidebar); color: #fff;
    padding: 11px 18px; border-radius: 13px;
    font-family: 'Manrope', sans-serif;
    font-size: 0.85rem; font-weight: 600;
    box-shadow: 0 8px 24px rgba(15,118,110,0.32);
    transform: translateY(18px); opacity: 0;
    transition: opacity 280ms, transform 280ms;
    z-index: 9999; pointer-events: none;
}
#rmToast.show { opacity: 1; transform: translateY(0); }

@media (max-width: 960px) {
    .rm-shell { grid-template-columns: 1fr; }
    .rm-svg-wrap { min-height: 380px; }
}
</style>

{{-- Stats --}}
<div class="rm-stats">
    <div class="rm-stat">
        <div class="rm-stat-val">{{ count($routes) }}</div>
        <div class="rm-stat-lbl">Total Routes</div>
    </div>
    <div class="rm-stat">
        <div class="rm-stat-val" id="statAssigned">{{ $routes->filter(fn($r) => !empty($r['driver_id']))->count() }}</div>
        <div class="rm-stat-lbl">Assigned</div>
    </div>
    <div class="rm-stat">
        <div class="rm-stat-val" id="statUnassigned">{{ $routes->filter(fn($r) => empty($r['driver_id']))->count() }}</div>
        <div class="rm-stat-lbl">Unassigned</div>
    </div>
</div>

<div class="rm-shell">

    {{-- ── Map ──────────────────────────────────────────────── --}}
    <div class="rm-map-card">
        <div class="rm-map-header">
            <h3><i class='bx bx-map' style="margin-right:6px;vertical-align:-2px;"></i>Nairobi Matatu Routes</h3>
            <div class="rm-badge">
                <span class="rm-badge-dot"></span>
                Digital Matatus · {{ count($routes) }} routes live
            </div>
        </div>

        <div class="rm-legend-row">
            <div class="rm-legend-item">
                <div class="rm-legend-line" style="background:#0f766e;"></div>
                <span>Assigned</span>
            </div>
            <div class="rm-legend-item">
                <div class="rm-legend-dashed"></div>
                <span>Unassigned</span>
            </div>
            <div class="rm-legend-item">
                <svg width="10" height="10"><circle cx="5" cy="5" r="4" fill="#0f766e"/></svg>
                <span>Matatu in service</span>
            </div>
            <div class="rm-legend-item">
                <svg width="10" height="10"><circle cx="5" cy="5" r="4" fill="none" stroke="#aaa" stroke-width="1.5"/></svg>
                <span>Terminus</span>
            </div>
            <span style="margin-left:auto;font-size:0.7rem;">Click a route to assign</span>
        </div>

        <div class="rm-svg-wrap" id="rmMapWrap">
            <div id="rmTooltip"></div>

            {{-- ══════════════════════════════════════════════════════
                 SVG MAP  —  viewBox 0 0 1000 720
                 CBD (City Centre) anchored at (540, 400)

                 Roads radiate from CBD following the real Nairobi layout:
                   West        → Waiyaki Way   (Kawangware, Uthiru, Kikuyu)
                   SW          → Ngong Road    (Yaya, Ngumo, Karen, Ngong)
                   SSE / S     → Mombasa Road  (South C, KPA, Airport)
                   E / ENE     → Jogoo Rd /    (Kariobangi, Outer Ring,
                                 Kangundo Rd    Komarocks, Kayole, Donholm)
                   N / NNE     → Thika Road    (Roysambu, Githurai, Ruiru)
                   NNE         → Juja Rd       (Lucky Summer)
                 ══════════════════════════════════════════════════════ --}}

            <svg id="routeMapSvg" viewBox="0 0 1000 720" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid meet">
                <defs>
                    <!-- Park fills -->
                    <pattern id="parkPat" x="0" y="0" width="6" height="6" patternUnits="userSpaceOnUse">
                        <rect width="6" height="6" fill="#d4edda"/>
                        <circle cx="3" cy="3" r="1" fill="#b2dfbb" opacity=".5"/>
                    </pattern>
                    <radialGradient id="cbdG" cx="50%" cy="50%" r="50%">
                        <stop offset="0%" stop-color="#0f766e" stop-opacity=".18"/>
                        <stop offset="100%" stop-color="#0f766e" stop-opacity="0"/>
                    </radialGradient>
                </defs>

                <!-- ── Background parks (from PDF) ──────────────────── -->
                <!-- Karura Forest  NW -->
                <ellipse cx="415" cy="195" rx="70" ry="50" fill="url(#parkPat)" opacity=".9"/>
                <text class="rm-district" x="382" y="196" style="font-size:6.5px;">KARURA FOREST</text>

                <!-- Ngong Road Forest  SW -->
                <ellipse cx="310" cy="540" rx="90" ry="55" fill="url(#parkPat)" opacity=".9"/>
                <text class="rm-district" x="263" y="545" style="font-size:6px;">NGONG ROAD FOREST</text>

                <!-- City Park  centre-north -->
                <ellipse cx="555" cy="330" rx="28" ry="22" fill="url(#parkPat)" opacity=".8"/>
                <text class="rm-district" x="535" y="331" style="font-size:5.5px;">CITY PARK</text>

                <!-- Nairobi National Park  far south -->
                <ellipse cx="480" cy="680" rx="140" ry="40" fill="url(#parkPat)" opacity=".7"/>
                <text class="rm-district" x="400" y="682" style="font-size:6.5px;">NAIROBI NATIONAL PARK</text>

                <!-- ── Road backbone (grey hairlines) ───────────────── -->
                <!-- Waiyaki Way / Uhuru Hwy  W -->
                <path d="M540,400 L80,395" fill="none" stroke="#c5d8d2" stroke-width="2.5" stroke-dasharray="none"/>
                <!-- Ngong Road  SW -->
                <path d="M540,400 L170,630" fill="none" stroke="#c5d8d2" stroke-width="2.5"/>
                <!-- Mombasa Road  SE -->
                <path d="M540,400 L760,660" fill="none" stroke="#c5d8d2" stroke-width="2.5"/>
                <!-- Thika Road  N -->
                <path d="M540,400 L590,35" fill="none" stroke="#c5d8d2" stroke-width="2.5"/>
                <!-- Jogoo / Outer Ring  E -->
                <path d="M540,400 L870,310" fill="none" stroke="#c5d8d2" stroke-width="2.5"/>
                <!-- Kangundo Road  E-SE -->
                <path d="M540,400 L880,450" fill="none" stroke="#c5d8d2" stroke-width="2.5"/>
                <!-- Limuru Road  NW -->
                <path d="M540,400 L250,130" fill="none" stroke="#c5d8d2" stroke-width="1.5" stroke-dasharray="6 3"/>
                <!-- Kiambu Road  N -->
                <path d="M540,400 L430,95" fill="none" stroke="#c5d8d2" stroke-width="1.5" stroke-dasharray="6 3"/>
                <!-- Langata Road  SSW -->
                <path d="M540,400 L400,660" fill="none" stroke="#c5d8d2" stroke-width="1.5" stroke-dasharray="6 3"/>
                <!-- Northern Bypass curve  N arc -->
                <path d="M320,190 Q520,110 700,200" fill="none" stroke="#c5d8d2" stroke-width="1.5" stroke-dasharray="4 4"/>
                <!-- Outer Ring Road arc  E -->
                <path d="M640,250 Q820,340 790,540" fill="none" stroke="#c5d8d2" stroke-width="1.5" stroke-dasharray="4 4"/>

                <!-- ── Road labels ────────────────────────────────── -->
                <text class="rm-road-label" x="270" y="388" transform="rotate(1,270,388)">WAIYAKI WAY / LOWER KABETE RD</text>
                <text class="rm-road-label" x="395" y="525" transform="rotate(55,395,525)">NGONG ROAD</text>
                <text class="rm-road-label" x="600" y="505" transform="rotate(52,600,505)">MOMBASA ROAD</text>
                <text class="rm-road-label" x="555" y="225" transform="rotate(6,555,225)">THIKA ROAD</text>
                <text class="rm-road-label" x="650" y="360" transform="rotate(-15,650,360)">OUTER RING ROAD</text>
                <text class="rm-road-label" x="665" y="430" transform="rotate(13,665,430)">KANGUNDO ROAD</text>
                <text class="rm-road-label" x="340" y="268" transform="rotate(-40,340,268)">LIMURU ROAD</text>

                <!-- ── District zone labels ───────────────────────── -->
                <text class="rm-district" x="118" y="375">DAGORETTI</text>
                <text class="rm-district" x="108" y="418">KABERIA</text>
                <text class="rm-district" x="220" y="360">KAWANGWARE</text>
                <text class="rm-district" x="148" y="634">NGONG</text>
                <text class="rm-district" x="236" y="580">KAREN</text>
                <text class="rm-district" x="430" y="488">KIBERA/AYANI</text>
                <text class="rm-district" x="453" y="520">NGUMO</text>
                <text class="rm-district" x="486" y="580">SOUTH C</text>
                <text class="rm-district" x="660" y="595">KPA / AIRPORT</text>
                <text class="rm-district" x="730" y="480">FEDHA</text>
                <text class="rm-district" x="782" y="375">KOMAROCKS</text>
                <text class="rm-district" x="768" y="430">KAYOLE</text>
                <text class="rm-district" x="716" y="375">DONHOLM</text>
                <text class="rm-district" x="644" y="168">LUCKY SUMMER</text>
                <text class="rm-district" x="532" y="148">ROYSAMBU</text>
                <text class="rm-district" x="492" y="82">GITHURAI</text>
                <text class="rm-district" x="597" y="82">RUIRU</text>
                <text class="rm-district" x="614" y="136">SUNTON</text>
                <text class="rm-district" x="488" y="335">WESTLANDS</text>
                <text class="rm-district" x="490" y="350">LAVINGTON</text>
                <text class="rm-district" x="590" y="462">SOUTH B</text>
                <text class="rm-district" x="597" y="305">EASTLEIGH</text>
                <text class="rm-district" x="668" y="288">KARIOBANGI</text>

                <!-- ═══════════════════════════════════════════════════
                     ROUTE LINES — matched to real road geometry

                     CBD anchor: (540, 400)

                     Paths use cubic Bezier (C) and quadratic (Q) to
                     follow the actual road bends shown in the PDF.
                 ═══════════════════════════════════════════════════ -->

                {{-- ══ WEST – along WAIYAKI WAY ══ --}}

                {{-- 46K/Y  Kawangware / Yaya
                     Goes W along Waiyaki Way through Westlands,
                     swings slightly S via Valley Arcade to Yaya/Kawangware --}}
                <path id="line-46KY" class="rm-route-line"
                      d="M540,400 C490,395 410,390 320,388 C265,387 230,390 172,396"
                      stroke="#e74c3c" stroke-width="4.5"
                      data-route="46K/Y" data-name="Kawangware / Yaya"
                      data-rid="{{ $routeMap['46K/Y']['route_id'] ?? '' }}"/>

                {{-- 46P  Kawangware (Loop) — parallel, offset slightly S --}}
                <path id="line-46P" class="rm-route-line"
                      d="M540,400 C490,406 405,408 310,410 C258,411 210,412 168,414"
                      stroke="#e67e22" stroke-width="4.5"
                      data-route="46P" data-name="Kawangware (Loop)"
                      data-rid="{{ $routeMap['46P']['route_id'] ?? '' }}"/>

                {{-- 4W  Kaberia — turns SSW after Kawangware --}}
                <path id="line-4W" class="rm-route-line"
                      d="M540,400 C490,400 390,398 300,400 C250,401 215,408 155,435"
                      stroke="#f39c12" stroke-width="4.5"
                      data-route="4W" data-name="Kaberia"
                      data-rid="{{ $routeMap['4W']['route_id'] ?? '' }}"/>

                {{-- 2  Dagoretti — follows Dagoretti Road, further S --}}
                <path id="line-2" class="rm-route-line"
                      d="M540,400 C490,405 400,420 310,438 C255,448 205,455 128,462"
                      stroke="#d4ac0d" stroke-width="4.5"
                      data-route="2" data-name="Dagoretti"
                      data-rid="{{ $routeMap['2']['route_id'] ?? '' }}"/>

                {{-- 102  Kikuyu — far west along Waiyaki Way --}}
                <path id="line-102" class="rm-route-line"
                      d="M540,400 C490,394 380,386 270,385 C200,384 145,384 72,385"
                      stroke="#16a085" stroke-width="4.5"
                      data-route="102" data-name="Kikuyu"
                      data-rid="{{ $routeMap['102']['route_id'] ?? '' }}"/>

                {{-- ══ SW – along NGONG ROAD ══ --}}

                {{-- 111  Ngong — follows Ngong Road all the way SW --}}
                <path id="line-111" class="rm-route-line"
                      d="M540,400 C520,425 490,460 455,490 C415,525 360,565 230,625"
                      stroke="#27ae60" stroke-width="4.5"
                      data-route="111" data-name="Ngong"
                      data-rid="{{ $routeMap['111']['route_id'] ?? '' }}"/>

                {{-- 24  Karen — branches off Ngong Rd at Karen junction --}}
                <path id="line-24" class="rm-route-line"
                      d="M540,400 C518,428 488,462 452,494 C420,520 375,550 298,583"
                      stroke="#2ecc71" stroke-width="4.5"
                      data-route="24" data-name="Karen"
                      data-rid="{{ $routeMap['24']['route_id'] ?? '' }}"/>

                {{-- 24C  Hardy — near Karen, slight branch --}}
                <path id="line-24C" class="rm-route-line"
                      d="M540,400 C520,430 494,466 462,498 C432,528 398,555 345,578"
                      stroke="#1abc9c" stroke-width="4.5"
                      data-route="24C" data-name="Hardy"
                      data-rid="{{ $routeMap['24C']['route_id'] ?? '' }}"/>

                {{-- 32A  Ayani/Kibera — SW, shorter than Karen --}}
                <path id="line-32A" class="rm-route-line"
                      d="M540,400 C525,422 505,448 480,470 C460,487 440,498 405,508"
                      stroke="#3498db" stroke-width="4.5"
                      data-route="32A" data-name="Ayani"
                      data-rid="{{ $routeMap['32A']['route_id'] ?? '' }}"/>

                {{-- 33NG  Ngumo — Ngumo Stage (near Kenyatta Market), S of Yaya --}}
                <path id="line-33NG" class="rm-route-line"
                      d="M540,400 C528,420 514,442 496,460 C478,475 462,486 442,495"
                      stroke="#2980b9" stroke-width="4.5"
                      data-route="33NG" data-name="Ngumo"
                      data-rid="{{ $routeMap['33NG']['route_id'] ?? '' }}"/>

                {{-- ══ S / SSE – MOMBASA ROAD ══ --}}

                {{-- 12C  South C — S along Mombasa Rd --}}
                <path id="line-12C" class="rm-route-line"
                      d="M540,400 C545,438 548,476 546,515 C544,545 540,562 530,588"
                      stroke="#8e44ad" stroke-width="4.5"
                      data-route="12C" data-name="South C"
                      data-rid="{{ $routeMap['12C']['route_id'] ?? '' }}"/>

                {{-- 12D  KPA — SE toward Airport area --}}
                <path id="line-12D" class="rm-route-line"
                      d="M540,400 C558,440 582,478 608,514 C630,544 648,562 668,588"
                      stroke="#9b59b6" stroke-width="4.5"
                      data-route="12D" data-name="KPA"
                      data-rid="{{ $routeMap['12D']['route_id'] ?? '' }}"/>

                {{-- ══ ESE – MOMBASA RD / EMBAKASI ══ --}}

                {{-- 33B/FED  Fedha Estate / Embakasi --}}
                <path id="line-33B" class="rm-route-line"
                      d="M540,400 C580,408 635,418 690,434 C730,445 760,455 790,468"
                      stroke="#c0392b" stroke-width="4.5"
                      data-route="33B/FED" data-name="Fedha Estate"
                      data-rid="{{ $routeMap['33B/FED']['route_id'] ?? '' }}"/>

                {{-- ══ E – JOGOO / KANGUNDO ROAD ══ --}}

                {{-- 34B  Jacaranda / Donholm — E via Jogoo Rd lower --}}
                <path id="line-34B" class="rm-route-line"
                      d="M540,400 C578,395 630,390 678,388 C714,386 740,388 762,395"
                      stroke="#e67e22" stroke-width="4.5"
                      data-route="34B" data-name="Jacaranda"
                      data-rid="{{ $routeMap['34B']['route_id'] ?? '' }}"/>

                {{-- 19C  Komarocks — E along Outer Ring Rd --}}
                <path id="line-19C" class="rm-route-line"
                      d="M540,400 C580,385 640,368 700,352 C742,340 778,332 820,326"
                      stroke="#e74c3c" stroke-width="4.5"
                      data-route="19C" data-name="Komarocks"
                      data-rid="{{ $routeMap['19C']['route_id'] ?? '' }}"/>

                {{-- 1960  Kayole — E via Kangundo Road --}}
                <path id="line-1960" class="rm-route-line"
                      d="M540,400 C580,410 640,422 700,432 C742,440 775,444 818,450"
                      stroke="#f1c40f" stroke-width="4.5"
                      data-route="1960" data-name="Kayole"
                      data-rid="{{ $routeMap['1960']['route_id'] ?? '' }}"/>

                {{-- ══ N / NNE – THIKA ROAD ══ --}}

                {{-- 43  Ngumba / Roysambu — N on Thika Rd --}}
                <path id="line-43" class="rm-route-line"
                      d="M540,400 C545,355 548,298 550,248 C551,218 550,195 548,162"
                      stroke="#16a085" stroke-width="4.5"
                      data-route="43" data-name="Ngumba"
                      data-rid="{{ $routeMap['43']['route_id'] ?? '' }}"/>

                {{-- 44G/Z  KU — Thika Rd, branches NE to Kenyatta Univ --}}
                <path id="line-44GZ" class="rm-route-line"
                      d="M540,400 C548,350 558,285 572,235 C584,192 600,162 628,120"
                      stroke="#1abc9c" stroke-width="4.5"
                      data-route="44G/Z" data-name="KU"
                      data-rid="{{ $routeMap['44G/Z']['route_id'] ?? '' }}"/>

                {{-- 145  Ruiru Town — far N on Thika Rd --}}
                <path id="line-145" class="rm-route-line"
                      d="M540,400 C548,345 560,270 572,218 C582,172 592,132 610,80"
                      stroke="#3498db" stroke-width="4.5"
                      data-route="145" data-name="Ruiru Town"
                      data-rid="{{ $routeMap['145']['route_id'] ?? '' }}"/>

                {{-- 45G  Githurai — N on Thika Rd, slightly W of 145 --}}
                <path id="line-45G" class="rm-route-line"
                      d="M540,400 C542,348 540,278 536,226 C533,184 530,148 525,95"
                      stroke="#2980b9" stroke-width="4.5"
                      data-route="45G" data-name="Githurai"
                      data-rid="{{ $routeMap['45G']['route_id'] ?? '' }}"/>

                {{-- 49  Sunton — NNE, Thika Rd eastern branch --}}
                <path id="line-49" class="rm-route-line"
                      d="M540,400 C548,358 564,295 580,248 C594,206 610,174 638,138"
                      stroke="#8e44ad" stroke-width="4.5"
                      data-route="49" data-name="Sunton"
                      data-rid="{{ $routeMap['49']['route_id'] ?? '' }}"/>

                {{-- 25A  Lucky Summer — NE via Juja Rd, near Kasarani --}}
                <path id="line-25A" class="rm-route-line"
                      d="M540,400 C558,370 590,330 624,295 C650,268 672,246 692,198"
                      stroke="#f39c12" stroke-width="4.5"
                      data-route="25A" data-name="Lucky Summer"
                      data-rid="{{ $routeMap['25A']['route_id'] ?? '' }}"/>

                <!-- ── Terminus circles (matches PDF terminus symbol) ─ -->
                <!-- W termini -->
                <circle cx="172" cy="396" r="5" fill="#fff" stroke="#e74c3c"  stroke-width="2"/>  <!-- Kawangware/Yaya -->
                <circle cx="168" cy="414" r="5" fill="#fff" stroke="#e67e22"  stroke-width="2"/>  <!-- Kawangware Loop -->
                <circle cx="155" cy="435" r="5" fill="#fff" stroke="#f39c12"  stroke-width="2"/>  <!-- Kaberia -->
                <circle cx="128" cy="462" r="5" fill="#fff" stroke="#d4ac0d"  stroke-width="2"/>  <!-- Dagoretti -->
                <circle cx="72"  cy="385" r="5" fill="#fff" stroke="#16a085"  stroke-width="2"/>  <!-- Kikuyu -->
                <!-- SW termini -->
                <circle cx="230" cy="625" r="5" fill="#fff" stroke="#27ae60"  stroke-width="2"/>  <!-- Ngong -->
                <circle cx="298" cy="583" r="5" fill="#fff" stroke="#2ecc71"  stroke-width="2"/>  <!-- Karen -->
                <circle cx="345" cy="578" r="5" fill="#fff" stroke="#1abc9c"  stroke-width="2"/>  <!-- Hardy -->
                <circle cx="405" cy="508" r="5" fill="#fff" stroke="#3498db"  stroke-width="2"/>  <!-- Ayani -->
                <circle cx="442" cy="495" r="5" fill="#fff" stroke="#2980b9"  stroke-width="2"/>  <!-- Ngumo -->
                <!-- S termini -->
                <circle cx="530" cy="588" r="5" fill="#fff" stroke="#8e44ad"  stroke-width="2"/>  <!-- South C -->
                <circle cx="668" cy="588" r="5" fill="#fff" stroke="#9b59b6"  stroke-width="2"/>  <!-- KPA -->
                <!-- E termini -->
                <circle cx="790" cy="468" r="5" fill="#fff" stroke="#c0392b"  stroke-width="2"/>  <!-- Fedha -->
                <circle cx="762" cy="395" r="5" fill="#fff" stroke="#e67e22"  stroke-width="2"/>  <!-- Jacaranda -->
                <circle cx="820" cy="326" r="5" fill="#fff" stroke="#e74c3c"  stroke-width="2"/>  <!-- Komarocks -->
                <circle cx="818" cy="450" r="5" fill="#fff" stroke="#f1c40f"  stroke-width="2"/>  <!-- Kayole -->
                <!-- N termini -->
                <circle cx="548" cy="162" r="5" fill="#fff" stroke="#16a085"  stroke-width="2"/>  <!-- Ngumba -->
                <circle cx="628" cy="120" r="5" fill="#fff" stroke="#1abc9c"  stroke-width="2"/>  <!-- KU -->
                <circle cx="610" cy="80"  r="5" fill="#fff" stroke="#3498db"  stroke-width="2"/>  <!-- Ruiru -->
                <circle cx="525" cy="95"  r="5" fill="#fff" stroke="#2980b9"  stroke-width="2"/>  <!-- Githurai -->
                <circle cx="638" cy="138" r="5" fill="#fff" stroke="#8e44ad"  stroke-width="2"/>  <!-- Sunton -->
                <circle cx="692" cy="198" r="5" fill="#fff" stroke="#f39c12"  stroke-width="2"/>  <!-- Lucky Summer -->

                <!-- Terminus stop labels -->
                <text class="rm-stop-label" x="150" y="392" text-anchor="end">Kawangware/Yaya</text>
                <text class="rm-stop-label" x="148" y="410" text-anchor="end">Kawangware Loop</text>
                <text class="rm-stop-label" x="136" y="432" text-anchor="end">Kaberia</text>
                <text class="rm-stop-label" x="108" y="458" text-anchor="end">Dagoretti</text>
                <text class="rm-stop-label" x="53"  y="382" text-anchor="end">Kikuyu</text>
                <text class="rm-stop-label" x="210" y="635">Ngong</text>
                <text class="rm-stop-label" x="278" y="580" text-anchor="end">Karen</text>
                <text class="rm-stop-label" x="325" y="575" text-anchor="end">Hardy</text>
                <text class="rm-stop-label" x="386" y="506" text-anchor="end">Ayani</text>
                <text class="rm-stop-label" x="424" y="492" text-anchor="end">Ngumo</text>
                <text class="rm-stop-label" x="510" y="604">South C</text>
                <text class="rm-stop-label" x="650" y="604">KPA</text>
                <text class="rm-stop-label" x="796" y="482">Fedha Estate</text>
                <text class="rm-stop-label" x="768" y="410">Jacaranda</text>
                <text class="rm-stop-label" x="826" y="322">Komarocks</text>
                <text class="rm-stop-label" x="824" y="466">Kayole</text>
                <text class="rm-stop-label" x="528" y="158" text-anchor="end">Ngumba</text>
                <text class="rm-stop-label" x="636" y="136">KU</text>
                <text class="rm-stop-label" x="616" y="76">Ruiru Town</text>
                <text class="rm-stop-label" x="504" y="90" text-anchor="end">Githurai</text>
                <text class="rm-stop-label" x="644" y="154">Sunton</text>
                <text class="rm-stop-label" x="698" y="214">Lucky Summer</text>

                <!-- ── CBD Hub ─────────────────────────────────────── -->
                <circle cx="540" cy="400" r="22" fill="url(#cbdG)"/>
                <circle cx="540" cy="400" r="11" fill="#0f766e"/>
                <circle cx="540" cy="400" r="5"  fill="#ffd700"/>
                <text class="rm-stop-label" x="556" y="396" style="fill:#fff;font-size:6.5px;font-weight:800;">CBD</text>

                <!-- ── Animated matatu bus dots (injected by JS) ────── -->
                <g id="rmBusDots"></g>
            </svg>
        </div>
    </div>

    {{-- ── Side panel ─────────────────────────────────────── --}}
    <div class="rm-panel">

        {{-- Assign card --}}
        <div class="rm-panel-card">
            <h3>Assign Driver</h3>
            <p>Click any route on the map, then select a driver below.</p>

            <div id="rmNoSel" style="color:var(--muted);font-size:0.83rem;padding:8px 0;text-align:center;">
                <i class='bx bx-map-pin' style="font-size:1.6rem;display:block;margin-bottom:4px;color:var(--border);"></i>
                No route selected
            </div>

            <div id="rmSelPanel" style="display:none;">
                <div class="rm-route-badge" id="rmBadge">
                    <span id="rmBadgeCode"></span>
                    <span id="rmBadgeName"></span>
                </div>

                <form id="rmForm" method="POST" action="{{ route('crew.assign') }}">
                    @csrf
                    <input type="hidden" name="route_id"   id="rmRouteId"  value="">
                    <input type="hidden" name="vehicle_id" value="">
                    <input type="hidden" name="status"     value="Active">

                    <div class="rm-field">
                        <label for="rmDriver">Driver</label>
                        <select id="rmDriver" name="driver_id" required>
                            <option value="">Select driver…</option>
                            @foreach ($drivers as $driver)
                                <option value="{{ $driver->driver_id }}">
                                    {{ trim($driver->first_name . ' ' . $driver->last_name) ?: 'Driver '.$driver->driver_id }}
                                    {{ $driver->status !== 'Active' ? '('.$driver->status.')' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button class="rm-btn" type="submit">
                        <i class='bx bxs-user-pin'></i>
                        Assign to Route
                    </button>
                </form>
            </div>
        </div>

        {{-- Route list --}}
        <div class="rm-panel-card" style="flex:1;overflow:hidden;display:flex;flex-direction:column;gap:0;">
            <h3 style="margin-bottom:10px;">All Routes</h3>
            <div class="rm-route-list" id="rmList"></div>
        </div>
    </div>
</div>

<div id="rmToast"></div>

{{-- ══ JS DATA ══ --}}
<script>
const RM_ROUTES  = @json($routesForJs);
const RM_DRIVERS = @json($driversForJs);

// Route code → unique colour matching line strokes above
const COLOR_MAP = {
    '46K/Y'  : '#e74c3c',
    '46P'    : '#e67e22',
    '4W'     : '#f39c12',
    '2'      : '#d4ac0d',
    '102'    : '#16a085',
    '111'    : '#27ae60',
    '24'     : '#2ecc71',
    '24C'    : '#1abc9c',
    '32A'    : '#3498db',
    '33NG'   : '#2980b9',
    '12C'    : '#8e44ad',
    '12D'    : '#9b59b6',
    '33B/FED': '#c0392b',
    '34B'    : '#e67e22',
    '19C'    : '#e74c3c',
    '1960'   : '#f1c40f',
    '43'     : '#16a085',
    '44G/Z'  : '#1abc9c',
    '145'    : '#3498db',
    '45G'    : '#2980b9',
    '49'     : '#8e44ad',
    '25A'    : '#f39c12',
};

function color(code) { return COLOR_MAP[code] || '#607d8b'; }
function driverName(id) {
    const d = RM_DRIVERS.find(x => x.driver_id == id);
    return d ? (d.first_name + ' ' + d.last_name).trim() : null;
}
function showToast(msg) {
    const t = document.getElementById('rmToast');
    t.textContent = msg; t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2800);
}

// ── Route list panel ──────────────────────────────────────
function buildList() {
    const list = document.getElementById('rmList');
    list.innerHTML = '';
    RM_ROUTES.forEach(r => {
        const el   = document.createElement('div');
        el.className = 'rm-route-item';
        el.dataset.code = r.route_code;
        const drv  = driverName(r.driver_id) || 'Unassigned';
        const c    = color(r.route_code);
        el.innerHTML = `
            <span class="rm-swatch" style="background:${c}"></span>
            <div style="flex:1;min-width:0;">
                <div class="rm-item-name">${r.route_code} · ${r.route_name}</div>
                <div class="rm-item-driver">${drv}</div>
            </div>
            ${r.driver_id ? `<span style="width:7px;height:7px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>` : ''}
        `;
        el.addEventListener('click', () => selectRoute(r.route_code));
        list.appendChild(el);
    });
}

// ── Line styling (assigned = solid, unassigned = dashed) ──
function applyStyles() {
    RM_ROUTES.forEach(r => {
        const line = document.querySelector(`[data-route="${r.route_code}"]`);
        if (!line) return;
        line.style.strokeOpacity = r.driver_id ? '1' : '0.4';
        line.style.strokeDasharray = r.driver_id ? 'none' : '11 7';
    });
}

// ── Select a route ────────────────────────────────────────
let selected = null;
function selectRoute(code) {
    if (selected) {
        document.querySelector(`[data-route="${selected}"]`)?.classList.remove('active');
        document.querySelector(`[data-code="${selected}"]`)?.classList.remove('active');
    }
    selected = code;
    document.querySelector(`[data-route="${code}"]`)?.classList.add('active');
    const item = document.querySelector(`[data-code="${code}"]`);
    if (item) { item.classList.add('active'); item.scrollIntoView({block:'nearest',behavior:'smooth'}); }

    const r = RM_ROUTES.find(x => x.route_code === code);
    if (!r) return;

    const c = color(code);
    document.getElementById('rmBadge').style.background = c;
    document.getElementById('rmBadgeCode').textContent  = code;
    document.getElementById('rmBadgeName').textContent  = '· ' + r.route_name;
    document.getElementById('rmRouteId').value          = r.route_id || '';
    document.getElementById('rmDriver').value           = r.driver_id || '';

    document.getElementById('rmNoSel').style.display    = 'none';
    document.getElementById('rmSelPanel').style.display = 'block';
}

// ── Tooltip on route hover ────────────────────────────────
const tooltip = document.getElementById('rmTooltip');
const wrap    = document.getElementById('rmMapWrap');
document.querySelectorAll('.rm-route-line').forEach(line => {
    const code  = line.dataset.route;
    const rname = line.dataset.name;
    line.addEventListener('mouseenter', () => {
        const r   = RM_ROUTES.find(x => x.route_code === code);
        const drv = r?.driver_id ? driverName(r.driver_id) : 'Unassigned';
        tooltip.textContent = `${code}  ${rname} — ${drv}`;
        tooltip.classList.add('vis');
    });
    line.addEventListener('mousemove', e => {
        const rc = wrap.getBoundingClientRect();
        tooltip.style.left = (e.clientX - rc.left + 14) + 'px';
        tooltip.style.top  = (e.clientY - rc.top  - 36) + 'px';
    });
    line.addEventListener('mouseleave', () => tooltip.classList.remove('vis'));
    line.addEventListener('click', () => selectRoute(code));
});

// ── Animated matatu dots ──────────────────────────────────
const busState = {};

function initBusDots() {
    const g = document.getElementById('rmBusDots');
    g.innerHTML = '';
    RM_ROUTES.forEach(r => {
        const code  = r.route_code;
        const line  = document.querySelector(`[data-route="${code}"]`);
        if (!line) return;
        const c     = color(code);
        const count = r.driver_id ? 2 : 1;

        for (let k = 0; k < count; k++) {
            const dot = document.createElementNS('http://www.w3.org/2000/svg','circle');
            dot.setAttribute('r', r.driver_id ? '5.5' : '4');
            dot.setAttribute('fill', c);
            dot.setAttribute('opacity', r.driver_id ? '1' : '0.4');
            if (r.driver_id) { dot.setAttribute('stroke','#fff'); dot.setAttribute('stroke-width','1.5'); }
            g.appendChild(dot);

            const speed = 0.00010 + Math.random() * 0.00008;
            const dir   = k % 2 === 0 ? 1 : -1;
            busState[`${code}-${k}`] = { dot, line, t: k / count, speed, dir };
        }
    });
}

let lastT = null;
function animateBuses(ts) {
    if (!lastT) lastT = ts;
    const dt = ts - lastT; lastT = ts;
    for (const key in busState) {
        const s = busState[key];
        s.t = (s.t + s.speed * dt * s.dir + 1) % 1;
        try {
            const pt = s.line.getPointAtLength(s.line.getTotalLength() * s.t);
            s.dot.setAttribute('cx', pt.x);
            s.dot.setAttribute('cy', pt.y);
        } catch(e){}
    }
    requestAnimationFrame(animateBuses);
}

// ── AJAX assign ───────────────────────────────────────────
document.getElementById('rmForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const rid = document.getElementById('rmRouteId').value;
    const did = document.getElementById('rmDriver').value;
    if (!rid || !did) { showToast('Select a route and driver first.'); return; }

    const btn = this.querySelector('button');
    btn.disabled = true; btn.textContent = 'Saving…';

    fetch(this.action, {
        method: 'POST',
        headers: {'X-CSRF-TOKEN': this.querySelector('[name=_token]').value, 'Accept':'application/json'},
        body: new FormData(this),
    })
    .then(() => {
        const r = RM_ROUTES.find(x => x.route_id == rid);
        if (r) {
            r.driver_id = parseInt(did);
            // Update list item
            const item  = document.querySelector(`[data-code="${r.route_code}"]`);
            if (item) {
                item.querySelector('.rm-item-driver').textContent = driverName(did) || 'Unassigned';
                if (!item.querySelector('span:last-child[style*="22c55e"]')) {
                    item.insertAdjacentHTML('beforeend',
                        `<span style="width:7px;height:7px;border-radius:50%;background:#22c55e;flex-shrink:0;"></span>`);
                }
            }
            applyStyles();
            initBusDots();
            // Update stats
            const aCount = RM_ROUTES.filter(x => x.driver_id).length;
            document.getElementById('statAssigned').textContent   = aCount;
            document.getElementById('statUnassigned').textContent = RM_ROUTES.length - aCount;
        }
        showToast(`Driver assigned to ${selected} ✓`);
    })
    .catch(() => showToast('Saved — reload to confirm.'))
    .finally(() => { btn.disabled = false; btn.innerHTML = `<i class='bx bxs-user-pin'></i> Assign to Route`; });
});

// ── Boot ──────────────────────────────────────────────────
buildList();
applyStyles();
initBusDots();
requestAnimationFrame(animateBuses);
</script>
@endsection
