@extends('layouts.app')

@section('title', 'Recetario · DulceConMaría')

@section('content')
    <section class="bg-[#FFF5FB] pb-16">
        <div class="max-w-5xl mx-auto px-4 pt-6 md:pt-28">
            <h1 class="text-2xl md:text-3xl font-black text-[#2558D5] text-center mb-8">Recetario</h1>

            @if ($recipe)
                {{-- Tarjeta de receta principal (adaptada de RecipeCard) --}}
                <div class="flex justify-center">
                    <article class="w-full md:w-[60%] bg-white rounded-xl shadow-md border border-[#f7b3cc8f]">
                        <div class="rounded-xl border border-[#f7b3cc8f] m-4 md:m-5 p-4 md:p-6">
                            <header class="flex items-center justify-center gap-3 mb-4">
                                <img src="/assets/iconRecipes.png" alt="Receta" class="w-7 md:w-9" />
                                <h2 class="text-[24px] md:text-[32px] font-black text-[#F7B3CC] text-center">
                                    {{ $recipe->title }}
                                </h2>
                            </header>

                            <div class="flex justify-center mb-4">
                                <img src="/assets/Rectangle 31.png" alt="{{ $recipe->title }}" class="w-[90%] rounded-lg" />
                            </div>

                            <div class="w-[92%] mx-auto border border-[#f7b3cc8f] mb-4"></div>

                            <div class="w-[92%] mx-auto space-y-6 text-sm md:text-base leading-relaxed">
                                <section>
                                    <h3 class="font-semibold text-[#2558D5] mb-2">Ingredientes</h3>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($recipe->ingredients as $ingredient)
                                            <li>{{ $ingredient }}</li>
                                        @endforeach
                                    </ul>
                                </section>

                                <div class="w-[92%] mx-auto border border-[#f7b3cc8f]"></div>

                                <section class="pb-2">
                                    <h3 class="font-semibold text-[#2558D5] mb-2">Preparación</h3>
                                    <p>{{ $recipe->description }}</p>
                                </section>
                            </div>
                        </div>
                    </article>
                </div>
            @else
                <p class="text-center text-sm text-slate-500 mt-10">Todavía no hay recetas en el recetario.</p>
            @endif
        </div>
    </section>
@endsection
