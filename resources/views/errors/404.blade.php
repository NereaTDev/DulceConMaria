@extends('layouts.app')

@section('title', 'Página no encontrada · DulceConMaría')

@section('content')
    <section class="pt-24 pb-16">
        <div class="max-w-4xl mx-auto px-4 flex flex-col items-center text-center gap-6">
            <p class="text-[11px] tracking-[0.18em] uppercase text-[#F990B7]">Error 404</p>
            <h1 class="text-3xl md:text-4xl font-semibold text-[#2B1A22]">Vaya, esta página se ha derretido...</h1>
            <p class="text-sm md:text-base text-[#5B4A54] max-w-xl">
                No hemos encontrado la página que estabas buscando. Puede que el enlace haya caducado,
                se haya movido o que nunca haya existido.
            </p>

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
