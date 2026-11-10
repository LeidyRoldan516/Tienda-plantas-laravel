{{-- Selector de idioma: español e inglés --}}
<div class="inline-flex items-center gap-1 rounded-full border border-marca-100 bg-white px-2 py-1 text-xs font-semibold">
    <a href="{{ route('idioma.cambiar', 'es') }}"
        aria-current="{{ app()->getLocale() === 'es' ? 'true' : 'false' }}"
        class="rounded-full px-2 py-1 transition
            {{ app()->getLocale() === 'es'
                ? 'bg-marca-600 text-white'
                : 'text-marca-700 hover:bg-marca-50' }}"
        aria-label="{{ __('messages.idioma_es') }}">
        ES
    </a>

    <span class="text-marca-200">|</span>

    <a href="{{ route('idioma.cambiar', 'en') }}"
        aria-current="{{ app()->getLocale() === 'en' ? 'true' : 'false' }}"
        class="rounded-full px-2 py-1 transition
            {{ app()->getLocale() === 'en'
                ? 'bg-marca-600 text-white'
                : 'text-marca-700 hover:bg-marca-50' }}"
        aria-label="{{ __('messages.idioma_en') }}">
        EN
    </a>
</div>
