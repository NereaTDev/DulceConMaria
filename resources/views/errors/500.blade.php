@extends('layouts.app')

@section('title', 'Ha ocurrido un error · DulceConMaría')

@section('content')
    <section class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4 flex flex-col items-center text-center gap-6">
            <p class="text-[11px] tracking-[0.18em] uppercase text-[#F990B7]">Error 500</p>
            <h1 class="text-3xl md:text-4xl font-semibold text-[#2B1A22]">Algo se ha quemado en el horno...</h1>
            <p class="text-sm md:text-base text-[#5B4A54] max-w-xl">
                Ha ocurrido un error interno al cargar esta página. Nuestro horno técnico ya está revisando
                qué ha pasado.
            </p>

            @if(app()->hasDebugModeEnabled() && config('app.debug'))
                <p class="mt-2 text-xs text-red-500/80">
                    Modo debug activado — este mensaje solo se muestra en entornos de desarrollo.
                </p>
            @endif

            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-[#F990B7] text-white px-5 py-2.5 text-sm font-semibold hover:bg-[#FF4B88] transition">
                    Volver a la página principal
                </a>
                @auth
                    <a href="{{ route('campus') }}" class="inline-flex items-center justify-center rounded-full border border-[#F7D2E4] text-[#F990B7] px-5 py-2.5 text-sm font-semibold hover:bg-[#FFF0F6] transition">
                        Ir a mi campus
                    </a>
                @endauth
            </div>
        </div>
    </section>
@endsection
