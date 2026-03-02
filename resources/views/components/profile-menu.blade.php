@props([
    'align' => 'right',   // alineación del dropdown
    'variant' => 'full',  // full (campus) | compact (web)
])

@php
    $user = auth()->user();
    $isCompact = $variant === 'compact';
@endphp

@if($user)
    <div class="relative" id="profile-menu-wrapper">
        {{-- Botón avatar --}}
        <button
            type="button"
            id="profile-menu-trigger"
            class="inline-flex items-center gap-2 rounded-full border border-[#F7D2E4] bg-white/80 px-2 py-1 text-xs text-[#5B4A54] hover:border-[#F990B7] hover:text-[#F990B7]">
            <span class="inline-flex h-7 w-7 items-center justify-center rounded-full bg-[#F990B7] text-[11px] font-semibold text-white">
                {{ strtoupper(mb_substr($user->name ?? $user->email, 0, 1, 'UTF-8')) }}
            </span>
            @if(! $isCompact)
                <span class="hidden sm:inline max-w-[140px] truncate">{{ $user->name ?? $user->email }}</span>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>

        {{-- Dropdown --}}
        <div
            id="profile-menu-panel"
            class="absolute mt-2 w-52 rounded-xl border border-[#F7D2E4]/70 bg-white py-2 text-xs text-[#5B4A54] shadow-lg z-40 {{ $align === 'left' ? 'left-0' : 'right-0' }} hidden"
        >
            {{-- Cabecera con nombre + email --}}
            <div class="px-3 pb-2 border-b border-[#F7D2E4]/60 mb-1">
                <p class="font-semibold truncate">{{ $user->name ?? 'Usuario' }}</p>
                <p class="text-[11px] text-[#7B6B75] truncate">{{ $user->email }}</p>
            </div>

            {{-- Enlace al campus (solo si no estamos ya en una URL de campus) --}}
            @if(! request()->is('campus*'))
                <a href="{{ route('campus') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Mi campus</a>
            @endif

            {{-- Si es admin, acceso al panel siempre --}}
            @if($user->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Panel admin</a>

                {{-- Desde campus, opción para volver a la web --}}
                @if(request()->is('campus*'))
                    <a href="{{ url('/') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Volver a la web</a>
                @endif
            @endif

            {{-- Solo en variante full (campus) mostramos enlaces extra --}}
            @if(! $isCompact)
                <a href="{{ route('campus.profile.edit') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Perfil</a>

                @if(! $user->has_seen_onboarding)
                    <a href="{{ route('campus', ['showOnboarding' => 1]) }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">
                        Ver tutorial del campus
                    </a>
                @endif
            @endif

            {{-- Cerrar sesión --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="w-full px-3 py-1.5 text-left text-red-500 hover:bg-red-50">Cerrar sesión</button>
            </form>
        </div>
    </div>

    {{-- Script plano para controlar el dropdown sin depender de Alpine --}}
    <script>
        (function () {
            const trigger = document.getElementById('profile-menu-trigger');
            const panel = document.getElementById('profile-menu-panel');

            if (!trigger || !panel) return;

            const openPanel = () => {
                panel.classList.remove('hidden');
            };

            const closePanel = () => {
                panel.classList.add('hidden');
            };

            const togglePanel = (event) => {
                event.stopPropagation();
                if (panel.classList.contains('hidden')) {
                    openPanel();
                } else {
                    closePanel();
                }
            };

            trigger.addEventListener('click', togglePanel);

            // Cerrar al hacer clic fuera
            document.addEventListener('click', function (event) {
                if (!panel.classList.contains('hidden')) {
                    const wrapper = document.getElementById('profile-menu-wrapper');
                    if (wrapper && !wrapper.contains(event.target)) {
                        closePanel();
                    }
                }
            });

            // Cerrar con Esc
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closePanel();
                }
            });
        })();
    </script>
@endif
