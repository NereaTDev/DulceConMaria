@extends('layouts.app')

@section('title', $course->title . ' · DulceConMaría')

@section('content')
    <section class="bg-[#FFF5FB] pb-16">
        <div class="max-w-5xl mx-auto px-4 pt-24 md:pt-28">
            <h1 class="text-2xl md:text-3xl font-black text-[#2B1A22] mb-4">{{ $course->title }}</h1>
            @if($course->short_description)
                <p class="text-sm md:text-base text-[#5B4A54] mb-6">{{ $course->short_description }}</p>
            @endif

            @if (! $hasAccess)
                {{-- Vista pública / bloqueada --}}
                <div class="bg-white border border-[#F7D2E4] rounded-xl shadow-sm p-6 mb-10">
                    <h2 class="text-xl font-semibold mb-3">Contenido del curso</h2>
                    @if ($course->lessons->count())
                        <ul class="list-disc list-inside text-sm text-[#5B4A54] space-y-1 mb-4">
                            @foreach ($course->lessons as $lesson)
                                <li>{{ $lesson->title }}</li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-500 mb-4">Próximamente publicaremos el temario completo.</p>
                    @endif

                    <p class="text-sm text-[#5B4A54] mb-4">
                        Para ver las lecciones completas y los vídeos del curso, necesitas estar inscrito.
                    </p>

                    <div class="flex flex-wrap items-center gap-3">
                        @auth
                            <span class="text-xs text-slate-500">Estás conectado como {{ auth()->user()->email }}, pero aún no tienes acceso a este curso.</span>
                        @else
                            <a href="{{ route('login') }}" class="text-xs text-slate-600 underline">Inicia sesión o crea una cuenta para inscribirte.</a>
                        @endauth
                    </div>
                </div>
            @else
                {{-- Vista para alumnos con acceso --}}
                <div class="bg-white border border-[#F7D2E4] rounded-xl shadow-sm p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Lecciones</h2>
                    @if ($course->lessons->count())
                        <ol class="list-decimal list-inside space-y-2 text-sm text-[#5B4A54]">
                            @foreach ($course->lessons as $lesson)
                                <li>
                                    <div class="font-semibold">{{ $lesson->title }}</div>
                                    @if ($lesson->summary)
                                        <p class="text-xs text-slate-500">{{ $lesson->summary }}</p>
                                    @endif
                                    @if ($lesson->video_url)
                                        <p class="text-xs mt-1"><a href="{{ $lesson->video_url }}" target="_blank" class="text-[#FF4B88] underline">Ver vídeo</a></p>
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-sm text-slate-500">Aún no hay lecciones publicadas.</p>
                    @endif
                </div>

                @if ($course->recipes->count())
                    <div class="bg-white border border-[#F7D2E4] rounded-xl shadow-sm p-6 mb-8">
                        <h2 class="text-xl font-semibold mb-4">Recetas del curso</h2>
                        <ul class="list-disc list-inside text-sm text-[#5B4A54] space-y-1">
                            @foreach ($course->recipes as $recipe)
                                <li>{{ $recipe->title }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
