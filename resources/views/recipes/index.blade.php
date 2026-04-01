@extends('layouts.app')

@section('title', 'Recetario · DulceConMaría')

@push('styles')
<style>
    /*
     * Las cards se apilan en la misma celda de grid (grid-area: 1/1)
     * para que la altura no salte cuando dos están visibles a la vez.
     * Solo se animan translate + opacity — sin scale, sin rotate.
     */

    /* Entrada: llega desde abajo-derecha (como saliendo del mazo) */
    .deck-enter {
        transition: transform 0.38s cubic-bezier(0.22, 1, 0.36, 1),
                    opacity   0.25s ease;
    }
    .deck-enter-start {
        transform: translateX(10px) translateY(10px);
        opacity: 0;
    }
    .deck-enter-end {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }

    /* Salida: se va hacia arriba-izquierda suavemente */
    .deck-leave {
        transition: transform 0.22s ease-in,
                    opacity   0.2s  ease-in;
    }
    .deck-leave-start {
        transform: translateX(0) translateY(0);
        opacity: 1;
    }
    .deck-leave-end {
        transform: translateX(-8px) translateY(-8px);
        opacity: 0;
    }
</style>
@endpush

@section('content')
<div class="bg-[#FFF5FB] min-h-screen">

    @if($recipes->isEmpty())
        <div class="flex items-center justify-center min-h-screen">
            <p class="text-sm text-slate-500">Todavía no hay recetas en el recetario.</p>
        </div>
    @else

    <div
        x-data="{
            current: 0,
            total: {{ $recipes->count() }},
            go(i) { this.current = i; },
            next() { if (this.current < this.total - 1) this.go(this.current + 1); },
            prev() { if (this.current > 0) this.go(this.current - 1); }
        }"
    >
        {{-- Aside fijo a la izquierda (desktop) --}}
        <aside class="hidden md:flex fixed left-0 top-[72px] h-[calc(100vh-72px)] w-52 z-20 flex-col">
            <div class="overflow-y-auto px-4 py-6">
                <h3 class="text-[10px] font-bold text-[#2558D5] uppercase tracking-widest mb-4">Recetario</h3>
                <ul class="space-y-0.5">
                    @foreach($recipes as $i => $r)
                        <li>
                            <button
                                @click="go({{ $i }})"
                                :class="current === {{ $i }}
                                    ? 'bg-[#fff0f6] text-[#f7b3cc] font-semibold pl-3 border-l-2 border-[#f7b3cc]'
                                    : 'text-slate-500 hover:text-[#f7b3cc] hover:bg-[#fff8fb] pl-3'"
                                class="text-left text-xs w-full py-1.5 rounded-r transition-all leading-snug"
                            >{{ $r->title }}</button>
                        </li>
                    @endforeach
                </ul>
            </div>
            {{-- Contador en aside --}}
            <div class="px-4 py-3 border-t border-[#f7b3cc]/20 text-[10px] text-slate-400 text-center" x-text="`${current + 1} de {{ $recipes->count() }}`"></div>
        </aside>

        {{-- Flechas fijas en pantalla --}}
        <button
            @click="prev()"
            :disabled="current === 0"
            :class="current === 0 ? 'opacity-20 cursor-not-allowed' : 'hover:bg-[#f7b3cc] hover:text-white hover:border-[#f7b3cc] hover:shadow-lg'"
            class="fixed left-3 md:left-56 top-1/2 -translate-y-1/2 z-30 w-10 h-10 md:w-11 md:h-11 rounded-full bg-white border-2 border-[#f7b3cc] text-[#f7b3cc] text-xl font-bold flex items-center justify-center shadow-md transition-all"
            aria-label="Receta anterior"
        >‹</button>

        <button
            @click="next()"
            :disabled="current === total - 1"
            :class="current === total - 1 ? 'opacity-20 cursor-not-allowed' : 'hover:bg-[#f7b3cc] hover:text-white hover:border-[#f7b3cc] hover:shadow-lg'"
            class="fixed right-3 top-1/2 -translate-y-1/2 z-30 w-10 h-10 md:w-11 md:h-11 rounded-full bg-white border-2 border-[#f7b3cc] text-[#f7b3cc] text-xl font-bold flex items-center justify-center shadow-md transition-all"
            aria-label="Receta siguiente"
        >›</button>

        {{-- Contenido principal --}}
        <main class="md:pl-52 min-h-screen flex flex-col items-center px-10 md:px-16 pb-12">

            <h1 class="text-2xl md:text-3xl font-black text-[#2558D5] text-center mt-8 mb-10">Recetario</h1>

            {{-- Tarjetas --}}
            <div class="w-full max-w-3xl">

                {{-- Grid de una sola celda: todas las cards se superponen sin afectar height --}}
                <div class="relative pb-3 pr-3" style="display:grid;">
                    {{-- Cartas decorativas fijas (siempre visibles, no se animan) --}}
                    <div class="rounded-2xl border border-[#f7b3cc]/20 shadow bg-white"
                         style="grid-area:1/1; transform: translate(9px, 9px) rotate(1.8deg); pointer-events:none;"></div>
                    <div class="rounded-2xl border border-[#f7b3cc]/30 shadow-sm bg-white"
                         style="grid-area:1/1; transform: translate(4px, 5px) rotate(0.8deg); pointer-events:none;"></div>

                @foreach($recipes as $i => $recipe)
                    @php
                        $ingredients = is_array($recipe->ingredients) ? $recipe->ingredients : [];
                        $half = ceil(count($ingredients) / 2);
                        $col1 = array_slice($ingredients, 0, $half);
                        $col2 = array_slice($ingredients, $half);
                    @endphp

                    {{-- Solo el article se anima; grid-area:1/1 lo superpone sin alterar height --}}
                    <div
                        x-show="current === {{ $i }}"
                        x-transition:enter="deck-enter"
                        x-transition:enter-start="deck-enter-start"
                        x-transition:enter-end="deck-enter-end"
                        x-transition:leave="deck-leave"
                        x-transition:leave-start="deck-leave-start"
                        x-transition:leave-end="deck-leave-end"
                        style="grid-area:1/1; display:none;"
                    >
                            {{-- Tarjeta principal --}}
                            <article class="relative bg-white rounded-2xl shadow-lg border border-[#f7b3cc]/40 p-6 md:p-10">

                                {{-- Número + título --}}
                                <header class="flex items-center gap-4 mb-6">
                                    <span class="flex-shrink-0 w-9 h-9 rounded-full bg-[#FFF5FB] border-2 border-[#f7b3cc] flex items-center justify-center text-[#f7b3cc] font-bold text-sm shadow-sm">
                                        {{ $i + 1 }}
                                    </span>
                                    <h2 class="text-2xl md:text-3xl font-black text-[#3a2a1a] leading-tight">
                                        {{ $recipe->title }}
                                    </h2>
                                </header>

                                {{-- Imagen --}}
                                @if($recipe->image_url)
                                    <div class="mb-7">
                                        <img
                                            src="{{ $recipe->image_url }}"
                                            alt="{{ $recipe->title }}"
                                            class="w-full rounded-xl object-contain max-h-[420px] shadow"
                                        />
                                    </div>
                                @endif

                                <div class="border-t border-[#f7b3cc]/30 mb-6"></div>

                                {{-- Ingredientes en dos columnas --}}
                                <section class="mb-6">
                                    <h3 class="font-bold text-[#2558D5] text-xs uppercase tracking-widest mb-4">Ingredientes</h3>
                                    @if(count($ingredients) > 0)
                                        <div class="grid grid-cols-2 gap-x-8 gap-y-1.5 text-sm text-slate-700">
                                            <ul class="space-y-1.5">
                                                @foreach($col1 as $ingredient)
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-[#f7b3cc] font-bold mt-0.5 leading-none">·</span>
                                                        <span>{{ $ingredient }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @if(count($col2) > 0)
                                            <ul class="space-y-1.5">
                                                @foreach($col2 as $ingredient)
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-[#f7b3cc] font-bold mt-0.5 leading-none">·</span>
                                                        <span>{{ $ingredient }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-sm text-slate-400 italic">Sin ingredientes especificados.</p>
                                    @endif
                                </section>

                                <div class="border-t border-[#f7b3cc]/30 mb-6"></div>

                                {{-- Preparación --}}
                                <section>
                                    <h3 class="font-bold text-[#2558D5] text-xs uppercase tracking-widest mb-4">Preparación</h3>
                                    <div class="text-sm text-slate-700 leading-relaxed whitespace-pre-line">{{ $recipe->description }}</div>
                                </section>

                            </article>
                    </div>
                @endforeach
                </div>{{-- /relative wrapper --}}
            </div>

            {{-- Índice en mobile --}}
            <div class="md:hidden flex flex-wrap justify-center gap-2 mt-8">
                @foreach($recipes as $i => $r)
                    <button
                        @click="go({{ $i }})"
                        :class="current === {{ $i }}
                            ? 'bg-[#f7b3cc] text-white border-[#f7b3cc]'
                            : 'bg-white text-[#f7b3cc] border-[#f7b3cc]/50 hover:border-[#f7b3cc]'"
                        class="px-3 py-1 rounded-full border text-xs font-medium transition-colors"
                    >{{ $r->title }}</button>
                @endforeach
            </div>

        </main>
    </div>
    @endif

</div>
@endsection
