@extends('admin.layouts.app')

@section('title', 'Usuario · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Usuario</h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 mb-8 text-sm grid md:grid-cols-2 gap-4">
        <div>
            <p><span class="font-semibold">Nombre:</span> {{ $user->name }}</p>
            <p><span class="font-semibold">Email:</span> {{ $user->email }}</p>
            <p><span class="font-semibold">Teléfono:</span> {{ $user->phone ?? '—' }}</p>
            <p><span class="font-semibold">Rol:</span> {{ $user->role }}</p>
        </div>
        <div>
            <p><span class="font-semibold">Ciudad:</span> {{ $user->city ?? '—' }}</p>
            <p><span class="font-semibold">País:</span> {{ $user->country ?? '—' }}</p>
            <p><span class="font-semibold">Instagram:</span> {{ $user->instagram ?? '—' }}</p>
            @if($user->notes)
                <p class="mt-2"><span class="font-semibold">Notas:</span> {{ $user->notes }}</p>
            @endif
        </div>

        <div class="mt-4 flex gap-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-pink-600">Editar usuario</a>
            <a href="{{ route('admin.users.index') }}" class="text-xs text-slate-600 hover:underline">Volver al listado</a>
        </div>
    </div>

    <h2 class="text-lg font-semibold mb-3">Inscripciones</h2>
    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden text-sm">
        <table class="w-full">
            <thead class="bg-slate-50 border-b border-slate-200">
            <tr class="text-left">
                <th class="px-4 py-2">Curso</th>
                <th class="px-4 py-2">Estado</th>
                <th class="px-4 py-2">Pagado en</th>
            </tr>
            </thead>
            <tbody>
            @forelse ($enrollments as $enrollment)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-2">{{ $enrollment->course?->title ?? '—' }}</td>
                    <td class="px-4 py-2">{{ $enrollment->status }}</td>
                    <td class="px-4 py-2">{{ $enrollment->paid_at?->format('d/m/Y H:i') ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="px-4 py-4 text-center text-slate-500">Este usuario no tiene inscripciones.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
