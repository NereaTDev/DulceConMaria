<x-guest-layout>
    <div class="min-h-screen w-full flex items-center justify-center bg-[#FFF5FB] px-4">
        <div class="max-w-md w-full bg-white border border-[#F7D2E4] rounded-3xl shadow-sm p-6 md:p-8">
            <div class="flex flex-col items-center mb-6 text-center">
                <img src="/assets/Logo.png" alt="DulceConMaría" class="h-12 w-auto mb-3" />
                <p class="text-[11px] tracking-[0.18em] uppercase text-[#F990B7] mb-1">Acceso al campus</p>
                <h1 class="text-xl md:text-2xl font-semibold text-[#2B1A22]">Crea tu cuenta para entrar</h1>
                <p class="mt-1 text-xs text-[#7B6B75]">
                    Rellena estos datos para acceder al campus DulceConMaría y guardar tu progreso en los cursos.
                </p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data="{ submitting: false }" @submit="submitting = true">
                @csrf

                <!-- Name -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-[#5B4A54] mb-1">Nombre</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-xs" />
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#5B4A54] mb-1">Correo electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-[#5B4A54] mb-1">Contraseña</label>
                    <input id="password" type="password" name="password" required autocomplete="new-password"
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-[#5B4A54] mb-1">Repite la contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                           class="block w-full rounded-xl border border-[#F7D2E4] bg-[#FFF5FB] px-3 py-2 text-sm text-[#2B1A22] focus:outline-none focus:ring-2 focus:ring-[#F990B7] focus:border-[#F990B7]" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs" />
                </div>

                <div class="pt-2 flex flex-col gap-3 text-xs text-[#7B6B75]">
                    <button type="submit"
                            class="w-full inline-flex items-center justify-center rounded-full bg-[#F990B7] text-white px-4 py-2.5 text-sm font-semibold hover:bg-[#FF4B88] transition disabled:opacity-60 disabled:cursor-not-allowed"
                            :disabled="submitting">
                        Crear cuenta
                    </button>
                    <p class="text-center">
                        ¿Ya tienes una cuenta?
                        <a href="{{ route('login') }}" class="text-[#F990B7] hover:text-[#FF4B88]">Inicia sesión</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
