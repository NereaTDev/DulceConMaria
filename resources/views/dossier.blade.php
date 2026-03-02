@extends('layouts.app')

@section('title', 'Dossier del curso · DulceConMaría')

@section('content')
    <section class="bg-[#FFF5FB] py-10 md:py-14">
        <div class="max-w-4xl mx-auto px-4 space-y-8">
            <div class="bg-white border border-[#F7D2E4] rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] p-6 md:p-8">
                <h1 class="text-2xl md:text-3xl font-semibold text-[#2B1A22] mb-3">
                    Dossier del Curso Básico de Bombones de Chocolate
                </h1>
                <p class="text-sm text-[#5B4A54] leading-relaxed mb-4">
                    Aquí tienes un resumen del contenido del curso, a quién va dirigido y qué puedes esperar cuando entres
                    en el campus de DulceConMaría.
                </p>

                <h2 class="text-base font-semibold text-[#2B1A22] mt-4 mb-1">¿Para quién es este curso?</h2>
                <p class="text-sm text-[#5B4A54] leading-relaxed mb-3">
                    Para personas que quieren empezar en la chocolatería desde cero o consolidar una base sólida:
                    amantes del dulce, reposteros caseros y emprendedoras que quieren ofrecer bombones artesanales.
                </p>

                <h2 class="text-base font-semibold text-[#2B1A22] mt-4 mb-1">Qué aprenderás</h2>
                <ul class="text-sm text-[#5B4A54] leading-relaxed list-disc list-inside space-y-1 mb-4">
                    <li>Templado de chocolate y manejo correcto de las temperaturas.</li>
                    <li>Uso del material básico y preparación del espacio de trabajo.</li>
                    <li>Moldeado, desmolde y rellenado de bombones.</li>
                    <li>Decoraciones sencillas con acabado profesional.</li>
                    <li>Conservación, empaquetado y presentación para regalo o venta.</li>
                </ul>

                <h2 class="text-base font-semibold text-[#2B1A22] mt-4 mb-1">Bloques del curso</h2>
                <ol class="text-sm text-[#5B4A54] leading-relaxed list-decimal list-inside space-y-1 mb-4">
                    <li><strong>Introducción a la chocolatería</strong>: tipos de chocolate, conceptos clave de templado.</li>
                    <li><strong>Preparación y material</strong>: herramientas básicas y organización del espacio.</li>
                    <li><strong>Bombones paso a paso</strong>: moldeo, rellenos y errores frecuentes.</li>
                    <li><strong>Decoración y presentación</strong>: detalles finales y empaquetado.</li>
                </ol>

                <h2 class="text-base font-semibold text-[#2B1A22] mt-4 mb-1">Funciona así</h2>
                <ul class="text-sm text-[#5B4A54] leading-relaxed list-disc list-inside space-y-1 mb-6">
                    <li>Acceso al campus privado con lecciones en vídeo.</li>
                    <li>Progreso guardado para continuar donde lo dejaste.</li>
                    <li>Recetas y material extra dentro del campus.</li>
                </ul>

                <p class="text-sm text-[#5B4A54] leading-relaxed mb-4">
                    Si después de revisar este dossier sigues con dudas, puedes escribirme directamente desde la página
                    de contacto.
                </p>

                <div class="flex flex-col sm:flex-row gap-3">
                    <a href="#cta" class="inline-flex items-center justify-center rounded-full bg-[#F990B7] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                        Volver a la información del curso
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
