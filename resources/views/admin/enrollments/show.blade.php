@extends('admin.layouts.app')

@section('title', 'Inscripción · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-4">Inscripción</h1>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm p-4 mb-8 text-sm">
        <p><span class="font-semibold">Usuario:</span> {{ $enrollment->user?->email ?? '—' }}</p>
        <p><span class="font-semibold">Curso:</span> {{ $enrollment->course?->title ?? '—' }}</p>
        <p><span class="font-semibold">Estado actual:</span> {{ $enrollment->status }}</p>
        <p><span class="font-semibold">Pagado en:</span> {{ $enrollment->paid_at?->format('d/m/Y H:i') ?? '—' }}</p>

        <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST" class="mt-4 flex items-center gap-3">
            @csrf
            @method('PUT')
            <label class="text-sm font-medium">Cambiar estado:</label>
            <select name="status" class="border rounded px-2 py-1 text-sm">
                <option value="pending" @selected($enrollment->status === 'pending')>pending</option>
                <option value="paid" @selected($enrollment->status === 'paid')>paid</option>
                <option value="cancelled" @selected($enrollment->status === 'cancelled')>cancelled</option>
            </select>
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-pink-600">Guardar</button>
        </form>
    </div>
@endsection
