<x-layouts::auth.split :title="__('Create account')">
    <div class="flex flex-col gap-8">
        <div class="text-left">
            <h1 class="text-3xl font-black tracking-tight text-slate-900 dark:text-white mb-2">{{ __('Join TicketHub') }}</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium">{{ __('Start experiencing the best events today.') }}</p>
        </div>

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Full name')"
                :value="old('name')"
                type="text"
                required
                autofocus
                autocomplete="name"
                placeholder="John Doe"
                class="rounded-xl border-slate-200 focus:ring-indigo-600"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Email address')"
                :value="old('email')"
                type="email"
                required
                autocomplete="username"
                placeholder="name@company.com"
                class="rounded-xl border-slate-200 focus:ring-indigo-600"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('••••••••')"
                viewable
                class="rounded-xl border-slate-200 focus:ring-indigo-600"
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirm password')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('••••••••')"
                viewable
                class="rounded-xl border-slate-200 focus:ring-indigo-600"
            />

            <div class="pt-2">
                <flux:button variant="primary" type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white font-bold py-3 rounded-xl shadow-lg shadow-indigo-100 dark:shadow-none transition-all active:scale-[0.98]">
                    {{ __('Create account') }}
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

            <div class="text-center">
                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">
                    {{ __('Already have an account?') }}
                    <flux:link :href="route('login')" wire:navigate class="text-indigo-600 dark:text-indigo-400 font-black hover:underline underline-offset-4 ml-1">
                        {{ __('Sign in instead') }}
                    </flux:link>
                </p>
            </div>
        </form>
    </div>
</x-layouts::auth.split>
