@props([
    'items' => [], // [['name' => 'Home', 'url' => '/'], ['name' => 'Current']]
    'tone'  => 'dark', // dark = on ink hero; light = on white
])

@php
    $linkClass = $tone === 'dark'
        ? 'hover:text-white text-ink-200'
        : 'hover:text-brand-700 text-ink-500';
    $sepClass  = $tone === 'dark' ? 'text-ink-400' : 'text-ink-300';
    $curClass  = $tone === 'dark' ? 'text-white' : 'text-ink-700';
@endphp

@if (count($items) > 0)
<nav {{ $attributes->merge(['class' => 'text-sm mb-5', 'aria-label' => 'Breadcrumb']) }}>
    <ol class="flex flex-wrap items-center gap-x-1.5 gap-y-1">
        @foreach ($items as $i => $item)
            <li class="inline-flex items-center gap-1.5">
                @if ($i > 0)
                    <span class="{{ $sepClass }}" aria-hidden="true">/</span>
                @endif
                @if (! empty($item['url']) && ! $loop->last)
                    <a href="{{ $item['url'] }}" class="{{ $linkClass }}">{{ $item['name'] }}</a>
                @else
                    <span class="{{ $curClass }}" @if($loop->last) aria-current="page" @endif>{{ $item['name'] }}</span>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
@endif
