@extends('admin.layouts.app')

@section('title', 'Recetas · Admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Recetas</h1>
        <a href="{{ route('admin.recipes.create') }}" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-pink-600">
            Nueva receta
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-slate-50 border-b border-slate-200">
            <tr class="text-left">
                <th class="px-4 py-2">Título</th>
                <th class="px-4 py-2">Curso</th>
                <th class="px-4 py-2 text-center">Pública</th>
                <th class="px-4 py-2">Creada</th>
                <th class="px-4 py-2"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($recipes as $recipe)
                <tr class="border-b border-slate-100 hover:bg-slate-50">
                    <td class="px-4 py-2 font-medium">{{ $recipe->title }}</td>
                    <td class="px-4 py-2 text-slate-500">{{ $recipe->course?->title ?? '—' }}</td>
                    <td class="px-4 py-2 text-center">
                        <form action="{{ route('admin.recipes.toggle-public', $recipe) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                title="{{ $recipe->is_public ? 'Visible · click para ocultar' : 'Oculta · click para publicar' }}"
                                class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-medium border transition-colors
                                    {{ $recipe->is_public
                                        ? 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100'
                                        : 'bg-slate-100 text-slate-500 border-slate-200 hover:bg-slate-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full inline-block {{ $recipe->is_public ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                {{ $recipe->is_public ? 'Pública' : 'Oculta' }}
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-2 text-slate-500">{{ $recipe->created_at?->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.recipes.edit', $recipe) }}" class="text-xs text-pink-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar «{{ $recipe->title }}»?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-8 text-center text-slate-500">
                        Todavía no hay recetas.
                        <a href="{{ route('admin.recipes.create') }}" class="text-pink-500 hover:underline ml-1">Crea la primera</a>.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
