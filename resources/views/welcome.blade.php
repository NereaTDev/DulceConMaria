@extends('layouts.app')

@section('title', 'Curso de chocolatería · DulceConMaría')

@section('content')
    {{-- HERO PRINCIPAL (adaptado de FirstSection) --}}
    <section id="hero" class="bg-white">
        <div class="max-w-5xl mx-auto px-4 py-16 md:py-20">
            <div class="grid md:grid-cols-2 items-center gap-6 md:gap-10">
                {{-- Imagen chocolate a la izquierda --}}
                <div class="flex justify-center md:justify-start">
                    <div class="relative w-full max-w-md">
                        <img src="/assets/choco.png" alt="Chocolate" class="relative left-4 md:left-10 w-[95%] rounded-[2rem]" />
                    </div>
                </div>

                {{-- Texto a la derecha --}}
                <div class="flex flex-col items-start md:px-12 gap-6 leading-relaxed">
                    <h4 class="font-semibold text-[18px] md:text-[24px] text-[#FF4B88]">Curso Online</h4>
                    <h1 class="font-black text-[#2B1A22] text-[28px] md:text-[40px] lg:text-[50px] leading-tight">
                        CHOCOLATERÍA
                    </h1>
                    <p class="text-[#5B4A54] text-[14px] md:text-[16px]">
                        ¡Descubre el mundo del chocolate con nuestro <strong>Curso Básico de Bombones de Chocolate</strong>!
                        Aprende las técnicas fundamentales, desde el templado perfecto hasta la creación de exquisitos
                        rellenos, todo desde la comodidad de tu hogar.
                        <span class="block mt-3 text-[#FF4B88] font-semibold">¡Inscríbete ahora y despierta tu creatividad dulce!</span>
                    </p>
                    <div class="mt-2 flex flex-col sm:flex-row gap-2">
                        <x-cta-button href="#cta">Descargar dossier</x-cta-button>
                        <x-cta-button href="{{ route('contact.show') }}" variant="secondary">Tengo dudas, quiero hablar</x-cta-button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CONTENIDO DEL CURSO: carrusel de unidades --}}
    <x-section id="cursos" title="Contenido del curso" subtitle="Un recorrido en varias unidades para dominar los bombones paso a paso.">
        <div class="overflow-x-auto pb-4">
            <div class="flex gap-4 md:gap-6 min-w-full snap-x snap-mandatory">
                @php
                    $units = [
                        [
                            'label' => 'Unidad 1',
                            'title' => 'Introducción',
                            'bg' => '#FFE0EB',
                            'text' => '#2B1A22',
                            'bullets' => [
                                'Tipos de chocolate y características.',
                                'Material básico y preparación del espacio de trabajo.',
                                'Conceptos clave de temperado.',
                            ],
                            'image' => '/assets/course/unit-1.png',
                        ],
                        [
                            'label' => 'Unidad 2',
                            'title' => 'Preparación',
                            'bg' => '#E0E5FF',
                            'text' => '#0F172A',
                            'bullets' => [
                                'Moldeo y desmolde de bombones.',
                                'Rellenos básicos y texturas.',
                                'Errores frecuentes y cómo corregirlos.',
                            ],
                            'image' => '/assets/course/unit-2.png',
                        ],
                        [
                            'label' => 'Unidad 3',
                            'title' => 'Montaje y presentación',
                            'bg' => '#FFF0E2',
                            'text' => '#2B1A22',
                            'bullets' => [
                                'Decoraciones sencillas con efecto profesional.',
                                'Empaquetado y conservación.',
                                'Ideas para regalar o vender tus bombones.',
                            ],
                            'image' => '/assets/course/unit-3.png',
                        ],
                        [
                            'label' => 'Unidad 4',
                            'title' => 'Bombones avanzados',
                            'bg' => '#D5E1FF',
                            'text' => '#0F172A',
                            'bullets' => [
                                'Saborizaciones avanzadas.',
                                'Decoraciones creativas.',
                                'Tips para producción en pequeña escala.',
                            ],
                            'image' => '/assets/course/unit-4.png',
                        ],
                    ];
                @endphp

                @foreach($units as $unit)
                    <article class="snap-start shrink-0 w-[85%] md:w-[420px] rounded-3xl p-6 md:p-8 flex flex-col md:grid md:grid-cols-[1.1fr,0.9fr] gap-4 items-center" style="background-color: {{ $unit['bg'] }}; color: {{ $unit['text'] }};">
                        <div class="w-full">
                            <p class="text-xs font-semibold mb-1" style="color: {{ $unit['text'] }}; opacity: 0.85;">{{ $unit['label'] }}</p>
                            <h3 class="text-lg md:text-xl font-semibold mb-3">{{ $unit['title'] }}</h3>
                            <ul class="text-xs md:text-sm space-y-1 list-disc list-inside opacity-90">
                                @foreach($unit['bullets'] as $bullet)
                                    <li>{{ $bullet }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="w-full flex items-center justify-end md:justify-center">
                            <div class="relative w-40 h-36 md:w-48 md:h-40">
                                <img src="{{ $unit['image'] }}" alt="{{ $unit['title'] }}" class="w-full h-full object-cover rounded-[2.5rem] shadow-md border border-white/40" />
                                <div class="absolute -right-4 top-1/2 -translate-y-1/2 hidden md:flex h-8 w-8 rounded-full bg-white/80 items-center justify-center text-[#2558D5] shadow">
                                    <span class="text-sm">»</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </x-section>

    {{-- BENEFICIOS DEL CURSO (adaptado de BenefitsCards) --}}
    <x-section id="beneficios" title="Beneficios del curso" subtitle="Más que recetas: una base sólida para seguir creando por tu cuenta.">
        <div class="flex flex-col gap-8 mt-4">
            <div class="px-6">
                <h2 class="text-xl font-semibold text-[#2558D5]">Beneficios del Curso</h2>
            </div>
            <div class="flex flex-wrap justify-center gap-10">
                @foreach ([1,2,3,4] as $i)
                    @php
                        $texts = [
                            'Aprenderás de forma práctica y didáctica, con explicaciones detalladas y ejemplos visuales.',
                            'Obtendrás acceso a un contenido exclusivo y de alta calidad, creado por expertos en chocolatería.',
                            'Podrás practicar desde la comodidad de tu hogar, adaptando el ritmo de aprendizaje a tu propia agenda.',
                            'Al finalizar el curso, estarás listo para sorprender a tus amigos y familiares con tus propios bombones artesanales.',
                        ];
                    @endphp
                    <article class="w-[270px] h-[350px] bg-[#F8E4EB] rounded-lg flex flex-col items-center justify-center px-6">
                        <div class="flex flex-col items-center gap-8">
                            <img src="/assets/cardsIcon{{ $i }}.png" alt="Beneficio {{ $i }}" class="w-1/3 mb-2" />
                            <h6 class="text-center text-[16px] font-semibold text-[#2558D5]">{{ $texts[$i-1] }}</h6>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </x-section>

    {{-- SECCIÓN OSCURA FINAL / CTA (adaptado de FixedBanner) --}}
    <section id="cta" class="relative w-full mt-8">
        <div class="w-full min-h-screen md:h-screen bg-cover bg-center" style="background-image: url('/assets/Rectangle 19.png')"></div>
        <div class="absolute inset-0 flex items-center justify-center">
            <div class="w-full max-w-4xl md:w-4/5 px-6">
                <div class="p-2 md:p-5">
                    <h2 class="text-white font-black text-[28px] md:text-[42px] mb-2 w-4/5">
                        ¡Conviértete en un experto chocolatero!
                    </h2>
                    <p class="text-white text-[16px] md:text-[22px] mt-3 w-11/12">
                        Inscríbete ahora en nuestro Curso Básico de Bombones de Chocolate y descubre el placer de crear tus propias obras maestras dulces.
                    </p>
                    <p class="text-[#f7b3cc] text-[16px] md:text-[22px] italic font-semibold mt-4 w-3/4">
                        Contáctanos hoy mismo para más información y reserva tu lugar.
                    </p>
                    <a href="{{ route('login') }}" class="mt-5 inline-block bg-[#f7b3cc] text-white text-[18px] md:text-[20px] font-semibold px-6 py-2 rounded-full cursor-pointer">
                        Inscribirme
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
