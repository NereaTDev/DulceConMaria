<x-guest-layout>
    <div class="min-h-screen w-full flex flex-col items-center justify-center bg-[#FFF5FB] px-4">
        <div class="w-full max-w-md mb-4 flex justify-start">
            <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs text-[#7B6B75] hover:text-[#FF4B88]">
                <span class="inline-block h-5 w-5 rounded-full border border-[#F7D2E4] flex items-center justify-center text-[11px]">←</span>
                <span>Volver</span>
            </a>
        </div>
        <div class="max-w-md w-full bg-white border border-[#F7D2E4] rounded-3xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col items-center mb-6 text-center">
                <img src="/assets/Logo.png" alt="DulceConMaría" class="h-12 w-auto mb-3" />
                <p class="text-[11px] tracking-[0.18em] uppercase text-[#F990B7] mb-1">Acceso al campus</p>
                <h1 class="text-xl md:text-2xl font-semibold text-[#2B1A22]">Inicia sesión en tu cuenta</h1>
                <p class="mt-1 text-xs text-[#7B6B75]">Introduce tu correo y contraseña para acceder al campus DulceConMaría.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-4" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#5B4A54] mb-1">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-[#5B4A54] mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <!-- Remember Me + Forgot -->
                <div class="flex items-center gap-4 text-xs text-[#7B6B75]">
                    <label for="remember_me" class="inline-flex items-center gap-2">
                        <input id="remember_me" type="checkbox" class="rounded border-[#F7D2E4] text-[#F990B7] focus:ring-[#F990B7]" name="remember">
                        <span>Recordarme</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-[#F990B7] hover:text-[#FF4B88]" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-full bg-[#F990B7] text-white px-4 py-2.5 text-sm font-semibold hover:bg-[#FF4B88] transition disabled:opacity-60 disabled:cursor-not-allowed"
                            :disabled="submitting">
                        Entrar al campus
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
