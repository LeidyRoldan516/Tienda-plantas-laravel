
{{-- Autor original: Simon Martinez Gomez --}}
{{-- Mejoras de usabilidad: David --}}

<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Nombre --}}
        <div>
            <x-input-label for="name" :value="__('messages.nombre')" />

            <x-text-input
                id="name"
                class="block mt-1 w-full"
                type="text"
                name="name"
                :value="old('name')"
                required
                autofocus
                autocomplete="name"
            />

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Correo electrónico --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('messages.correo')" />

            <x-text-input
                id="email"
                class="block mt-1 w-full"
                type="email"
                name="email"
                :value="old('email')"
                required
                autocomplete="username"
            />

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Contraseña --}}
        <div class="mt-4">
            <x-input-label
                for="password"
                :value="__('messages.contrasena')"
            />

            <div class="relative mt-1">
                <x-text-input
                    id="password"
                    class="block w-full pr-24"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                />

                <button
                    type="button"
                    aria-controls="password"
                    aria-pressed="false"
                    onclick="
                        const campo = document.getElementById('password');
                        const visible = campo.type === 'password';
                        campo.type = visible ? 'text' : 'password';
                        this.textContent = visible
                            ? @js(__('messages.ocultar'))
                            : @js(__('messages.mostrar'));
                        this.setAttribute('aria-pressed', String(visible));
                    "
                    class="absolute inset-y-0 right-3 text-sm font-medium text-marca-700 hover:underline focus:outline-none focus:ring-2 focus:ring-marca-500 rounded"
                >
                    {{ __('messages.mostrar') }}
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmar contraseña --}}
        <div class="mt-4">
            <x-input-label
                for="password_confirmation"
                :value="__('messages.confirmar_contrasena')"
            />

            <div class="relative mt-1">
                <x-text-input
                    id="password_confirmation"
                    class="block w-full pr-24"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                />

                <button
                    type="button"
                    aria-controls="password_confirmation"
                    aria-pressed="false"
                    onclick="
                        const campo = document.getElementById('password_confirmation');
                        const visible = campo.type === 'password';
                        campo.type = visible ? 'text' : 'password';
                        this.textContent = visible
                            ? @js(__('messages.ocultar'))
                            : @js(__('messages.mostrar'));
                        this.setAttribute('aria-pressed', String(visible));
                    "
                    class="absolute inset-y-0 right-3 text-sm font-medium text-marca-700 hover:underline focus:outline-none focus:ring-2 focus:ring-marca-500 rounded"
                >
                    {{ __('messages.mostrar') }}
                </button>
            </div>

            <x-input-error
                :messages="$errors->get('password_confirmation')"
                class="mt-2"
            />
        </div>

        {{-- Acciones --}}
        <div class="flex items-center justify-end mt-4">
            <a
                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                href="{{ route('login') }}"
            >
                {{ __('messages.ya_registrado') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('messages.crear_cuenta') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
