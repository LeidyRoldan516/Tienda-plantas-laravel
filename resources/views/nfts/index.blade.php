{{-- Autor: Codex --}}
<x-tienda-layout>
    <x-slot name="title">{{ __('messages.nfts_aliados') }} · {{ __('messages.marca_nombre') }}</x-slot>

    <div class="contenedor py-12">
        <div class="mb-10 max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Monoverse</p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900 sm:text-4xl">{{ __('messages.nfts_aliados') }}</h1>
            <p class="mt-2 text-marca-700/70">{{ __('messages.nfts_descripcion') }}</p>
        </div>

        @if ($error)
            <div role="alert" class="rounded-3xl border border-rose-200 bg-white px-6 py-12 text-center">
                <p class="text-marca-700">{{ $error }}</p>
                <a href="{{ route('nfts.index', ['page' => request()->integer('page') >= 1 && request()->integer('page') <= 1000000 ? request()->integer('page') : 1]) }}" class="btn-secundario mt-6">{{ __('messages.nfts_reintentar') }}</a>
            </div>
        @elseif (empty($nfts))
            <div class="rounded-3xl border border-dashed border-marca-200 bg-white px-6 py-16 text-center">
                <p class="text-marca-700/70">{{ __('messages.nfts_vacio') }}</p>
            </div>
        @else
            <div class="grid items-start gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($nfts as $nft)
                    <article class="overflow-hidden rounded-3xl border border-marca-100 bg-white">
                        @if ($nft['image_url'])
                            <div class="aspect-[4/5] overflow-hidden bg-marca-50">
                                <img src="{{ $nft['image_url'] }}" alt="{{ $nft['name'] ?? __('messages.nfts_sin_nombre') }}" loading="lazy" class="h-full w-full object-cover">
                            </div>
                        @endif
                        <div class="space-y-4 p-6">
                            <h2 class="text-xl font-semibold text-marca-900">{{ $nft['name'] ?: __('messages.nfts_sin_nombre') }}</h2>
                            <p class="text-sm leading-relaxed text-marca-700/80">{{ $nft['description'] ?: __('messages.nfts_sin_descripcion') }}</p>
                            <dl class="space-y-2 text-sm text-marca-700">
                                <div><dt class="font-semibold">{{ __('messages.nfts_coleccion') }}</dt><dd>{{ $nft['collection'] ?: __('messages.nfts_no_disponible') }}</dd></div>
                                <div><dt class="font-semibold">{{ __('messages.nfts_precio') }}</dt><dd>{{ $nft['price'] ?? __('messages.nfts_no_disponible') }}</dd></div>
                                <div><dt class="font-semibold">{{ __('messages.nfts_moneda') }}</dt><dd>{{ $nft['currency'] ?: __('messages.nfts_no_disponible') }}</dd></div>
                                <div><dt class="font-semibold">{{ __('messages.nfts_vendedor') }}</dt><dd>{{ $nft['seller'] ?: __('messages.nfts_no_disponible') }}</dd></div>
                            </dl>
                            @if ($nft['detail_url'])
                                <a href="{{ $nft['detail_url'] }}" target="_blank" rel="noopener noreferrer" class="btn-secundario">{{ __('messages.nfts_ver_detalle') }}</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        @if ($meta && $meta['last_page'] > 1)
            <nav aria-label="{{ __('messages.nfts_paginacion') }}" class="mt-10 flex flex-wrap items-center justify-center gap-4">
                @if ($meta['current_page'] > 1)
                    <a href="{{ route('nfts.index', ['page' => $meta['current_page'] - 1]) }}" rel="prev" class="btn-secundario">{{ __('messages.nfts_anterior') }}</a>
                @endif
                <span class="text-sm text-marca-700">{{ __('messages.nfts_pagina', ['actual' => $meta['current_page'], 'ultima' => $meta['last_page']]) }}</span>
                @if ($meta['current_page'] < $meta['last_page'])
                    <a href="{{ route('nfts.index', ['page' => $meta['current_page'] + 1]) }}" rel="next" class="btn-secundario">{{ __('messages.nfts_siguiente') }}</a>
                @endif
            </nav>
        @endif
    </div>
</x-tienda-layout>
