@extends('layouts.campus')

@section('title', 'Mi campus · DulceConMaría')

@section('content')
    <section class="py-8 md:py-10">
        <div class="max-w-6xl mx-auto px-4 space-y-6">
            {{-- Bloque de bienvenida + curso actual --}}
            <div class="bg-white/80 border border-[#F7D2E4] rounded-2xl shadow-sm p-4 md:p-5 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-xl md:text-2xl font-semibold tracking-tight mb-1">
                        Hola, {{ $user->name ?? $user->email }}
                    </h1>
                    @if($currentCourse)
                        <p class="text-xs uppercase tracking-[0.18em] text-[#FF4B88] mb-1">Curso actual</p>
                        <p class="text-sm md:text-base font-semibold text-[#2B1A22] mb-1">
                            {{ $currentCourse->title }}
                        </p>
                        @php
                            $totalLessons = $currentCourse?->lessons?->count() ?? 0;
                            $completedLessons = $currentCourseProgress['completed'] ?? 0;
                            $progressPercent = $currentCourseProgress['percent'] ?? 0;
                        @endphp
                        @if($totalLessons > 0)
                            <div class="mt-2">
                                <div class="flex items-center justify-between text-[11px] text-[#7B6B75] mb-1">
                                    <span>Progreso</span>
                                    <span>{{ $completedLessons }}/{{ $totalLessons }} lecciones · {{ $progressPercent }}%</span>
                                </div>
                                <div class="h-1.5 rounded-full bg-[#F7D2E4]/60 overflow-hidden">
                                    <div class="h-full bg-[#FF4B88]" style="width: {{ $progressPercent }}%"></div>
                                </div>
                            </div>
                        @endif
                    @else
                        <p class="text-sm text-[#5B4A54]">
                            Todavía no tienes ningún curso activo. En cuanto te inscribas en uno, lo verás aquí.
                        </p>
                    @endif
                </div>

                <div class="flex flex-col items-stretch md:items-end gap-3">
                    @if($currentCourse && $previewLesson)
                        <a href="{{ route('campus.lessons.show', $previewLesson) }}" class="inline-flex items-center justify-center rounded-full bg-[#FF4B88] text-white px-4 py-2 text-sm font-semibold hover:bg-[#FF306F]">
                            Continuar curso
                        </a>
                    @elseif($currentCourse)
                        <a href="{{ route('courses.show', $currentCourse->slug) }}" class="inline-flex items-center justify-center rounded-full bg-[#FF4B88] text-white px-4 py-2 text-sm font-semibold hover:bg-[#FF306F]">
                            Ver curso
                        </a>
                    @else
                        <a href="{{ url('/') }}#hero" class="inline-flex items-center justify-center rounded-full bg-[#FF4B88] text-white px-4 py-2 text-sm font-semibold hover:bg-[#FF306F]">
                            Ver cursos disponibles
                        </a>
                    @endif

                    @if($currentCourse && $courses->count() > 1)
                        <div class="flex flex-wrap gap-1.5 justify-end">
                            @foreach($courses as $course)
                                <a href="{{ route('courses.show', $course->slug) }}" class="text-[11px] px-2.5 py-1 rounded-full border {{ $course->id === $currentCourse->id ? 'border-[#FF4B88] text-[#FF4B88]' : 'border-[#F7D2E4] text-[#5B4A54]' }}">
                                    {{ $course->title }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            {{-- Zona principal: vídeo + playlist / info lateral --}}
            <div class="grid gap-8 md:grid-cols-[1.7fr,1fr]">
                <div class="space-y-4">
                    @if($currentCourse)
                        {{-- Vídeo de la lección destacada --}}
                        @if($previewLesson && $previewLesson->embed_url)
                            <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-2 md:p-3">
                                <a href="{{ route('campus.lessons.show', $previewLesson) }}" class="block group">
                                    <div class="aspect-video w-full rounded-xl overflow-hidden border border-[#F7D2E4] bg-black flex items-center justify-center relative">
                                        @if($previewLesson->thumbnail_url)
                                            <img src="{{ $previewLesson->thumbnail_url }}" alt="Previsualización de {{ $previewLesson->title }}" class="absolute inset-0 w-full h-full object-cover" />
                                        @endif
                                        <div class="absolute inset-0 bg-black/35 group-hover:bg-black/25 transition"></div>
                                        <div class="relative flex items-center justify-center">
                                            <div class="h-14 w-14 rounded-full bg-white/90 flex items-center justify-center text-[#FF4B88] shadow-lg">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M6.5 5.5v9l7-4.5-7-4.5z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <p class="text-[11px] uppercase tracking-[0.18em] text-[#FF4B88] mb-1">Lección destacada</p>
                                        <p class="text-sm font-semibold text-[#2B1A22] group-hover:text-[#FF4B88] transition">{{ $previewLesson->title }}</p>
                                        <p class="text-xs text-[#7B6B75] mt-1">Toca para ir a la clase y ver el vídeo.</p>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="bg-white border border-dashed border-[#F7D2E4] rounded-2xl shadow-sm h-40 flex items-center justify-center">
                                <p class="text-xs text-[#7B6B75] text-center px-4">Pronto tendrás aquí el vídeo de tu primera lección.</p>
                            </div>
                        @endif

                        {{-- Playlist de lecciones --}}
                        @if($currentCourse->lessons->count())
                            <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h2 class="text-sm font-semibold text-[#2B1A22]">Lecciones del curso</h2>
                                        <p class="text-[11px] text-[#7B6B75]">{{ $currentCourse->lessons->count() }} lecciones · Recetas: {{ $currentCourse->recipes->count() }}</p>
                                    </div>
                                </div>

                                <ul class="divide-y divide-[#F7D2E4]/60">
                                    @foreach($currentCourse->lessons->sortBy('order') as $index => $lesson)
                                        <li>
                                            <a href="{{ route('campus.lessons.show', $lesson) }}" class="flex items-center justify-between py-2.5 gap-3">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-7 w-7 flex items-center justify-center rounded-full border border-[#F7D2E4] text-[11px] text-[#7B6B75]">
                                                        {{ $index + 1 }}
                                                    </div>
                                                    <div>
                                                        <p class="text-xs md:text-sm text-[#2B1A22]">{{ $lesson->title }}</p>
                                                    </div>
                                                </div>
                                                <span class="text-[11px] text-[#FF4B88]">Ver</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @else
                        {{-- Usuario sin cursos activos --}}
                        <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-6">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#FF4B88] mb-2">Aún no estás inscrita</p>
                            <h2 class="text-lg md:text-xl font-semibold text-[#2B1A22] mb-2">Inscríbete en nuestros cursos de chocolatería</h2>
                            <p class="text-sm text-[#5B4A54] mb-4">
                                Todavía no tienes ningún curso activo en tu cuenta. Vuelve a la página principal para ver toda la
                                información del programa y reservar tu plaza.
                            </p>
                            <a href="{{ url('/') }}#hero" class="inline-flex items-center rounded-full bg-[#FF4B88] text-white px-4 py-1.5 text-xs font-semibold hover:bg-[#FF306F]">
                                Ver información del curso
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Panel lateral --}}
                <aside class="space-y-4 md:space-y-4 md:mt-0 mt-4">
                    @if($currentCourse)
                        <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-4">
                            <h3 class="text-sm font-semibold text-[#2B1A22] mb-2">Resumen del curso</h3>
                            <p class="text-xs text-[#5B4A54] mb-1">Nivel: <span class="font-semibold">{{ ucfirst($currentCourse->level ?? 'básico') }}</span></p>
                            <p class="text-xs text-[#5B4A54] mb-1">Lecciones: {{ $currentCourse->lessons->count() }}</p>
                            <p class="text-xs text-[#5B4A54]">Recetas: {{ $currentCourse->recipes->count() }}</p>
                        </div>
                    @endif

                    <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-4">
                        <h3 class="text-sm font-semibold text-[#2B1A22] mb-2">Accesos rápidos</h3>
                        <ul class="text-xs text-[#5B4A54] space-y-1">
                            <li><span class="opacity-60">·</span> Próximamente: recetario completo del campus.</li>
                            <li><span class="opacity-60">·</span> Próximamente: materiales descargables.</li>
                            <li><span class="opacity-60">·</span> Próximamente: calendario de clases en directo.</li>
                        </ul>
                    </div>

                    <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-4">
                        <h3 class="text-sm font-semibold text-[#2B1A22] mb-2">Estado de tu cuenta</h3>
                        <p class="text-xs text-[#5B4A54] mb-1">Email: <span class="font-mono text-[#2B1A22]">{{ $user->email }}</span></p>
                        <p class="text-xs text-[#5B4A54]">Rol: <span class="font-semibold">{{ $user->role }}</span></p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endsection
