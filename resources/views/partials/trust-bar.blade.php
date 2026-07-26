{{-- Visible credentials strip — complements the faint footer legal text. --}}
<section class="border-y border-ink-100 bg-white">
    <div class="container py-4 sm:py-5">
        <ul class="flex flex-wrap items-center justify-center gap-x-8 gap-y-3 text-sm font-semibold text-ink-700">
            <li class="inline-flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                    <x-lucide name="shield-check" class="h-4 w-4" />
                </span>
                <span>
                    Owner-managed by {{ config('rdm.owner') }}
                </span>
            </li>
            <li class="hidden sm:block h-4 w-px bg-ink-200" aria-hidden="true"></li>
            <li class="inline-flex items-center gap-2">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                    <x-lucide name="check-circle-2" class="h-4 w-4" />
                </span>
                <span>
                    Registered with the NHBRC as a home builder
                    @if (config('rdm.nhbrc_number'))
                        <span class="font-normal text-ink-500">({{ config('rdm.nhbrc_number') }})</span>
                    @endif
                </span>
            </li>
            <li class="hidden sm:block h-4 w-px bg-ink-200" aria-hidden="true"></li>
            <li class="inline-flex items-center gap-2 text-ink-600">
                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-brand-50 text-brand-600">
                    <x-lucide name="map-pin" class="h-4 w-4" />
                </span>
                <span>{{ config('rdm.location') }}</span>
            </li>
        </ul>
    </div>
</section>
