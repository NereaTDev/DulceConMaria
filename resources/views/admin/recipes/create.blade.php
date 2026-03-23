@extends('admin.layouts.app')

@section('title', 'Nueva receta · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Nueva receta</h1>

    <form action="{{ route('admin.recipes.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
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

        <div
            x-data="{
                preview: null,
                onFile(e) { const f = e.target.files[0]; if (f) this.preview = URL.createObjectURL(f); }
            }"
        >
            <label class="block text-sm font-medium mb-1">Imagen de la receta (opcional)</label>
            <div class="flex items-start gap-4">
                <label class="cursor-pointer flex flex-col items-center justify-center w-40 h-32 border-2 border-dashed border-slate-300 rounded-lg hover:border-pink-400 transition bg-slate-50 overflow-hidden shrink-0">
                    <template x-if="!preview">
                        <div class="flex flex-col items-center gap-1 text-slate-400 text-xs text-center px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5V19a1 1 0 001 1h16a1 1 0 001-1v-2.5M16 10l-4-4m0 0L8 10m4-4v12"/></svg>
                            Subir imagen
                        </div>
                    </template>
                    <template x-if="preview">
                        <img :src="preview" class="w-full h-full object-cover" alt="Preview" />
                    </template>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="hidden" @change="onFile($event)">
                </label>
                <p class="text-xs text-slate-500 mt-1">JPG, PNG o WEBP · Máx. 2 MB<br>Se mostrará en el recetario público.</p>
            </div>
            @error('image')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
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
