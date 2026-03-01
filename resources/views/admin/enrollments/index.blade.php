@extends('admin.layouts.app')

@section('title', 'Inscripciones · Admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Inscripciones</h1>
        @if (
            Route::has('admin.enrollments.create') &&
            auth()->check() &&
            auth()->user()->role === 'admin' &&
            (bool) (auth()->user()->grant_all_courses ?? false)
        )
            <a href="{{ route('admin.enrollments.create') }}" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-pink-600">
                Nueva inscripción
            </a>
        @endif
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="mb-4 bg-white border border-slate-200 rounded-lg shadow-sm p-3 text-xs flex flex-wrap items-end gap-3">
        <form method="GET" action="{{ route('admin.enrollments.index') }}" class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-[11px] font-medium mb-1">Alumno</label>
                <select name="user_id" class="border rounded px-2 py-1 text-xs min-w-[180px]">
                    <option value="">Todos</option>
                    @foreach($users as $userOption)
                        <option value="{{ $userOption->id }}" @selected(request('user_id') == $userOption->id)>
                            {{ $userOption->name }} ({{ $userOption->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-medium mb-1">Curso</label>
                <select name="course_id" class="border rounded px-2 py-1 text-xs min-w-[180px]">
                    <option value="">Todos</option>
                    @foreach($courses as $courseOption)
                        <option value="{{ $courseOption->id }}" @selected(request('course_id') == $courseOption->id)>
                            {{ $courseOption->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex gap-2 items-center mt-4">
                <button type="submit" class="inline-flex items-center rounded-md bg-slate-800 px-3 py-1.5 text-xs font-semibold text-white hover:bg-slate-900">Filtrar</button>
                <a href="{{ route('admin.enrollments.index') }}" class="text-[11px] text-slate-500 hover:underline">Limpiar filtros</a>
            </div>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden text-sm">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr class="text-left">
                        <th class="px-4 py-2">Usuario</th>
                        <th class="px-4 py-2">Curso</th>
                        <th class="px-4 py-2">
                            @php
                                $nextDirStatus = ($sort === 'status' && $direction === 'asc') ? 'desc' : 'asc';
                                $statusArrow = $sort === 'status'
                                    ? ($direction === 'asc' ? '↑' : '↓')
                                    : '↕';
                            @endphp
                            <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['sort' => 'status', 'direction' => $nextDirStatus])) }}" class="inline-flex items-center gap-1 text-slate-700 hover:text-slate-900">
                                Estado
                                <span class="text-[10px] text-slate-400">{{ $statusArrow }}</span>
                            </a>
                        </th>
                        <th class="px-4 py-2">
                            @php
                                $nextDirDate = ($sort === 'paid_at' && $direction === 'asc') ? 'desc' : 'asc';
                                $dateArrow = $sort === 'paid_at'
                                    ? ($direction === 'asc' ? '↑' : '↓')
                                    : '↕';
                            @endphp
                            <a href="{{ route('admin.enrollments.index', array_merge(request()->query(), ['sort' => 'paid_at', 'direction' => $nextDirDate])) }}" class="inline-flex items-center gap-1 text-slate-700 hover:text-slate-900">
                                Pagado en
                                <span class="text-[10px] text-slate-400">{{ $dateArrow }}</span>
                            </a>
                        </th>
                        <th class="px-4 py-2 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr class="border-b border-slate-100">
                            <td class="px-4 py-2">
                                <a href="{{ route('admin.users.show', $enrollment->user) }}" class="text-pink-600 hover:underline">
                                    {{ $enrollment->user?->name ?? '—' }}
                                </a>
                            </td>
                            <td class="px-4 py-2">{{ $enrollment->course?->title ?? '—' }}</td>
                            <td class="px-4 py-2">{{ $enrollment->status }}</td>
                            <td class="px-4 py-2">{{ $enrollment->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
                            <td class="px-4 py-2 text-right space-x-2">
                                <a href="{{ route('admin.enrollments.show', $enrollment) }}" class="text-xs text-slate-600 hover:underline">Ver</a>
                                <a href="{{ route('admin.enrollments.edit', $enrollment) }}" class="text-xs text-pink-600 hover:underline">Editar</a>
                                <form action="{{ route('admin.enrollments.destroy', $enrollment) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar esta inscripción?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">
                                        Eliminar
                                    </button>
                                </form>
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
