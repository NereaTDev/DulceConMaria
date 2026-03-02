@props(['user'])

@php
    $forceShow = request()->boolean('showOnboarding');
    $startMode = $forceShow ? 'full' : 'intro';
@endphp

@if($user && $user->email_verified_at && (
    (! $user->has_seen_onboarding && ! $user->dismissed_onboarding_at) || $forceShow
))
<div x-data="{ open: true, mode: '{{ $startMode }}' }" x-show="open" x-cloak
     class="fixed inset-0 z-40 flex items-center justify-center bg-black/40">
    <div class="bg-white w-full max-w-md mx-4 rounded-3xl border border-[#F7D2E4] shadow-[0_10px_30px_rgba(15,23,42,0.12)] p-6">
        <div class="flex justify-between items-start mb-3">
            <h2 class="text-lg font-semibold text-[#2B1A22]">
                Bienvenida al campus de DulceConMaría
            </h2>
            <button type="button" class="text-[#7B6B75] text-sm" @click="open = false">
                ×
            </button>
        </div>

        {{-- Modo introductorio corto --}}
        <div x-show="mode === 'intro'">
            <p class="text-xs text-[#5B4A54] mb-4 leading-relaxed">
                Te enseño en <strong>3 pasos</strong> cómo moverte por el campus: dónde está tu curso, cómo ver las lecciones
                y dónde encontrar las recetas extra.
            </p>

            <ul class="text-xs text-[#5B4A54] space-y-1 mb-5 list-disc list-inside">
                <li>Encuentra tu curso principal en la página del campus.</li>
                <li>Accede a las lecciones y deja que guardemos tu progreso.</li>
                <li>Explora las recetas y recursos extra cuando quieras practicar.</li>
            </ul>

            <div class="flex flex-col gap-2">
                {{-- Ver tutorial: solo cambia a modo "full" --}}
                <button type="button" @click="mode = 'full'"
                        class="w-full inline-flex justify-center items-center rounded-full bg-[#F990B7] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                    Ver tutorial del campus
                </button>

                {{-- Omitir por ahora: marca como omitido y recarga --}}
                <form method="POST" action="{{ route('onboarding.complete') }}" class="mt-1">
                    @csrf
                    <input type="hidden" name="action" value="skip">
                    <button type="submit" class="w-full text-[11px] text-[#7B6B75] hover:text-[#FF4B88] underline">
                        Omitir por ahora
                    </button>
                </form>
            </div>
        </div>

        {{-- Modo tutorial completo --}}
        <div x-show="mode === 'full'">
            <div class="text-xs text-[#5B4A54] leading-relaxed space-y-3 mb-4">
                <p>
                    <strong>1. Tu curso principal</strong><br>
                    Desde la página de inicio del campus verás un listado con tus cursos. Haz clic en tu curso principal
                    para ver todas las lecciones disponibles.
                </p>
                <p>
                    <strong>2. Lecciones y progreso</strong><br>
                    Dentro de cada lección encontrarás el vídeo principal y, cuando corresponda, material extra.
                    Tu progreso se irá guardando para que puedas continuar más adelante justo donde lo dejaste.
                </p>
                <p>
                    <strong>3. Recetas y material extra</strong><br>
                    En el menú superior tienes la sección de <strong>Recetas</strong>, donde encontrarás elaboraciones
                    adicionales relacionadas con el curso. Algunas son exclusivas del campus.
                </p>
                <p>
                    <strong>4. Tu perfil y soporte</strong><br>
                    Desde el menú de usuario puedes acceder a tu perfil para actualizar tus datos. Si tienes cualquier
                    problema con el acceso o con un vídeo, puedes contactar respondiendo a los correos del campus.
                </p>
            </div>

            <div class="flex flex-col gap-2">
                {{-- Marcar tutorial como visto y cerrar --}}
                <form method="POST" action="{{ route('onboarding.complete') }}" @submit="open = false">
                    @csrf
                    <input type="hidden" name="action" value="view">
                    <button type="submit" class="w-full inline-flex justify-center items-center rounded-full bg-[#F990B7] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                        He entendido el tutorial, empezar en el campus
                    </button>
                </form>

                <button type="button" class="w-full text-[11px] text-[#7B6B75] hover:text-[#FF4B88] underline"
                        @click="open = false">
                    Cerrar sin marcar como completado
                </button>
            </div>
        </div>
    </div>
</div>
@endif
