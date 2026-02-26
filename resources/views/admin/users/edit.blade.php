@extends('admin.layouts.app')

@section('title', 'Editar usuario · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Editar usuario</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2 text-sm" required>
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2 text-sm" required>
            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Contraseña (dejar vacío para no cambiarla)</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 text-sm">
                @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 text-sm">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Rol</label>
            <select name="role" class="w-full border rounded px-3 py-2 text-sm">
                <option value="user" @selected(old('role', $user->role) === 'user')>user</option>
                <option value="admin" @selected(old('role', $user->role) === 'admin')>admin</option>
            </select>
            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="border-t border-slate-200 pt-4 mt-2">
            <h2 class="text-sm font-semibold mb-2">Asignar curso (opcional)</h2>
            <p class="text-xs text-slate-500 mb-3">Puedes asignar cursos adicionales a este usuario desde aquí.</p>

            <form action="{{ route('admin.enrollments.store') }}" method="POST" class="flex flex-wrap items-end gap-3 text-sm">
                @csrf
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div>
                    <label class="block text-xs font-medium mb-1">Curso</label>
                    <select name="course_id" class="border rounded px-2 py-1 text-sm">
                        @foreach(\App\Models\Course::orderBy('title')->get() as $courseOption)
                            <option value="{{ $courseOption->id }}">{{ $courseOption->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-medium mb-1">Estado</label>
                    <select name="status" class="border rounded px-2 py-1 text-sm">
                        <option value="pending">pending</option>
                        <option value="paid">paid</option>
                        <option value="cancelled">cancelled</option>
                    </select>
                </div>

                <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-3 py-1.5 text-xs font-semibold text-white hover:bg-pink-600">
                    Asociar curso
                </button>
            </form>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600">Guardar cambios</button>
            <a href="{{ route('admin.users.show', $user) }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
        </div>
    </form>

    <h2 class="text-lg font-semibold mb-3 mt-8">Inscripciones</h2>
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
