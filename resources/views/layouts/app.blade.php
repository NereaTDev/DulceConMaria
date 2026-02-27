<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'DulceConMaría')</title>

    <meta name="description" content="Cursos online de bombones, chocolates y repostería creativa con DulceConMaría.">
    <meta name="theme-color" content="#F990B7">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512x512.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FFF5FB] text-slate-900 antialiased">
    <div class="min-h-screen flex flex-col">
        {{-- NavBar adaptada al estilo original --}}
        <header class="fixed top-0 inset-x-0 z-30 transition-shadow bg-white" id="main-nav">
            <nav class="max-w-5xl mx-auto flex items-center justify-between w-full px-4 md:px-12 py-3">
                <div class="navbar-logo flex items-center">
                    <a href="{{ url('/') }}" class="block w-36 md:w-48">
                        <img src="/assets/Logo.png" alt="DulceConMaría" class="w-auto h-12" />
                    </a>
                </div>

                {{-- Navegación desktop --}}
                <div class="hidden md:flex items-center justify-end gap-6 text-sm font-semibold">
                    <a href="{{ url('/') }}#hero" class="text-[#F990B7] hover:scale-110 hover:text-[#F5387E] transition">
                        Curso
                    </a>
                    <a href="{{ route('recipes.index') }}" class="text-[#F990B7] hover:scale-110 hover:text-[#F5387E] transition">
                        Recetas
                    </a>
                    <span class="text-[#F990B7] hover:scale-110 hover:text-[#F5387E] transition cursor-not-allowed opacity-60">
                        Blog
                    </span>
                    @guest
                        <a href="{{ route('login') }}" class="text-[#F990B7] hover:scale-110 hover:text-[#F5387E] transition">
                            Iniciar sesión
                        </a>
                    @else
                        <a href="{{ route('campus') }}" class="text-[#F990B7] hover:scale-110 hover:text-[#F5387E] transition">
                            Mi campus
                        </a>
                    @endguest
                    @guest
                        <a href="{{ route('register') }}" class="relative text-white bg-[#F990B7] px-4 py-1 rounded-full overflow-hidden">
                            <span class="relative z-10">Inscribirme</span>
                        </a>
                    @else
                        <a href="{{ route('campus') }}" class="relative text-white bg-[#F990B7] px-4 py-1 rounded-full overflow-hidden">
                            <span class="relative z-10">Entrar al campus</span>
                        </a>
                    @endguest
                </div>

                {{-- Botón menú móvil --}}
                <button id="main-menu-toggle" class="md:hidden inline-flex items-center justify-center rounded-full border border-[#F7D2E4] p-2 text-[#F990B7] bg-white/80">
                    <span class="sr-only">Abrir menú</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3 5h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                    </svg>
                </button>
            </nav>

            {{-- Menú móvil reutilizable --}}
            @php
                $mainMenuLinks = [
                    ['label' => 'Curso', 'href' => url('/') . '#hero'],
                    ['label' => 'Recetas', 'href' => route('recipes.index')],
                    ['label' => 'Blog (próximamente)'],
                ];

                // Enlaces inferiores (cross: campus / admin / login)
                $mainBottomLinks = [];
                if(auth()->check()) {
                    $mainBottomLinks[] = ['label' => 'Mi campus', 'href' => route('campus'), 'section' => 'bottom'];
                    if(auth()->user()->role === 'admin') {
                        $mainBottomLinks[] = ['label' => 'Panel admin', 'href' => route('admin.dashboard'), 'section' => 'bottom'];
                    }
                } else {
                    $mainBottomLinks[] = ['label' => 'Iniciar sesión', 'href' => route('login'), 'section' => 'bottom'];
                }
                $mainBottomLinks[] = ['label' => 'Inscribirme', 'href' => url('/') . '#cta', 'variant' => 'primary', 'section' => 'bottom'];

                $mainMenuLinks = array_merge($mainMenuLinks, $mainBottomLinks);
            @endphp
            <x-mobile-menu menu-id="main" title="Menú" :links="$mainMenuLinks" />
        </header>

        <main class="flex-1 pt-14">
            @yield('content')
        </main>

        <footer class="border-t border-[#F7D2E4] bg-[#FFF5FB] mt-10">
            <div class="max-w-5xl mx-auto px-4 py-6 text-xs text-[#7B6B75] flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <p>DulceConMaría - {{ date('Y') }} © · Todos los derechos reservados.</p>
                <p class="text-[11px]">Curso básico de bombones de chocolate · Hecho con cariño y mucho cacao.</p>
            </div>
        </footer>
    </div>

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
</body>
</html>
