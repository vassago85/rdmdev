@props([
    'items' => [],
    'heading' => 'Frequently asked questions',
    'eyebrow' => 'FAQ',
])

@php
    $items = collect($items)
        ->filter(fn ($item) => filled($item['question'] ?? null) && filled($item['answer'] ?? null))
        ->values();
@endphp

@if ($items->isNotEmpty())
<section {{ $attributes->merge(['class' => 'section-tight', 'id' => 'faq']) }}>
    <div class="container max-w-3xl">
        @if ($eyebrow)
            <p class="eyebrow">{{ $eyebrow }}</p>
        @endif
        <h2 class="mt-2">{{ $heading }}</h2>

        <div class="mt-8 divide-y divide-ink-100 border-y border-ink-100">
            @foreach ($items as $item)
                <details class="group py-5">
                    <summary class="flex cursor-pointer list-none items-start justify-between gap-4 text-left font-semibold text-ink-800 hover:text-brand-700">
                        <span>{{ $item['question'] }}</span>
                        <x-lucide name="arrow-right" class="mt-1 h-4 w-4 shrink-0 rotate-90 transition group-open:-rotate-90 text-brand-600" />
                    </summary>
                    <div class="mt-3 text-ink-600 leading-relaxed text-[15px]">
                        {!! nl2br(e($item['answer'])) !!}
                    </div>
                </details>
            @endforeach
        </div>
    </div>
</section>
@endif
