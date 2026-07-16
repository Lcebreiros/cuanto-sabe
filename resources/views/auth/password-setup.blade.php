<x-guest-layout>
    <form method="POST" action="{{ route('password.setup.store') }}" class="w-full max-w-md mx-auto space-y-8 p-8">
        @csrf

        @if (session('status'))
            <div class="text-center text-sm text-[#ffe47a] bg-[#3a2f00] border border-[#ffe47a55] rounded-lg py-3 px-4">
                {{ session('status') }}
            </div>
        @endif

        <div class="space-y-6">
            <!-- Nombre -->
            <div class="space-y-2">
                <x-input-label for="name" :value="__('Nombre')" class="text-lg" />
                <x-text-input
                    id="name"
                    type="text"
                    name="name"
                    :value="old('name', $name ?? '')"
                    required
                    autofocus
                    autocomplete="username"
                    class="w-full text-lg py-3 px-4"
                />
                <x-input-error :messages="$errors->get('name')" />
            </div>

            <!-- Nueva contraseña -->
            <div class="space-y-2">
                <x-input-label for="password" :value="__('Creá una contraseña')" class="text-lg" />
                <x-text-input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    class="w-full text-lg py-3 px-4"
                />
                <x-input-error :messages="$errors->get('password')" />
            </div>

            <!-- Repetir contraseña -->
            <div class="space-y-2">
                <x-input-label for="password_confirmation" :value="__('Repetí la contraseña')" class="text-lg" />
                <x-text-input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    class="w-full text-lg py-3 px-4"
                />
                <x-input-error :messages="$errors->get('password_confirmation')" />
            </div>
        </div>

        <div class="space-y-6 pt-4">
            <x-primary-button class="w-full justify-center py-4 text-lg font-semibold">
                {{ __('Crear contraseña e iniciar sesión') }}
            </x-primary-button>

            <div class="text-center">
                <a
                    href="{{ route('login') }}"
                    class="text-sm underline text-[#66cce6] hover:text-[#00f0ff] transition-colors duration-300"
                >
                    {{ __('Volver a iniciar sesión') }}
                </a>
            </div>
        </div>
    </form>

    <style>
        .space-y-8 > :not([hidden]) ~ :not([hidden]) {
            margin-top: 2rem;
        }

        .space-y-6 > :not([hidden]) ~ :not([hidden]) {
            margin-top: 1.5rem;
        }

        input[type="text"],
        input[type="password"] {
            font-size: 1rem !important;
            padding: 0.875rem 1rem !important;
            border-radius: 0.5rem !important;
        }

        .primary-button {
            background: linear-gradient(135deg, #00f0ff, #0099cc) !important;
            font-size: 1.1rem !important;
            padding: 1rem 2rem !important;
            border-radius: 0.5rem !important;
            box-shadow: 0 4px 15px rgba(0, 240, 255, 0.3) !important;
            transition: all 0.3s ease !important;
        }

        .primary-button:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 6px 20px rgba(0, 240, 255, 0.4) !important;
        }
    </style>
</x-guest-layout>
