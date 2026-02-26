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
                <th class="px-4 py-2">Creada</th>
                <th class="px-4 py-2"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($recipes as $recipe)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-2">{{ $recipe->title }}</td>
                    <td class="px-4 py-2">{{ $recipe->course?->title ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $recipe->created_at?->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.recipes.edit', $recipe) }}" class="text-xs text-pink-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.recipes.destroy', $recipe) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta receta?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-4 py-4 text-center text-slate-500">Todavía no hay recetas.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
