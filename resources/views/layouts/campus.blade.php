<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Campus · DulceConMaría')</title>
    <meta name="theme-color" content="#F990B7">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    {{-- Icono principal para iOS / PWA --}}
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFF5FB] text-slate-900">
<div class="min-h-screen flex flex-col">
    {{-- Header campus alineado con el de la web --}}
    <header class="fixed top-0 inset-x-0 z-30 transition-shadow bg-white" id="campus-nav">
        <nav class="max-w-5xl mx-auto flex items-center justify-between w-full px-4 md:px-12 py-3">
            <div class="flex items-center gap-2">
                <a href="{{ route('campus') }}" class="flex items-center gap-2">
                    <img src="/assets/Logo.png" alt="DulceConMaría" class="h-12 w-auto" />
                </a>
            </div>

            {{-- Navegación desktop --}}
            <div class="hidden md:flex items-center gap-4 text-xs text-slate-900">
                <a href="{{ route('campus') }}" class="hover:text-[#FF4B88]">Inicio</a>
                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/') }}" class="hover:text-[#FF4B88]">Volver a la web</a>
                @endif

                {{-- Acciones de usuario en el campus: perfil, campus, panel admin, logout --}}
                @auth
                    <x-profile-menu variant="full" />
                @endauth
            </div>

            {{-- Botón menú móvil (igual estilo que la web) --}}
            <button id="campus-menu-toggle" class="md:hidden inline-flex items-center justify-center rounded-full border border-[#F7D2E4] p-2 text-[#F990B7] bg-white/80">
                <span class="sr-only">Abrir menú del campus</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                </svg>
            </button>
        </nav>

        {{-- Menú móvil reutilizable --}}
        @php
            $campusMenuLinks = [
                ['label' => 'Inicio', 'href' => route('campus')],
                ['label' => 'Mi progreso (próximamente)'],
                ['label' => 'Soporte (próximamente)'],
            ];

            // Enlaces inferiores (cross: web / panel)
            $campusBottomLinks = [
                ['label' => 'Volver a la web', 'href' => url('/'), 'section' => 'bottom'],
            ];
            if(auth()->check() && auth()->user()->role === 'admin') {
                $campusBottomLinks[] = ['label' => 'Panel admin', 'href' => route('admin.dashboard'), 'section' => 'bottom'];
            }
            $campusBottomLinks[] = ['label' => 'Cerrar sesión', 'href' => route('logout'), 'variant' => 'danger', 'section' => 'bottom', 'method' => 'post'];

            $campusMenuLinks = array_merge($campusMenuLinks, $campusBottomLinks);
        @endphp
        <x-mobile-menu menu-id="campus" title="Campus" :links="$campusMenuLinks" />
    </header>

    <main id="campus-main" class="flex-1 pt-20">
        @yield('content')
    </main>

    <footer class="border-t border-[#F7D2E4] bg-white/90">
        <div id="campus-footer" class="max-w-6xl mx-auto px-4 py-4 text-[11px] text-[#7B6B75] flex justify-between">
            <span>DulceConMaría · Campus de chocolatería</span>
            <span>Hecho con cariño, chocolate y una pizca de azúcar glas.</span>
        </div>
    </footer>
</div>

{{-- Desactivado temporalmente: el service worker estaba cacheando contenido del campus
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function () {
            navigator.serviceWorker.register('/service-worker.js')
                .catch(function (err) {
                    console.error('ServiceWorker registration failed:', err);
                });
        });
    }
</script>
--}}
</body>
</html>
