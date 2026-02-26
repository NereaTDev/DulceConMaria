@props([
    'menuId', // identificador (ej. 'main', 'campus')
    'title' => 'Menú',
    'links' => [], // [['label' => '', 'href' => '', 'variant' => 'normal|primary|danger', 'section' => 'top|bottom']]
    'panelClass' => 'bg-[#FFF5FB]', // permite cambiar color de fondo por layout
])

@php
    $backdropId = $menuId . '-menu-backdrop';
    $toggleId = $menuId . '-menu-toggle';
    $closeId = $menuId . '-menu-close';
    $containerId = $menuId . '-mobile-menu';
@endphp

{{-- Botón hamburguesa (se espera que se incluya en el layout con este id) --}}

{{-- Backdrop --}}
<div id="{{ $backdropId }}" class="md:hidden fixed inset-0 bg-black/40 z-[98] hidden"></div>

{{-- Panel lateral --}}
<div id="{{ $containerId }}" class="md:hidden fixed inset-y-0 right-0 w-64 max-w-[80%] border-l border-[#F7D2E4] shadow-xl transform translate-x-full transition-transform duration-200 ease-out z-[99] {{ $panelClass }}">
    @php
        $topLinks = collect($links)->filter(fn($l) => ($l['section'] ?? 'top') === 'top');
        $bottomLinks = collect($links)->filter(fn($l) => ($l['section'] ?? 'top') === 'bottom');
    @endphp
    <div class="h-full flex flex-col py-4 px-5 gap-4 text-sm text-[#5B4A54]">
        <div class="flex items-center justify-between mb-2">
            <button id="{{ $closeId }}" class="inline-flex items-center justify-center rounded-full border border-[#F7D2E4] p-1.5 text-[#F990B7] bg-white/80">
                <span class="sr-only">Cerrar menú</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>

        {{-- enlaces superiores --}}
        @foreach($topLinks as $link)
            @php
                $variant = $link['variant'] ?? 'normal';
                $method = $link['method'] ?? 'get';
                $classes = 'py-1 text-sm';
                if ($variant === 'primary') {
                    $classes .= ' text-white bg-[#F990B7] rounded-full px-4 py-2.5 text-center mt-2';
                } elseif ($variant === 'danger') {
                    $classes .= ' text-red-500';
                } else {
                    $classes .= ' text-[#5B4A54]';
                }
            @endphp
            @if(isset($link['href']))
                @if(strtolower($method) === 'post')
                    <form action="{{ $link['href'] }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="{{ $classes }} w-full text-left">
                            {{ $link['label'] }}
                        </button>
                    </form>
                @else
                    <a href="{{ $link['href'] }}" class="{{ $classes }}">
                        {{ $link['label'] }}
                    </a>
                @endif
            @else
                <span class="{{ $classes }} cursor-not-allowed opacity-60">{{ $link['label'] }}</span>
            @endif
        @endforeach

        {{-- enlaces inferiores (cross: web/campus/panel) --}}
        @if($bottomLinks->count())
            <div class="mt-auto pt-4 border-t border-[#F7D2E4]/60 flex flex-col gap-2">
                @foreach($bottomLinks as $link)
                    @php
                        $variant = $link['variant'] ?? 'normal';
                        $method = $link['method'] ?? 'get';
                        $classes = 'py-1 text-sm';
                        if ($variant === 'primary') {
                            $classes .= ' text-white bg-[#F990B7] rounded-full px-4 py-2.5 text-center mt-2';
                        } elseif ($variant === 'danger') {
                            $classes .= ' text-red-500';
                        } else {
                            $classes .= ' text-[#5B4A54]';
                        }
                    @endphp
                    @if(isset($link['href']))
                        @if(strtolower($method) === 'post')
                            <form action="{{ $link['href'] }}" method="POST" class="w-full">
                                @csrf
                                <button type="submit" class="{{ $classes }} w-full text-left">
                                    {{ $link['label'] }}
                                </button>
                            </form>
                        @else
                            <a href="{{ $link['href'] }}" class="{{ $classes }}">
                                {{ $link['label'] }}
                            </a>
                        @endif
                    @else
                        <span class="{{ $classes }} cursor-not-allowed opacity-60">{{ $link['label'] }}</span>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
</div>

{{-- Script de control (se ejecuta una vez renderizado el componente) --}}
<script>
    (function() {
        const toggle = document.getElementById(@json($toggleId));
        const menu = document.getElementById(@json($containerId));
        const closeBtn = document.getElementById(@json($closeId));
        const backdrop = document.getElementById(@json($backdropId));

        if (!menu) return;

        const open = () => {
            menu.classList.remove('translate-x-full');
            if (backdrop) backdrop.classList.remove('hidden');
        };
        const close = () => {
            menu.classList.add('translate-x-full');
            if (backdrop) backdrop.classList.add('hidden');
        };

        if (toggle) toggle.addEventListener('click', open);
        if (closeBtn) closeBtn.addEventListener('click', close);
        if (backdrop) backdrop.addEventListener('click', close);
    })();
</script>
