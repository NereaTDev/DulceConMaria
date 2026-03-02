<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col justify-center items-center bg-[#FFF5FB] px-4">
        <div class="w-full max-w-md bg-white border border-[#F7D2E4] rounded-3xl shadow-[0_10px_30px_rgba(15,23,42,0.08)] px-8 py-8">
            <div class="flex justify-center mb-6">
                <img src="/assets/Logo.png" alt="DulceConMaría" class="h-10 w-auto">
            </div>

            <h1 class="text-center text-xl font-semibold text-[#2B1A22] mb-2">
                Confirma tu correo para entrar al campus
            </h1>

            <p class="text-sm text-[#5B4A54] leading-relaxed mb-4">
                Te hemos enviado un email a la dirección que has usado al registrarte. Dentro encontrarás un botón para
                <span class="font-semibold">confirmar tu correo</span> y acceder al campus de DulceConMaría.
            </p>

            <p class="text-xs text-[#7B6B75] leading-relaxed mb-6">
                Si no ves el correo en tu bandeja de entrada, revisa también la carpeta de <span class="font-semibold">spam</span>
                o "promociones". A veces se esconde por ahí.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mb-4 text-xs font-medium text-green-600 bg-green-50 border border-green-100 rounded-2xl px-3 py-2">
                    Hemos enviado un nuevo enlace de verificación a tu correo.
                </div>
            @endif

            <div class="mt-4 flex flex-col gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf

                    <button type="submit" class="w-full inline-flex justify-center items-center rounded-full bg-[#F990B7] px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                        Volver a enviar email de verificación
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf

                    <button type="submit" class="w-full text-xs text-[#7B6B75] hover:text-[#FF4B88] underline">
                        Cerrar sesión y usar otro correo
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
