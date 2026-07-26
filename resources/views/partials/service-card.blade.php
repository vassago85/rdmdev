@props([
    'service',
    'headingTag' => 'h3', // h2 on listing page, h3 under a section H2
])

<a href="{{ route('services.show', $service->slug) }}"
   {{ $attributes->merge(['class' => 'card-interactive group block border-t-4 border-t-brand-500 p-6 sm:p-7']) }}>
    <div class="inline-flex items-center justify-center h-12 w-12 rounded-lg bg-brand-50 text-brand-600 mb-5 group-hover:bg-brand-600 group-hover:text-white transition">
        <x-service-icon :icon="$service->icon ?: 'house'" />
    </div>

    @if ($headingTag === 'h2')
        <h2 class="!text-xl group-hover:text-brand-600 transition">{{ $service->title }}</h2>
    @else
        <h3 class="!text-xl group-hover:text-brand-600 transition">{{ $service->title }}</h3>
    @endif

    @if ($service->tagline)
        <p class="mt-1 text-sm font-semibold text-brand-600">{{ $service->tagline }}</p>
    @endif

    @if ($service->excerpt)
        <p class="mt-3 text-ink-500 text-[15px] leading-relaxed">{{ \Illuminate\Support\Str::limit($service->excerpt, 155) }}</p>
    @endif

    <p class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 group-hover:gap-2.5 transition-all">
        Learn more
        <x-lucide name="arrow-right" class="h-4 w-4" />
    </p>
</a>
