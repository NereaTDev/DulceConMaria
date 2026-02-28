@extends('admin.layouts.app')

@section('title', 'Editar inscripción · Admin')

@section('content')
    <h1 class="text-2xl font-semibold mb-6">Editar inscripción</h1>

    <form action="{{ route('admin.enrollments.update', $enrollment) }}" method="POST" class="space-y-6 max-w-2xl text-sm">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Usuario</label>
            <select name="user_id" class="w-full border rounded px-3 py-2 text-sm" required>
                @foreach($users as $userOption)
                    <option value="{{ $userOption->id }}" @selected(old('user_id', $enrollment->user_id) == $userOption->id)>
                        {{ $userOption->name }} ({{ $userOption->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Curso</label>
            <select name="course_id" class="w-full border rounded px-3 py-2 text-sm" required>
                @foreach($courses as $courseOption)
                    <option value="{{ $courseOption->id }}" @selected(old('course_id', $enrollment->course_id) == $courseOption->id)>
                        {{ $courseOption->title }}
                    </option>
                @endforeach
            </select>
            @error('course_id') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Estado</label>
            <select name="status" class="w-full border rounded px-3 py-2 text-sm">
                <option value="paid" @selected(old('status', $enrollment->status) === 'paid')>paid</option>
                <option value="pending" @selected(old('status', $enrollment->status) === 'pending')>pending</option>
                <option value="cancelled" @selected(old('status', $enrollment->status) === 'cancelled')>cancelled</option>
            </select>
            @error('status') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="inline-flex items-center rounded-md bg-pink-500 px-4 py-2 text-sm font-semibold text-white hover:bg-pink-600">Guardar cambios</button>
            <a href="{{ route('admin.enrollments.index') }}" class="text-sm text-slate-600 hover:underline">Cancelar</a>
        </div>
    </form>
@endsection
