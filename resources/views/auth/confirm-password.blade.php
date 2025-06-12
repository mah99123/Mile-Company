<x-guest-layout>
    <div class="text-center mb-5">
        <h1 class="text-2xl font-bold">تأكيد كلمة المرور</h1>
        <p class="text-gray-600">هذه منطقة آمنة للتطبيق. يرجى تأكيد كلمة المرور الخاصة بك قبل المتابعة.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('كلمة المرور')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-4">
            <x-primary-button>
                {{ __('تأكيد') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
