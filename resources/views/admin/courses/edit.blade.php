@extends('admin.layouts.app')

@section('title', 'Editar curso · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Editar curso</h1>

    <form action="{{ route('admin.courses.update', $course) }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Título</label>
            <input type="text" name="title" value="{{ old('title', $course->title) }}" class="w-full border rounded px-3 py-2 text-sm" required>
            @error('title') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Slug (opcional)</label>
            <input type="text" name="slug" value="{{ old('slug', $course->slug) }}" class="w-full border rounded px-3 py-2 text-sm">
            @error('slug') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Descripción corta</label>
            <textarea name="short_description" rows="2" class="w-full border rounded px-3 py-2 text-sm">{{ old('short_description', $course->short_description) }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Descripción larga</label>
            <textarea name="description" rows="5" class="w-full border rounded px-3 py-2 text-sm">{{ old('description', $course->description) }}</textarea>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Precio (€)</label>
                <input type="number" step="0.01" min="0" name="price_eur" value="{{ old('price_eur', $priceEur) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('price_eur') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Moneda</label>
                <input type="text" name="currency" value="{{ old('currency', $course->currency) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('currency') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Nivel</label>
                <select name="level" class="w-full border rounded px-3 py-2 text-sm">
                    <option value="beginner" @selected(old('level', $course->level) === 'beginner')>Inicial</option>
                    <option value="intermediate" @selected(old('level', $course->level) === 'intermediate')>Intermedio</option>
                    <option value="advanced" @selected(old('level', $course->level) === 'advanced')>Avanzado</option>
                </select>
                @error('level') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" id="is_active" class="border rounded" @checked(old('is_active', $course->is_active))>
            <label for="is_active" class="text-sm">Curso activo</label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600">Guardar cambios</button>
            <a href="{{ route('admin.courses.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
        </div>
    </form>
@endsection
