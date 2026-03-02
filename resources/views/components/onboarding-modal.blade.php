@props(['user'])

@if($user && $user->email_verified_at && ! $user->has_seen_onboarding && ! $user->dismissed_onboarding_at)
<div x-data="{ open: true }" x-show="open" x-cloak
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
            <form method="POST" action="{{ route('onboarding.complete') }}">
                @csrf
                <input type="hidden" name="action" value="view">
                <button type="submit" class="w-full inline-flex justify-center items-center rounded-full bg-[#F990B7] px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-[#F5387E] transition">
                    Ver tutorial del campus
                </button>
            </form>

            <form method="POST" action="{{ route('onboarding.complete') }}" class="mt-1">
                @csrf
                <input type="hidden" name="action" value="skip">
                <button type="submit" class="w-full text-[11px] text-[#7B6B75] hover:text-[#FF4B88] underline">
                    Omitir por ahora
                </button>
            </form>
        </div>
    </div>
</div>
@endif
