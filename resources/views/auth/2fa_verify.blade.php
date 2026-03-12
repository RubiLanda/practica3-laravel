<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 text-center">
        {{ __('Ingresa el código de 6 dígitos de tu aplicación Google Authenticator.') }}
    </div>

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf
        <div>
            <x-input-label for="one_time_password" :value="__('Código de Seguridad')" />
            <x-text-input id="one_time_password" class="block mt-1 w-full" type="text" name="one_time_password" required autofocus placeholder="000000" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '');"/>
            <x-input-error :messages="$errors->get('error_2fa')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Iniciar Sesión') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>