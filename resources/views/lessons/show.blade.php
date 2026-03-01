@extends('layouts.campus')

@section('title', $lesson->title . ' · ' . $course->title)

@section('content')
    <section class="py-10">
        <div class="max-w-5xl mx-auto px-4 space-y-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-[#FF4B88] mb-2">
                    {{ $course->title }}
                </p>
                <h1 class="text-2xl md:text-3xl font-semibold tracking-tight mb-2">
                    {{ $lesson->title }}
                </h1>
                @if($lesson->summary)
                    <p class="text-sm text-[#5B4A54] mb-4">{{ $lesson->summary }}</p>
                @endif
            </div>

            @if($lesson->embed_url)
                <div class="aspect-video w-full rounded-xl overflow-hidden border border-[#F7D2E4] bg-black mb-4">
                    <div
                        id="lesson-video-{{ $lesson->id }}"
                        class="w-full h-full"
                        data-lesson-id="{{ $lesson->id }}"
                        data-video-url="{{ $lesson->embed_url }}"
                    ></div>
                </div>
            @else
                <div class="h-40 rounded-xl border border-dashed border-[#F7D2E4] flex items-center justify-center mb-4 bg-[#FFF5FB]">
                    <p class="text-xs text-[#7B6B75]">Esta lección todavía no tiene vídeo asociado.</p>
                </div>
            @endif

            {{-- Navegación entre lecciones --}}
            <div class="flex justify-between items-center mt-2 text-xs">
                @if(isset($prevLesson) && $prevLesson)
                    <a href="{{ route('campus.lessons.show', $prevLesson) }}"
                       class="text-[#FF4B88] hover:text-[#FF306F]">
                        ← Lección anterior: {{ $prevLesson->title }}
                    </a>
                @else
                    <span class="text-slate-400">Inicio del curso</span>
                @endif

                @if(isset($nextLesson) && $nextLesson)
                    <a href="{{ route('campus.lessons.show', $nextLesson) }}"
                       class="text-[#FF4B88] hover:text-[#FF306F]">
                        Siguiente lección: {{ $nextLesson->title }} →
                    </a>
                @else
                    <span class="text-[#FF4B88] font-semibold">Finalizar curso</span>
                @endif
            </div>

            @if($lesson->recipes->count())
                <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-5 mt-4">
                    <h2 class="text-lg font-semibold text-[#2B1A22] mb-3">Recetas de esta clase</h2>
                    <div class="grid md:grid-cols-2 gap-4">
                        @foreach($lesson->recipes as $recipe)
                            <article class="border border-[#F7D2E4] rounded-xl p-3 text-sm text-[#5B4A54]">
                                <h3 class="font-semibold text-[#2B1A22] mb-1">{{ $recipe->title }}</h3>
                                @if(is_array($recipe->ingredients))
                                    <ul class="text-xs list-disc list-inside space-y-0.5 mb-2">
                                        @foreach($recipe->ingredients as $ingredient)
                                            <li>{{ $ingredient }}</li>
                                        @endforeach
                                    </ul>
                                @endif
                                <p class="text-xs text-[#7B6B75] line-clamp-3">{{ $recipe->description }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection
