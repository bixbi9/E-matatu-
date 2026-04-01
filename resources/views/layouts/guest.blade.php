<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'e-Matatu') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white">
        <div class="min-h-screen flex">

            {{-- Left branding panel --}}
            <div class="hidden lg:flex lg:w-5/12 flex-col justify-between p-12 relative overflow-hidden"
                 style="background-color: #008080;">

                {{-- Decorative circles --}}
                <div class="absolute -top-16 -left-16 w-64 h-64 rounded-full opacity-10"
                     style="background-color: #FFD700;"></div>
                <div class="absolute -bottom-20 -right-20 w-80 h-80 rounded-full opacity-10"
                     style="background-color: #FFD700;"></div>

                {{-- Logo --}}
                <div class="relative z-10">
                    <div class="flex items-baseline gap-0.5">
                        <span class="text-4xl font-black" style="color: #FFD700;">e</span>
                        <span class="text-4xl font-black text-white">-Matatu</span>
                    </div>
                    <p class="mt-1 text-xs font-semibold tracking-widest uppercase"
                       style="color: rgba(255,255,255,0.55);">Fleet Management System</p>
                </div>

                {{-- Middle content --}}
                <div class="relative z-10">
                    {{-- Simple bus SVG --}}
                    <svg viewBox="0 0 300 120" xmlns="http://www.w3.org/2000/svg" class="w-full max-w-xs mb-8 opacity-75">
                        <rect x="8" y="25" width="270" height="72" rx="10" fill="rgba(255,255,255,0.12)" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"/>
                        <rect x="16" y="34" width="48" height="30" rx="4" fill="rgba(255,215,0,0.45)" stroke="rgba(255,215,0,0.7)" stroke-width="1.5"/>
                        <rect x="72" y="34" width="48" height="30" rx="4" fill="rgba(255,215,0,0.45)" stroke="rgba(255,215,0,0.7)" stroke-width="1.5"/>
                        <rect x="128" y="34" width="48" height="30" rx="4" fill="rgba(255,215,0,0.45)" stroke="rgba(255,215,0,0.7)" stroke-width="1.5"/>
                        <rect x="184" y="34" width="48" height="30" rx="4" fill="rgba(255,215,0,0.45)" stroke="rgba(255,215,0,0.7)" stroke-width="1.5"/>
                        <rect x="240" y="38" width="32" height="22" rx="4" fill="rgba(255,215,0,0.25)" stroke="rgba(255,215,0,0.5)" stroke-width="1"/>
                        <circle cx="50" cy="104" r="14" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.45)" stroke-width="1.5"/>
                        <circle cx="50" cy="104" r="7" fill="rgba(255,255,255,0.3)"/>
                        <circle cx="226" cy="104" r="14" fill="rgba(255,255,255,0.18)" stroke="rgba(255,255,255,0.45)" stroke-width="1.5"/>
                        <circle cx="226" cy="104" r="7" fill="rgba(255,255,255,0.3)"/>
                        <rect x="0" y="48" width="12" height="18" rx="3" fill="rgba(255,215,0,0.55)"/>
                    </svg>

                    <h2 class="text-3xl font-bold text-white leading-tight mb-3">
                        Manage your fleet<br>with confidence.
                    </h2>
                    <p class="text-sm leading-relaxed" style="color: rgba(255,255,255,0.62);">
                        Real-time vehicle tracking, maintenance scheduling,
                        and compliance management — all in one place.
                    </p>

                    <ul class="mt-6 space-y-3">
                        @foreach(['Driver & vehicle management', 'Inspection & maintenance tracking', 'Insurance & route management'] as $feature)
                        <li class="flex items-center gap-2.5 text-sm" style="color: rgba(255,255,255,0.82);">
                            <span class="w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0"
                                  style="background-color: rgba(255,215,0,0.2);">
                                <i class='bx bx-check text-xs' style="color: #FFD700;"></i>
                            </span>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Bottom stats --}}
                <div class="relative z-10 grid grid-cols-3 gap-4 pt-6 border-t"
                     style="border-color: rgba(255,255,255,0.15);">
                    @foreach([['100+','Vehicles'], ['50+','Drivers'], ['24/7','Monitoring']] as [$num, $label])
                    <div class="text-center">
                        <p class="text-xl font-bold text-white">{{ $num }}</p>
                        <p class="text-xs mt-0.5" style="color: rgba(255,255,255,0.5);">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Right form panel --}}
            <div class="w-full lg:w-7/12 flex flex-col justify-center items-center px-8 py-12 bg-white">

                {{-- Mobile logo --}}
                <div class="lg:hidden mb-10 text-center">
                    <span class="text-3xl font-black" style="color: #FFD700;">e</span><span class="text-3xl font-black" style="color: #008080;">-Matatu</span>
                    <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest">Fleet Management System</p>
                </div>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

        </div>
    </body>
</html>
