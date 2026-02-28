@extends('admin.layouts.app')

@section('title', 'Editar usuario · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Editar usuario</h1>

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-6 max-w-2xl" x-data="{ showPasswordModal: false }">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded px-3 py-2 text-sm" required>
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full border rounded px-3 py-2 text-sm" required>
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full border rounded px-3 py-2 text-sm" placeholder="+34 ...">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Ciudad</label>
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('city') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">País</label>
                <input type="text" name="country" value="{{ old('country', $user->country) }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('country') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram', $user->instagram) }}" class="w-full border rounded px-3 py-2 text-sm" placeholder="@usuario">
                @error('instagram') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Notas internas (solo admin)</label>
            <textarea name="notes" rows="3" class="w-full border rounded px-3 py-2 text-sm" placeholder="Información relevante sobre el alumno...">{{ old('notes', $user->notes) }}</textarea>
            @error('notes') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        {{-- Gestión de contraseña: campo "secreto" + modal para reset --}}
        <div class="grid md:grid-cols-[1.2fr,auto] gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Contraseña</label>
                <input type="password" value="••••••••" class="w-full border rounded px-3 py-2 text-sm bg-slate-50 text-slate-400 cursor-not-allowed" disabled>
                <p class="text-[11px] text-slate-500 mt-1">La contraseña actual no se muestra ni se edita directamente.</p>
            </div>
            <div class="flex justify-end md:justify-start">
                <button type="button" @click="showPasswordModal = true"
                        class="inline-flex items-center rounded-full border border-pink-400 px-3 py-1.5 text-xs font-semibold text-pink-600 hover:bg-pink-50">
                    Resetear contraseña
                </button>
            </div>
        </div>

        {{-- Modal de cambio de contraseña (mismo formulario) --}}
        <div x-show="showPasswordModal" x-cloak class="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-6 text-sm">
                <h3 class="text-base font-semibold mb-3">Cambiar contraseña</h3>
                <p class="text-xs text-slate-600 mb-4">La nueva contraseña se guardará cuando pulses "Guardar cambios" en el formulario del usuario.</p>

                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium mb-1">Nueva contraseña</label>
                        <input type="password" name="password" class="w-full border rounded px-3 py-2 text-sm" autocomplete="new-password">
                        @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-medium mb-1">Confirmar nueva contraseña</label>
                        <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 text-sm" autocomplete="new-password">
                    </div>
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <button type="button" @click="showPasswordModal = false" class="px-3 py-1.5 text-xs rounded-full border border-slate-300 text-slate-600 hover:bg-slate-50">Cancelar</button>
                    <button type="button" @click="showPasswordModal = false" class="px-3 py-1.5 text-xs rounded-full bg-pink-500 text-white hover:bg-pink-600">Listo</button>
                </div>
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

        <div class="mt-2">
            <label class="inline-flex items-center gap-2 text-xs">
                <input type="checkbox" name="grant_all_courses" value="1" class="rounded border-slate-300 text-pink-500 focus:ring-pink-500" @checked(old('grant_all_courses', $user->grant_all_courses))>
                <span>Dar acceso a <strong>todos los cursos actuales</strong> (se crearán inscripciones como <code>paid</code> para los que falten).</span>
            </label>
        </div>

                <div class="border-t border-slate-200 pt-4 mt-4">
            <h2 class="text-sm font-semibold mb-2">Cursos del usuario</h2>
            <p class="text-xs text-slate-500 mb-2">
                Las inscripciones se gestionan desde la sección <strong>Inscripciones</strong>.
            </p>

            <div class="text-xs text-slate-600 mb-4">
                <p class="font-semibold mb-1">Cursos actuales:</p>
                @if($enrollments->isEmpty())
                    <p class="text-slate-500">Este usuario todavía no tiene inscripciones.</p>
                @else
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($enrollments as $enrollment)
                            <li>
                                {{ $enrollment->course?->title ?? 'Curso eliminado' }}
                                <span class="text-slate-400">({{ $enrollment->status }})</span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            <a href="{{ route('admin.enrollments.index', ['user_id' => $user->id]) }}"
               class="inline-flex items-center rounded-full border border-pink-400 px-3 py-1.5 text-xs font-semibold text-pink-600 hover:bg-pink-50">
                Gestionar inscripciones
            </a>
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
