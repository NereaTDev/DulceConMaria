@php
    $hasStatus = session('status');
    $hasErrors = $errors->any();
@endphp

<div
    x-data="{
        show: {{ ($hasStatus || $hasErrors) ? 'true' : 'false' }},
        message: @js($hasStatus ?? ($hasErrors ? 'Hay errores en el formulario.' : '')),
        type: @js($hasErrors ? 'error' : 'success'),
        timeout: null,
    }"
    x-init="if (show) { timeout = setTimeout(() => show = false, 4000); }"
    x-show="show"
    x-transition
    class="hidden md:fixed md:bottom-4 md:right-4 md:z-50"
>
    <div
        class="rounded-md px-4 py-2 text-sm shadow-lg border"
        :class="type === 'success'
            ? 'bg-emerald-50 border-emerald-200 text-emerald-800'
            : 'bg-red-50 border-red-200 text-red-800'"
    >
        <span x-text="message"></span>
    </div>
</div>
