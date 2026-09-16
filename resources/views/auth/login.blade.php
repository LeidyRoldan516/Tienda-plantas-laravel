{{-- Autor: Simon Martinez Gomez --}}
<x-tienda-layout>
    <x-slot name="title">Iniciar sesión · El Rincón de las Plantas</x-slot>

    <div class="contenedor flex justify-center py-16">
        <div class="w-full max-w-md">
            <div class="mb-8 text-center">
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-marca-300">Cuenta</p>
                <h1 class="mt-2 text-3xl font-semibold text-marca-900">Iniciar sesión</h1>
                <p class="mt-2 text-sm text-marca-700/70">Accede para comprar y seguir tus pedidos.</p>
            </div>

            <div class="rounded-3xl border border-marca-100 bg-white px-6 py-8 shadow-suave sm:px-8">
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <x-input-label for="email" :value="__('messages.correo')" class="text-marca-800" />
                        <x-text-input id="email" class="campo mt-1 block w-full" type="email" name="email"
                            :value="old('email')" required autofocus autocomplete="username" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" :value="__('messages.contrasena')" class="text-marca-800" />
                        <x-text-input id="password" class="campo mt-1 block w-full" type="password" name="password"
                            required autocomplete="current-password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div>
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-marca-300 text-marca-500 shadow-sm focus:ring-marca-500"
                                name="remember">
                            <span class="ms-2 text-sm text-marca-700">{{ __('messages.recordarme') }}</span>
                        </label>
                    </div>

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        @if (Route::has('password.request'))
                            <a class="text-sm font-medium text-marca-500 transition hover:text-marca-700"
                                href="{{ route('password.request') }}">
                                {{ __('messages.olvido_contrasena') }}
                            </a>
                        @endif

                        <x-primary-button class="w-full justify-center sm:w-auto">
                            {{ __('messages.iniciar_sesion') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            @if (Route::has('register'))
                <p class="mt-6 text-center text-sm text-marca-700/70">
                    ¿No tienes cuenta?
                    <a href="{{ route('register') }}" class="font-semibold text-marca-500 hover:text-marca-700">
                        Regístrate
                    </a>
                </p>
            @endif
        </div>
    </div>
</x-tienda-layout>
