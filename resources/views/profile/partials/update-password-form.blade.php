<section>
    <header class="mb-6">
        <h2 class="text-2xl font-bold text-blue-600">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-sm text-gray-600">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-blue-600" />
            <x-text-input id="update_password_current_password" name="current_password" type="password"
                class="mt-1 block w-full border-blue-200 focus:border-blue-500 focus:ring-blue-500"
                autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password" :value="__('New Password')" class="text-blue-600" />
            <x-text-input id="update_password_password" name="password" type="password"
                class="mt-1 block w-full border-blue-200 focus:border-blue-500 focus:ring-blue-500"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" class="text-blue-600" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="mt-1 block w-full border-blue-200 focus:border-blue-500 focus:ring-blue-500"
                autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-4">
            <x-primary-button class="bg-blue-500 hover:bg-blue-600 focus:ring-blue-300">
                {{ __('Update Password') }}
            </x-primary-button>

            @if (session('status') === 'password-updated')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 3000)"
                class="text-sm text-green-600 font-medium">{{ __('Password updated successfully!') }}</p>
            @endif
        </div>
    </form>
</section>