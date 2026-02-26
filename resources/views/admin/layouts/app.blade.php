<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Admin · DulceConMaría')</title>
    <meta name="theme-color" content="#F990B7">
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="192x192" href="/icons/icon-192x192.png">
    <link rel="apple-touch-icon" sizes="512x512" href="/icons/icon-512x512.png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-900">
@include('admin.partials.toast')
<div class="min-h-screen flex flex-col md:flex-row">
    {{-- Top bar (visible en mobile, comparte contenido con el aside) --}}
    <header class="md:hidden bg-white border-b border-slate-200 flex items-center justify-between px-4 py-3">
        <div class="text-pink-600 font-bold text-sm">
            Admin · DulceConMaría
        </div>
        <div class="flex items-center gap-2">
            <button id="admin-menu-toggle" class="inline-flex items-center justify-center rounded-full border border-[#F7D2E4] p-2 text-[#F990B7] bg-white/80">
                <span class="sr-only">Abrir menú</span>
                {{-- Icono hamburguesa --}}
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2zm0 4h14a1 1 0 110 2H3a1 1 0 010-2z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        @php
            $adminMenuLinks = [
                ['label' => 'Dashboard', 'href' => route('admin.dashboard')],
                ['label' => 'Cursos', 'href' => route('admin.courses.index')],
                ['label' => 'Lecciones', 'href' => route('admin.lessons.index')],
                ['label' => 'Recetas', 'href' => route('admin.recipes.index')],
                ['label' => 'Usuarios', 'href' => route('admin.users.index')],
                ['label' => 'Inscripciones', 'href' => route('admin.enrollments.index')],
            ];

            // Enlaces inferiores (cross: web / campus)
            $adminBottomLinks = [
                ['label' => 'Volver a la web', 'href' => url('/'), 'section' => 'bottom'],
            ];
            if(auth()->check()) {
                $adminBottomLinks[] = ['label' => 'Mi campus', 'href' => route('campus'), 'section' => 'bottom'];
            }
            $adminBottomLinks[] = ['label' => 'Cerrar sesión', 'href' => route('logout'), 'variant' => 'danger', 'section' => 'bottom', 'method' => 'post'];

            $adminMenuLinks = array_merge($adminMenuLinks, $adminBottomLinks);
        @endphp
        <x-mobile-menu menu-id="admin" title="Admin" :links="$adminMenuLinks" panel-class="bg-white" />
    </header>

    <aside class="md:flex md:flex-col md:w-64 bg-white border-r border-slate-200 flex flex-col md:h-screen md:sticky md:top-0 md:block hidden" id="admin-sidebar">
        <div class="flex items-center justify-between p-4 text-sm border-b border-slate-200 hidden md:flex">
            <span class="text-pink-600 font-bold">Admin · DulceConMaría</span>
            <a href="{{ url('/') }}" class="text-xs text-slate-600 border border-slate-200 rounded-full px-3 py-1 hover:bg-slate-50">Volver a la web</a>
        </div>
        <nav class="flex-1 px-4 py-4 space-y-2 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="block rounded px-2 py-1 hover:bg-pink-50">Dashboard</a>
            <a href="{{ route('admin.courses.index') }}" class="block rounded px-2 py-1 hover:bg-pink-50">Cursos</a>
            <a href="{{ route('admin.lessons.index') }}" class="block rounded px-2 py-1 hover:bg-pink-50">Lecciones</a>
            <a href="{{ route('admin.recipes.index') }}" class="block rounded px-2 py-1 hover:bg-pink-50">Recetas</a>
            <a href="{{ route('admin.users.index') }}" class="block rounded px-2 py-1 hover:bg-pink-50">Usuarios</a>
            <a href="{{ route('admin.enrollments.index') }}" class="block rounded px-2 py-1 hover:bg-pink-50">Inscripciones</a>
        </nav>
        @auth
            <div class="px-4 py-3 text-xs border-t border-slate-200 space-y-2">
                <p class="px-2 py-1">Conectado como <span class="font-semibold">{{ auth()->user()->email }}</span></p>
                <div class="flex flex-col gap-1 text-[11px] text-sm">
                    <a href="{{ url('/') }}" class="text-slate-900 hover:text-pink-500 hover:underline hover:decoration-pink-500 underline-offset-2 px-2 py-1">Volver a la web</a>
                    <a href="{{ route('campus') }}" class="text-slate-900 hover:text-pink-500 hover:underline hover:decoration-pink-500 underline-offset-2 px-2 py-1">Mi campus</a>
                    <form action="{{ route('logout') }}" method="POST" class="mt-1">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600 px-2 py-4">Cerrar sesión</button>
                    </form>
                </div>
            </div>
        @endauth
    </aside>

    <main class="flex-1 p-4 md:p-6">
        @yield('content')
    </main>
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
