@extends('layouts.app')

@section('title', 'Contacto · DulceConMaría')

@section('content')
    <section class="bg-[#FFF5FB] py-12 md:py-16">
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-white border border-[#F7D2E4] rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] p-6 md:p-8">
                <h1 class="text-2xl font-semibold text-[#2B1A22] mb-2">¿Tienes dudas antes de inscribirte?</h1>
                <p class="text-sm text-[#5B4A54] mb-6 leading-relaxed">
                    Cuéntame qué necesitas y te responderé lo antes posible para ayudarte a resolver tus dudas sobre el
                    curso de chocolatería, formas de pago o cualquier detalle del campus.
                </p>

                @if (session('status') === 'contact.sent')
                    <div class="mb-4 text-xs font-semibold text-green-700 bg-green-50 border border-green-100 rounded-2xl px-3 py-2">
                        ¡Mensaje enviado! Te responderé en cuanto lo revise.
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="name" class="block text-xs font-semibold text-[#7B6B75] mb-1">Nombre</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name') }}"
                            required
                            class="block w-full rounded-full border border-[#F7D2E4] px-4 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-transparent"
                        >
                        @error('name')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-semibold text-[#7B6B75] mb-1">Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email') }}"
                            required
                            class="block w-full rounded-full border border-[#F7D2E4] px-4 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-transparent"
                        >
                        @error('email')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-semibold text-[#7B6B75] mb-1">¿En qué puedo ayudarte?</label>
                        <textarea
                            id="message"
                            name="message"
                            rows="4"
                            class="block w-full rounded-3xl border border-[#F7D2E4] px-4 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-transparent"
                            placeholder="Cuéntame brevemente tu duda o situación"
                        >{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Honeypot anti-spam: campo oculto que los usuarios reales no ven --}}
                    <div class="hidden" aria-hidden="true">
                        <label for="website_hp" class="block text-xs mb-1">No rellenes este campo</label>
                        <input
                            id="website_hp"
                            name="website_hp"
                            type="text"
                            tabindex="-1"
                            autocomplete="off"
                            class="block w-full rounded-full border border-[#F7D2E4] px-4 py-2 text-sm text-[#2B1A22]"
                        >
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="inline-flex items-center justify-center rounded-full bg-[#F990B7] px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                            Enviar mensaje
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
