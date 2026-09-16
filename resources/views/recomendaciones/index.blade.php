{{-- Autor: Simon Martinez Gomez --}}
<x-tienda-layout>
    <x-slot name="title">{{ __('messages.recomendaciones') }} · El Rincón de las Plantas</x-slot>

    <div class="contenedor py-12">
        <div class="mb-10 max-w-2xl">
            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">
                {{ __('messages.recomendaciones_etiqueta') }}
            </p>
            <h1 class="mt-2 text-3xl font-semibold text-marca-900">
                {{ __('messages.recomendaciones_titulo') }}
            </h1>
            <p class="mt-2 text-marca-700/70">
                {{ __('messages.recomendaciones_descripcion') }}
            </p>
        </div>

        <div class="grid gap-10 lg:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
            <section class="rounded-3xl border border-marca-100 bg-white p-6 sm:p-8">
                <h2 class="text-xl font-semibold text-marca-900">
                    {{ __('messages.preferencias_titulo') }}
                </h2>
                <p class="mt-2 text-sm text-marca-700/70">
                    {{ __('messages.preferencias_ayuda') }}
                </p>

                <form action="{{ route('recomendaciones.preferencias') }}" method="POST" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label for="experiencia" class="mb-1 block text-sm font-medium text-marca-800">
                            {{ __('messages.preferencias_experiencia') }}
                        </label>
                        <select id="experiencia" name="experiencia" class="campo" required>
                            <option value="">{{ __('messages.preferencias_selecciona') }}</option>
                            @foreach ([
                                'principiante' => __('messages.preferencias_experiencia_principiante'),
                                'intermedio' => __('messages.preferencias_experiencia_intermedio'),
                                'avanzado' => __('messages.preferencias_experiencia_avanzado'),
                            ] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(old('experiencia', $perfil?->experiencia) === $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="espacio" class="mb-1 block text-sm font-medium text-marca-800">
                            {{ __('messages.preferencias_espacio') }}
                        </label>
                        <select id="espacio" name="espacio" class="campo" required>
                            <option value="">{{ __('messages.preferencias_selecciona') }}</option>
                            @foreach ([
                                'balcon' => __('messages.preferencias_espacio_balcon'),
                                'interior_pequeno' => __('messages.preferencias_espacio_interior_pequeno'),
                                'interior_amplio' => __('messages.preferencias_espacio_interior_amplio'),
                                'jardin' => __('messages.preferencias_espacio_jardin'),
                                'oficina' => __('messages.preferencias_espacio_oficina'),
                            ] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(old('espacio', $perfil?->espacio) === $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="iluminacion" class="mb-1 block text-sm font-medium text-marca-800">
                            {{ __('messages.preferencias_iluminacion') }}
                        </label>
                        <select id="iluminacion" name="iluminacion" class="campo" required>
                            <option value="">{{ __('messages.preferencias_selecciona') }}</option>
                            @foreach ([
                                'baja' => __('messages.preferencias_iluminacion_baja'),
                                'media' => __('messages.preferencias_iluminacion_media'),
                                'alta' => __('messages.preferencias_iluminacion_alta'),
                            ] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(old('iluminacion', $perfil?->iluminacion) === $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="tiempo_cuidado" class="mb-1 block text-sm font-medium text-marca-800">
                            {{ __('messages.preferencias_tiempo') }}
                        </label>
                        <select id="tiempo_cuidado" name="tiempo_cuidado" class="campo" required>
                            <option value="">{{ __('messages.preferencias_selecciona') }}</option>
                            @foreach ([
                                'bajo' => __('messages.preferencias_tiempo_bajo'),
                                'medio' => __('messages.preferencias_tiempo_medio'),
                                'alto' => __('messages.preferencias_tiempo_alto'),
                            ] as $valor => $etiqueta)
                                <option value="{{ $valor }}" @selected(old('tiempo_cuidado', $perfil?->tiempo_cuidado) === $valor)>
                                    {{ $etiqueta }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="mascotas" class="mb-1 block text-sm font-medium text-marca-800">
                            {{ __('messages.preferencias_mascotas') }}
                        </label>
                        @php
                            $mascotasActual = old('mascotas', $perfil?->mascotas);
                            $mascotasValor = match (true) {
                                $mascotasActual === true, $mascotasActual === 1, $mascotasActual === '1' => '1',
                                $mascotasActual === false, $mascotasActual === 0, $mascotasActual === '0' => '0',
                                default => '',
                            };
                        @endphp
                        <select id="mascotas" name="mascotas" class="campo" required>
                            <option value="">{{ __('messages.preferencias_selecciona') }}</option>
                            <option value="1" @selected($mascotasValor === '1')>{{ __('messages.si') }}</option>
                            <option value="0" @selected($mascotasValor === '0')>{{ __('messages.no') }}</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-primario">
                        {{ __('messages.preferencias_guardar') }}
                    </button>
                </form>
            </section>

            <aside class="space-y-6">
                <section class="rounded-3xl border border-marca-100 bg-marca-50/70 p-6 sm:p-8">
                    <h2 class="text-xl font-semibold text-marca-900">
                        {{ __('messages.recomendaciones_generar_titulo') }}
                    </h2>
                    <p class="mt-2 text-sm text-marca-700/70">
                        {{ __('messages.recomendaciones_generar_ayuda') }}
                    </p>

                    @if ($perfil?->estaCompleto())
                        <form action="{{ route('recomendaciones.generar') }}" method="POST" class="mt-6">
                            @csrf
                            <button type="submit" class="btn-primario">
                                {{ __('messages.recomendaciones_generar') }}
                            </button>
                        </form>
                    @else
                        <p class="mt-6 rounded-2xl border border-marca-200 bg-white px-4 py-3 text-sm text-marca-700">
                            {{ __('messages.preferencias_requeridas') }}
                        </p>
                    @endif
                </section>

                <section class="rounded-3xl border border-marca-100 bg-white p-6 sm:p-8">
                    <h2 class="text-xl font-semibold text-marca-900">
                        {{ __('messages.recomendaciones_historial') }}
                    </h2>

                    @forelse ($recomendaciones as $item)
                        <a href="{{ route('recomendaciones.show', $item) }}"
                            class="mt-4 block rounded-2xl border border-marca-100 px-4 py-3 transition hover:border-marca-300">
                            <p class="text-sm font-medium text-marca-900">
                                {{ $item->created_at->format('d/m/Y H:i') }}
                            </p>
                            <p class="mt-1 line-clamp-2 text-sm text-marca-700/70">
                                {{ $item->explicacion }}
                            </p>
                        </a>
                    @empty
                        <p class="mt-4 text-sm text-marca-700/70">
                            {{ __('messages.recomendaciones_historial_vacio') }}
                        </p>
                    @endforelse
                </section>
            </aside>
        </div>
    </div>
</x-tienda-layout>
