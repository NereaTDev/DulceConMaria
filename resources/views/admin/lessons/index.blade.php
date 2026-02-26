@extends('admin.layouts.app')

@section('title', 'Lecciones · Admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Lecciones</h1>
        <a href="{{ route('admin.lessons.create') }}" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-pink-600">
            Nueva lección
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
                <th class="px-4 py-2">Curso</th>
                <th class="px-4 py-2">Título</th>
                <th class="px-4 py-2">Orden</th>
                <th class="px-4 py-2">Preview</th>
                <th class="px-4 py-2"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($lessons as $lesson)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-2">{{ $lesson->course?->title ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $lesson->title }}</td>
                    <td class="px-4 py-2">{{ $lesson->order }}</td>
                    <td class="px-4 py-2">{{ $lesson->is_free_preview ? 'Sí' : 'No' }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.lessons.edit', $lesson) }}" class="text-xs text-pink-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.lessons.destroy', $lesson) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta lección?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-slate-500">Todavía no hay lecciones.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
