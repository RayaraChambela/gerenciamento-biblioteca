@props(['status'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center text-xs font-medium px-2.5 py-0.5 rounded-full ' . $status->badgeClasses()]) }}>
    {{ $status->label() }}
</span>
