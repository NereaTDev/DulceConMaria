@extends('layouts.app')

@section('title', 'Servicio en mantenimiento · DulceConMaría')

@section('content')
    <section class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4 flex flex-col items-center text-center gap-6">
            <p class="text-[11px] tracking-[0.18em] uppercase text-[#F990B7]">Error 503</p>
            <h1 class="text-3xl md:text-4xl font-semibold text-[#2B1A22]">Estamos templando el chocolate...</h1>
            <p class="text-sm md:text-base text-[#5B4A54] max-w-xl">
                El campus está temporalmente en mantenimiento o actualizándose. Vuelve en unos minutos
                y tendrás todo listo para seguir aprendiendo.
            </p>

            <div class="mt-4 flex flex-col sm:flex-row gap-3">
                <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-full bg-[#F990B7] text-white px-5 py-2.5 text-sm font-semibold hover:bg-[#FF4B88] transition">
                    Ir a la página principal
                </a>
            </div>
        </div>
    </section>
@endsection
