<div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 w-full h-full flex flex-col">
    {{-- Image placeholder --}}
    <x-skeleton.base type="rectangle" class="w-full h-48 sm:h-56 rounded-none" />
    
    <div class="p-5 flex flex-col flex-grow gap-4">
        {{-- Title placeholder --}}
        <div class="space-y-2 mt-2">
            <x-skeleton.base type="rectangle" class="w-3/4 h-6" />
            <x-skeleton.base type="rectangle" class="w-1/2 h-4" />
        </div>
        
        {{-- Content placeholder --}}
        <div class="space-y-2 mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
            <div class="flex justify-between items-center">
                <x-skeleton.base type="rectangle" class="w-1/4 h-4" />
                <x-skeleton.base type="rectangle" class="w-1/4 h-6" />
            </div>
        </div>
    </div>
</div>
