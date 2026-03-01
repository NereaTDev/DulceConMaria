<x-guest-layout>
    <div class="min-h-screen w-full flex items-center justify-center bg-[#FFF5FB] px-4">
        <div class="max-w-md w-full bg-white border border-[#F7D2E4] rounded-3xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col items-center mb-6 text-center">
                <img src="/assets/Logo.png" alt="DulceConMaría" class="h-12 w-auto mb-3" />
                <p class="text-[11px] tracking-[0.18em] uppercase text-[#F990B7] mb-1">Recuperar contraseña</p>
                <h1 class="text-xl md:text-2xl font-semibold text-[#2B1A22]">¿Has olvidado tu contraseña?</h1>
                <p class="mt-1 text-xs text-[#7B6B75]">
                    Escríbenos tu correo y te enviaremos un enlace para que puedas crear una nueva contraseña y volver al campus.
                </p>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs text-emerald-800">
                    <span class="font-semibold">¡Listo!</span>
                    <span class="ml-1">Te hemos enviado un correo con el enlace para restablecer tu contraseña. Revisa tu bandeja de entrada (y la carpeta de spam) para continuar.</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#5B4A54] mb-1">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <div class="pt-2 flex flex-col gap-3">
                    <button type="submit" class="w-full inline-flex items-center justify-center rounded-full bg-[#F990B7] text-white px-4 py-2.5 text-sm font-semibold hover:bg-[#FF4B88] transition">
                        Enviar enlace de recuperación
                    </button>
                    <a href="{{ route('login') }}" class="w-full text-center text-xs text-[#F990B7] hover:text-[#FF4B88]">
                        Volver a iniciar sesión
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
