@props(['type' => 'rectangle', 'class' => ''])

@php
    $baseClasses = 'animate-pulse bg-gray-200 dark:bg-gray-700';
    $shapeClasses = match($type) {
        'circle' => 'rounded-full',
        'square' => 'rounded-md',
        default => 'rounded-md', // rectangle
    };
@endphp

<div {{ $attributes->merge(['class' => "$baseClasses $shapeClasses $class"]) }}></div>
