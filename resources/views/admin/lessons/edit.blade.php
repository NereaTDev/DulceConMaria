@extends('admin.layouts.app')

@section('title', 'Editar lección · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Editar lección</h1>

    <form action="{{ route('admin.lessons.update', $lesson) }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Curso</label>
            <select name="course_id" class="w-full border rounded px-3 py-2 text-sm" required>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id', $lesson->course_id) == $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Título</label>
            <input type="text" name="title" value="{{ old('title', $lesson->title) }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Orden</label>
                <input type="number" name="order" value="{{ old('order', $lesson->order) }}" class="w-full border rounded px-3 py-2 text-sm" min="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">URL del vídeo</label>
                <input type="text" name="video_url" value="{{ old('video_url', $lesson->video_url) }}" class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Resumen</label>
            <textarea name="summary" rows="4" class="w-full border rounded px-3 py-2 text-sm">{{ old('summary', $lesson->summary) }}</textarea>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_free_preview" id="is_free_preview" class="border rounded" @checked(old('is_free_preview', $lesson->is_free_preview))>
            <label for="is_free_preview" class="text-sm">Mostrar como preview gratuita</label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600">Guardar cambios</button>
            <a href="{{ route('admin.lessons.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
        </div>
    </form>
@endsection
