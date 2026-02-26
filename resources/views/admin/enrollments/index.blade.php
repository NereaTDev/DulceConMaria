@extends('admin.layouts.app')

@section('title', 'Inscripciones · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Inscripciones</h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden text-sm">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full whitespace-nowrap">

            <thead class="bg-slate-50 border-b border-slate-200">
            <tr class="text-left">
                <th class="px-4 py-2">Usuario</th>
                <th class="px-4 py-2">Curso</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Pagado en</th>
                <th class="px-4 py-2"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($enrollments as $enrollment)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-2">{{ $enrollment->user?->email ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $enrollment->course?->title ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $enrollment->status }}</td>
                    <td class="px-4 py-2">{{ $enrollment->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
                    <td class="px-4 py-2 text-right">
                        <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="text-xs text-pink-600 hover:underline">Ver</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-slate-500">Todavía no hay inscripciones.</td>
                </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    </div>
@endsection
