@props(['items' => 3])

@for ($i = 0; $i < $items; $i++)
    <div class="p-4 border-b border-gray-100 dark:border-gray-700 last:border-0 flex items-center gap-4 w-full">
        <x-skeleton.base type="circle" class="w-12 h-12 flex-shrink-0" />
        <div class="space-y-2 flex-grow">
            <x-skeleton.base type="rectangle" class="w-2/3 h-4" />
            <x-skeleton.base type="rectangle" class="w-1/2 h-3" />
        </div>
    </div>
@endfor
