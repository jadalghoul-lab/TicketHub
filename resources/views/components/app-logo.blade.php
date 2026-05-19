@props([
    'sidebar' => false,
    'name' => config('app.name', 'TicketHub'),
])

@if($sidebar)
    <flux:sidebar.brand :name="$name" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center pr-2">
            <span class="w-2 h-6 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand :name="$name" {{ $attributes }}>
        <x-slot name="logo" class="flex items-center justify-center pr-2">
            <span class="w-2 h-6 bg-indigo-600 dark:bg-indigo-500 rounded-full"></span>
        </x-slot>
    </flux:brand>
@endif
