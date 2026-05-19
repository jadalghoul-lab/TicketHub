<x-layouts::auth.split :title="__('Forgot password')">
    <div class="flex flex-col gap-8">
        <div class="text-left">
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white mb-2">{{ __('Reset password') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('Enter your email address and we\'ll send you a link to reset your password.') }}</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                type="email"
                required
                autofocus
                placeholder="name@company.com"
                class="rounded-xl border-slate-200 focus:ring-indigo-600"
            />

            <div class="pt-2">
                <flux:button variant="primary" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-[0.98]" data-test="email-password-reset-link-button">
                    {{ __('Send reset link') }}
                </flux:button>
            </div>
        </form>

        <div class="text-center pt-2">
            <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                {{ __('Remember your password?') }}
                <flux:link :href="route('login')" wire:navigate class="text-indigo-600 dark:text-indigo-400 font-black hover:underline underline-offset-4 ml-1">
                    {{ __('Sign in') }}
                </flux:link>
            </p>
        </div>
    </div>
</x-layouts::auth.split>
