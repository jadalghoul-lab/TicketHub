<?php

$file = 'resources/views/organizer/dashboard.blade.php';
$content = file_get_contents($file);

$replacements = [
    '<div class="max-w-screen-2xl mx-auto w-full space-y-8">' => '<div class="max-w-screen-2xl mx-auto w-full space-y-8 px-4 sm:px-6 lg:px-8 py-6 sm:py-8">',
    'bg-indigo-600' => 'bg-[#3525cd]',
    'text-indigo-600' => 'text-[#3525cd]',
    'border-indigo-600' => 'border-[#3525cd]',
    'border-indigo-500' => 'border-[#3525cd]',
    'text-indigo-400' => 'text-[#8b82ff]',
    'border-indigo-900' => 'border-[#191c1e]',
    'hover:border-indigo-100' => 'hover:border-[#d5e3fd]',
    'hover:border-indigo-900' => 'hover:border-[#3525cd]',
    'bg-indigo-50' => 'bg-[#d5e3fd] dark:bg-[#3525cd]/20',
    'bg-indigo-900/50' => 'bg-[#191c1e]',
    'bg-indigo-900/30' => 'bg-[#3525cd]/30',
    'hover:bg-indigo-600' => 'hover:bg-[#3525cd]',
    'shadow-indigo-100' => 'shadow-[#d5e3fd]',
    'shadow-indigo-200' => 'shadow-[#d5e3fd]',
    'text-slate-900' => 'text-[#191c1e]',
    'bg-slate-900' => 'bg-[#191c1e]',
    'text-slate-400' => 'text-[#777587]',
    'text-slate-500' => 'text-[#57657b]',
    'text-slate-700' => 'text-[#191c1e]',
    'p-8' => 'p-6 md:p-8',
];

$content = str_replace(array_keys($replacements), array_values($replacements), $content);
file_put_contents($file, $content);
echo 'Replaced styling in dashboard.blade.php';
