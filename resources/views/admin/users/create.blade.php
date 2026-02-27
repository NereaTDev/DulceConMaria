@extends('admin.layouts.app')

@section('title', 'Nuevo usuario · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Nuevo usuario</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6 max-w-2xl">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Nombre</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2 text-sm" required>
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded px-3 py-2 text-sm" required>
                @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Teléfono</label>
                <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded px-3 py-2 text-sm" placeholder="+34 ...">
                @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Ciudad</label>
                <input type="text" name="city" value="{{ old('city') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('city') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">País</label>
                <input type="text" name="country" value="{{ old('country') }}" class="w-full border rounded px-3 py-2 text-sm">
                @error('country') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Instagram</label>
                <input type="text" name="instagram" value="{{ old('instagram') }}" class="w-full border rounded px-3 py-2 text-sm" placeholder="@dulceconmaria">
                @error('instagram') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Contraseña</label>
                <input type="password" name="password" class="w-full border rounded px-3 py-2 text-sm" required>
                @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Rol</label>
            <select name="role" class="w-full border rounded px-3 py-2 text-sm">
                <option value="user" @selected(old('role') === 'user')>user</option>
                <option value="admin" @selected(old('role') === 'admin')>admin</option>
            </select>
            @error('role') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="mt-2">
            <label class="inline-flex items-center gap-2 text-xs">
                <input type="checkbox" name="grant_all_courses" value="1" class="rounded border-slate-300 text-pink-500 focus:ring-pink-500">
                <span>Dar acceso a <strong>todos los cursos actuales</strong> (se crearán inscripciones como <code>paid</code>).</span>
            </label>
        </div>

        <div class="border-t border-slate-200 pt-4 mt-4">
            <h2 class="text-sm font-semibold mb-2">Asignar curso (opcional)</h2>
            <p class="text-xs text-slate-500 mb-3">Puedes dejar esta sección vacía si no quieres asignar ningún curso por ahora.</p>

            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium mb-1">Curso</label>
                    <select name="course_id" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="">-- Ninguno --</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium mb-1">Estado de la inscripción</label>
                    <select name="enrollment_status" class="w-full border rounded px-3 py-2 text-sm">
                        <option value="pending" @selected(old('enrollment_status') === 'pending')>pending</option>
                        <option value="paid" @selected(old('enrollment_status') === 'paid')>paid</option>
                        <option value="cancelled" @selected(old('enrollment_status') === 'cancelled')>cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600">Guardar usuario</button>
            <a href="{{ route('admin.users.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
        </div>
    </form>
@endsection
