{{-- Proof points under the hero. Empty or TODO values are left off the page. --}}
@php
    $stats = [];
    $years = config('rdm.years_in_business');
    $projects = config('rdm.completed_projects');
    $rating = config('rdm.google.rating');

    if (filled($years) && $years !== 'TODO') {
        $stats[] = ['icon' => 'calendar-check', 'text' => $years];
    }
    if (filled($projects) && $projects !== 'TODO') {
        $stats[] = ['icon' => 'hammer', 'text' => $projects . ' completed projects'];
    }
    if (is_numeric($rating) && (float) $rating > 0) {
        $stats[] = ['icon' => 'star', 'text' => 'Google rating ' . $rating];
    }
@endphp
@if ($stats !== [])
<section class="border-y border-ink-100 bg-white">
    <div class="container py-4 sm:py-5">
        <ul class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm font-semibold text-ink-700">
            @foreach ($stats as $stat)
                @if (! $loop->first)
                    <li class="hidden sm:block h-4 w-px bg-ink-200" aria-hidden="true"></li>
                @endif
                <li class="inline-flex items-center gap-2">
                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                        <x-lucide :name="$stat['icon']" class="h-4 w-4" />
                    </span>
                    <span>{{ $stat['text'] }}</span>
                </li>
            @endforeach
        </ul>
    </div>
</section>
@endif
