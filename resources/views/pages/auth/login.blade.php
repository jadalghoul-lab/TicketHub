<x-layouts::auth.split :title="__('Log in')">
    <div class="flex flex-col gap-8">
        <div class="text-left">
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white mb-2">{{ __('Welcome back') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('Please enter your details to sign in to your account.') }}</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <flux:input
                    name="email"
                    :label="__('Email address')"
                    :value="old('email')"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="name@company.com"
                    class="rounded-xl border-slate-200 focus:ring-indigo-600"
                />
            </div>

            <!-- Password -->
            <div class="space-y-2 relative">
                <flux:input
                    name="password"
                    :label="__('Password')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('••••••••')"
                    viewable
                    class="rounded-xl border-slate-200 focus:ring-indigo-600"
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors end-0" :href="route('password.request')" wire:navigate>
                        {{ __('Forgot password?') }}
                    </flux:link>
                @endif
            </div>

            <div class="flex items-center justify-between mt-2">
                <flux:checkbox name="remember" :label="__('Keep me signed in')" :checked="old('remember')" class="text-slate-600 dark:text-slate-300 font-medium" />
            </div>

            <div class="pt-2">
                <flux:button variant="primary" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-[0.98]" data-test="login-button">
                    {{ __('Sign in') }}
                </flux:button>
            </div>
            
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <span class="w-full border-t border-slate-100 dark:border-slate-800"></span>
                </div>
                <div class="relative flex justify-center text-xs uppercase">
                    <span class="bg-white dark:bg-slate-900 px-2 text-slate-400 dark:text-slate-500 font-bold tracking-widest">{{ __('Or') }}</span>
                </div>
            </div>

            @if (Route::has('register'))
                <div class="text-center">
                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                        {{ __('New to TicketHub?') }}
                        <flux:link :href="route('register')" wire:navigate class="text-indigo-600 dark:text-indigo-400 font-black hover:underline underline-offset-4 ml-1">
                            {{ __('Create an account') }}
                        </flux:link>
                    </p>
                </div>
            @endif
        </form>
    </div>
</x-layouts::auth.split>
