@extends('layouts.campus')

@section('title', 'Mi perfil · DulceConMaría')

@section('content')
    <div class="max-w-4xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-semibold text-[#2B1A22] mb-4">Mi perfil</h1>

        @if (session('status') === 'profile-updated')
            <div class="mb-4 text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-3 py-2">
                Perfil actualizado correctamente.
            </div>
        @endif

        <div class="bg-white border border-[#F7D2E4] rounded-2xl shadow-sm p-6 text-sm">
            {{-- Reutilizamos el formulario de Breeze pero dentro del layout del campus --}}
            @include('profile.partials.update-profile-information-form', ['user' => $user])

            <div class="mt-8 grid md:grid-cols-2 gap-6">
                @include('profile.partials.update-password-form')
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
@endsection
