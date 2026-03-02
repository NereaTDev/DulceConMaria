@extends('layouts.app')

@section('title', 'Tutorial del campus · DulceConMaría')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <div class="bg-white border border-[#F7D2E4] rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] p-6 md:p-8">
        <h1 class="text-2xl font-semibold text-[#2B1A22] mb-4">
            Bienvenida al campus de DulceConMaría
        </h1>
        <p class="text-sm text-[#5B4A54] mb-6 leading-relaxed">
            En este pequeño tutorial te enseño dónde encontrar tu curso, cómo ver las lecciones, revisar las recetas
            y gestionar tu perfil para que puedas aprovechar al máximo el campus.
        </p>

        <div class="space-y-6 text-sm text-[#5B4A54] leading-relaxed">
            <section>
                <h2 class="text-base font-semibold text-[#2B1A22] mb-1">1. Tu curso principal</h2>
                <p>
                    Desde la página de inicio del campus verás un listado con tus cursos. Haz clic en el curso principal
                    para ver todas las lecciones disponibles. Si en algún momento añado nuevos contenidos, se irán
                    incorporando aquí.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-[#2B1A22] mb-1">2. Lecciones y progreso</h2>
                <p>
                    Dentro de cada lección encontrarás el vídeo principal y, cuando corresponda, material extra
                    (recetas en PDF, listas de ingredientes, etc.). Tu progreso se irá guardando mientras avanzas
                    para que puedas continuar más adelante justo donde lo dejaste.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-[#2B1A22] mb-1">3. Recetas y material extra</h2>
                <p>
                    En el menú superior tienes una sección de <strong>Recetas</strong> donde encontrarás elaboraciones
                    adicionales relacionadas con el curso. Algunas recetas pueden ser públicas y otras exclusivas del
                    campus; si ves un icono de candado significa que son solo para alumnas.
                </p>
            </section>

            <section>
                <h2 class="text-base font-semibold text-[#2B1A22] mb-1">4. Tu perfil y soporte</h2>
                <p>
                    Desde el menú de usuario puedes acceder a tu perfil para actualizar tus datos. Si en algún momento
                    tienes problemas con el acceso, con un pago o con algún vídeo, puedes contactar conmigo respondiendo
                    a los correos que recibes del campus o usando el canal de soporte que te haya indicado.
                </p>
            </section>
        </div>

        <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
            <form method="POST" action="{{ route('onboarding.complete') }}">
                @csrf
                <input type="hidden" name="action" value="view">
                <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#F990B7] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                    He entendido el tutorial, ir al campus
                </button>
            </form>

            <a href="{{ route('campus') }}" class="text-xs text-[#7B6B75] hover:text-[#FF4B88] underline text-center">
                Volver al campus sin marcar como completado
            </a>
        </div>
    </div>
</div>
@endsection
