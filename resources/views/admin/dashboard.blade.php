@extends('admin.layouts.app')

@section('title', 'Dashboard · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Dashboard</h1>

    <div class="grid md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <p class="text-xs text-slate-500 mb-1">Cursos activos</p>
            <p class="text-2xl font-semibold">{{ $stats['active_courses'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <p class="text-xs text-slate-500 mb-1">Usuarios</p>
            <p class="text-2xl font-semibold">{{ $stats['users'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <p class="text-xs text-slate-500 mb-1">Inscripciones pagadas</p>
            <p class="text-2xl font-semibold">{{ $stats['paid_enrollments'] ?? 0 }}</p>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-slate-200 p-4">
            <p class="text-xs text-slate-500 mb-1">Recetas</p>
            <p class="text-2xl font-semibold">{{ $stats['recipes'] ?? 0 }}</p>
        </div>
    </div>
@endsection
