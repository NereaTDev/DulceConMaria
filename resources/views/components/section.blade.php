@props(['id', 'title', 'subtitle' => null])

<section id="{{ $id }}" {{ $attributes->merge(['class' => 'py-16 md:py-24']) }}>
    <div class="max-w-5xl mx-auto px-4">
        <div class="max-w-2xl mb-8">
            <h2 class="text-2xl md:text-3xl font-semibold tracking-tight text-slate-900 mb-2">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-slate-600 text-sm md:text-base">{{ $subtitle }}</p>
            @endif
        </div>
        {{ $slot }}
    </div>
</section>
