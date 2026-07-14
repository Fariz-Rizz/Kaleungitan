<section class="space-y-6">
    <header>
        <h2 class="text-lg font-semibold text-on-surface">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-on-surface-variant">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="bg-error text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-semibold text-on-surface">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-on-surface-variant">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="{{ __('Password') }}" class="sr-only" />

                <x-text-input id="password" name="password" type="password"
                    class="mt-1 block w-3/4 !bg-surface-container-low !border-none !rounded-lg !text-sm focus:!ring-2 focus:!ring-primary"
                    placeholder="{{ __('Password') }}" />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                    class="px-4 py-2 rounded-lg text-sm font-semibold border border-outline-variant text-on-surface-variant hover:bg-surface-container-low transition-colors">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                    class="bg-error text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
