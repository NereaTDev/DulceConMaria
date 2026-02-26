@extends('admin.layouts.app')

@section('title', 'Usuarios · Admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Usuarios</h1>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-sm font-semibold text-white hover:bg-pink-600">
            Nuevo usuario
        </a>
    </div>

    @if (session('status'))
        <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
            {{ session('status') }}
        </div>
    @endif

    <div class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="w-full overflow-x-auto">
            <table class="min-w-full text-sm whitespace-nowrap">

            <thead class="bg-slate-50 border-b border-slate-200">
            <tr class="text-left">
                <th class="px-4 py-2">Nombre</th>
                <th class="px-4 py-2">Email</th>
                <th class="px-4 py-2">Rol</th>
                <th class="px-4 py-2">Alta</th>
                <th class="px-4 py-2"></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($users as $user)
                <tr class="border-b border-slate-100">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2">{{ $user->role }}</td>
                    <td class="px-4 py-2">{{ $user->created_at?->format('d/m/Y') }}</td>
                    <td class="px-4 py-2 text-right space-x-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="text-xs text-pink-600 hover:underline">Ver</a>
                        <a href="{{ route('admin.users.edit', $user) }}" class="text-xs text-pink-600 hover:underline">Editar</a>
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este usuario?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs text-red-600 hover:underline">Eliminar</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-4 py-4 text-center text-slate-500">Todavía no hay usuarios.</td>
                </tr>
            @endforelse
            </tbody>
            </table>
        </div>
    </div>
@endsection
