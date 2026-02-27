@props([
    'align' => 'right',   // alineación del dropdown
    'variant' => 'full',  // full (campus) | compact (web)
])

@php
    $user = auth()->user();
    $isCompact = $variant === 'compact';
@endphp

@if($user)
    <div class="relative" x-data="{ open: false }">
        {{-- Botón avatar --}}
        <button @click="open = !open" class="inline-flex items-center gap-2 rounded-full border border-[#F7D2E4] bg-white/80 px-2 py-1 text-xs text-[#5B4A54] hover:border-[#F990B7] hover:text-[#F990B7]">
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
            x-cloak
            x-show="open"
            @click.away="open = false"
            @keydown.escape.window="open = false"
            class="absolute mt-2 w-52 rounded-xl border border-[#F7D2E4]/70 bg-white py-2 text-xs text-[#5B4A54] shadow-lg z-40 {{ $align === 'left' ? 'left-0' : 'right-0' }}"
        >
            {{-- Cabecera con nombre + email --}}
            <div class="px-3 pb-2 border-b border-[#F7D2E4]/60 mb-1">
                <p class="font-semibold truncate">{{ $user->name ?? 'Usuario' }}</p>
                <p class="text-[11px] text-[#7B6B75] truncate">{{ $user->email }}</p>
            </div>

            {{-- Enlace al campus (siempre) --}}
            <a href="{{ route('campus') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Mi campus</a>

            {{-- Solo en variante full (campus) mostramos panel admin + perfil --}}
            @if(! $isCompact)
                @if($user->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Panel admin</a>
                @endif

                <a href="{{ route('profile.edit') }}" class="block px-3 py-1.5 hover:bg-[#FFF5FB]">Perfil</a>
            @endif

            {{-- Cerrar sesión --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit" class="w-full px-3 py-1.5 text-left text-red-500 hover:bg-red-50">Cerrar sesión</button>
            </form>
        </div>
    </div>
@endif
