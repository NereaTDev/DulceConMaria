@extends('admin.layouts.app')

@section('title', 'Nueva receta · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Nueva receta</h1>

    <form action="{{ route('admin.recipes.store') }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Curso asociado (opcional)</label>
            <select name="course_id" class="w-full border rounded px-3 py-2 text-sm">
                <option value="">Sin curso</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Título</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2 text-sm" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Ingredientes (uno por línea)</label>
            <textarea name="ingredients" rows="6" class="w-full border rounded px-3 py-2 text-sm" required>{{ old('ingredients') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Preparación</label>
            <textarea name="description" rows="8" class="w-full border rounded px-3 py-2 text-sm" required>{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Clases asociadas (opcional)</label>
            <select name="lesson_ids[]" multiple class="w-full border rounded px-3 py-2 text-sm h-32">
                @foreach ($lessons as $lesson)
                    <option value="{{ $lesson->id }}" @selected(collect(old('lesson_ids', []))->contains($lesson->id))>
                        {{ $lesson->title }} — {{ $lesson->course?->title }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-500 mt-1">Puedes seleccionar varias clases manteniendo pulsado Ctrl (Windows) o Cmd (Mac).</p>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_public" id="is_public" class="border rounded" @checked(old('is_public', false))>
            <label for="is_public" class="text-sm">Mostrar en el recetario público de la web</label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600">Guardar</button>
            <a href="{{ route('admin.recipes.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
        </div>
    </form>
@endsection
