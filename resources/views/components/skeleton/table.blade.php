@props(['rows' => 5, 'cols' => 4])

@for ($i = 0; $i < $rows; $i++)
    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 animate-pulse">
        @for ($j = 0; $j < $cols; $j++)
            <td class="px-6 py-4">
                @if($j === 0)
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-200 rounded-full dark:bg-gray-700"></div>
                        <div class="h-4 bg-gray-200 rounded-md dark:bg-gray-700 w-24"></div>
                    </div>
                @elseif($j === $cols - 1)
                    <div class="h-8 bg-gray-200 rounded-md dark:bg-gray-700 w-16 float-right"></div>
                @else
                    <div class="h-4 bg-gray-200 rounded-md dark:bg-gray-700 w-20"></div>
                @endif
            </td>
        @endfor
    </tr>
@endfor
