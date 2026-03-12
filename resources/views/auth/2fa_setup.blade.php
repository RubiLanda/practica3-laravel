<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Configuración de Doble Factor: Escanea el código QR con tu aplicación de Google Authenticator.') }}
    </div>

    <div class="flex flex-col items-center justify-center p-4 bg-white rounded-lg shadow">
        <div class="mb-4">
            {!! $qrCodeImage !!}
        </div>

        <p class="text-xs text-gray-500 mb-4">
            Si no puedes escanear el código, ingresa esta llave manualmente: <br>
            <span class="font-bold text-gray-800">{{ $secret }}</span>
        </p>

        <form method="POST" action="{{ route('2fa.verify') }}" class="w-full">
            @csrf
            <div>
                <x-input-label for="one_time_password" :value="__('Código de Verificación')" />
                <x-text-input id="one_time_password" class="block mt-1 w-full" type="text" name="one_time_password" required autofocus placeholder="000000" />
                <x-input-error :messages="$errors->get('error_2fa')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button>
                    {{ __('Verificar y Acceder') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>