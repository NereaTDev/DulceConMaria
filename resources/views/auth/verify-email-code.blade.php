<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col justify-center items-center bg-[#FFF5FB] px-4">
        <div class="w-full max-w-md bg-white border border-[#F7D2E4] rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] px-8 py-8">
            <div class="flex justify-center mb-6">
                <img src="/assets/Logo.png" alt="DulceConMaría" class="h-10 w-auto">
            </div>

            <h1 class="text-center text-xl font-semibold text-[#2B1A22] mb-2">
                Introduce tu código de verificación
            </h1>

            <p class="text-sm text-[#5B4A54] leading-relaxed mb-4">
                Hemos enviado un código de <span class="font-semibold">6 dígitos</span> a tu correo electrónico.
                Escríbelo aquí para confirmar tu correo y acceder al campus.
            </p>

            @if (session('status') === 'email-verified')
                <div class="mb-4 text-xs font-medium text-green-600 bg-green-50 border border-green-100 rounded-2xl px-3 py-2">
                    ¡Tu correo ha sido verificado correctamente! Redirigiéndote al campus...
                </div>
            @endif

            <form method="POST" action="{{ route('verification.code.verify') }}" class="mt-4 space-y-4">
                @csrf

                <div>
                    <label for="code" class="block text-xs font-semibold text-[#7B6B75] mb-1">
                        Código de verificación
                    </label>
                    <input
                        id="code"
                        name="code"
                        type="text"
                        inputmode="numeric"
                        autocomplete="one-time-code"
                        class="block w-full rounded-full border border-[#F7D2E4] px-4 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-transparent"
                        placeholder="Ej. 123456"
                        required
                    >
                    @error('code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full inline-flex justify-center items-center rounded-full bg-[#F990B7] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                    Verificar código y entrar al campus
                </button>
            </form>

            <form method="POST" action="{{ route('verification.send') }}" class="mt-4">
                @csrf
                <button type="submit" class="w-full text-xs text-[#7B6B75] hover:text-[#FF4B88] underline">
                    Volver a enviar email de verificación
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf
                <button type="submit" class="w-full text-xs text-[#7B6B75] hover:text-[#FF4B88] underline">
                    Cerrar sesión y usar otro correo
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
