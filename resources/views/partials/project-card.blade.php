@php
    $url          = route('projects.show', $project->slug);
    $isRenovation = $project->project_type === \App\Models\Project::TYPE_RENOVATION;
    $showSlider   = $isRenovation && $project->cardHasBeforeAfter();

    if ($showSlider) {
        $beforeImg = $project->firstImageOfType('before');
        $afterImg  = $project->firstImageOfType('after');
    }

    $image = $project->featuredImageUrl();
@endphp

<div class="card-interactive group block overflow-hidden flex flex-col">
    <div class="aspect-[4/3] bg-ink-100 overflow-hidden relative">
        @if ($showSlider)
            {{-- Inline drag-to-compare before/after — the transformation sells the job --}}
            <div x-data="{ pos: 50 }" class="absolute inset-0 select-none">
                <img src="{{ $beforeImg->url() }}"
                     alt="{{ $beforeImg->alt ?: 'Before — '.$project->baseTitle() }}"
                     loading="lazy" decoding="async"
                     class="absolute inset-0 w-full h-full object-cover">

                <div class="absolute inset-0 overflow-hidden" :style="`clip-path: inset(0 ${100 - pos}% 0 0);`">
                    <img src="{{ $afterImg->url() }}"
                         alt="{{ $afterImg->alt ?: 'After — '.$project->baseTitle() }}"
                         loading="lazy" decoding="async"
                         class="absolute inset-0 w-full h-full object-cover">
                </div>

                <span class="absolute bottom-3 left-3 chip !bg-white/90 !text-ink-700">Before</span>
                <span class="absolute bottom-3 right-3 chip !bg-brand-600 !text-white">After</span>

                <div class="absolute top-0 bottom-0 w-0.5 bg-white shadow-lg pointer-events-none"
                     :style="`left: ${pos}%; transform: translateX(-50%);`">
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-9 w-9 rounded-full bg-white shadow-md grid place-items-center text-brand-700">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 18 9 12 15 6"/>
                            <polyline points="9 6 15 12 9 18" transform="translate(6 0)"/>
                        </svg>
                    </div>
                </div>

                <input type="range" min="0" max="100" x-model.number="pos"
                       aria-label="Drag to compare before and after — {{ $project->baseTitle() }}"
                       class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize">
            </div>

            <div class="absolute top-3 left-3 flex gap-2 pointer-events-none">
                @if ($project->category)
                    <span class="chip">{{ $project->category }}</span>
                @endif
            </div>
        @else
            <a href="{{ $url }}" class="block w-full h-full" aria-label="{{ $project->baseTitle() }}">
                @if ($image)
                    <img src="{{ $image }}"
                         alt="{{ $project->baseTitle() }}"
                         loading="lazy" decoding="async"
                         class="w-full h-full object-cover transition duration-500 ease-out group-hover:scale-105">
                @else
                    <div class="w-full h-full grid place-items-center text-ink-300 bg-gradient-to-br from-ink-100 to-ink-200">
                        <x-rdm-logo class="h-10 opacity-40" :eager="false" />
                    </div>
                @endif
            </a>
            <div class="absolute top-3 left-3 flex gap-2 pointer-events-none">
                @if ($project->category)
                    <span class="chip">{{ $project->category }}</span>
                @endif
                @if ($isRenovation)
                    <span class="chip !bg-white/90 !text-ink-700">Before &amp; After</span>
                @elseif ($project->project_type === \App\Models\Project::TYPE_BUILD)
                    <span class="chip !bg-white/90 !text-ink-700">Completed Build</span>
                @endif
            </div>
        @endif
    </div>

    <div class="p-5 sm:p-6 flex-1 flex flex-col">
        <h3 class="!text-lg">
            <a href="{{ $url }}" class="group-hover:text-brand-600 transition">{{ $project->baseTitle() }}</a>
        </h3>
        @if ($label = $project->locationLabel())
            <p class="mt-1 text-sm text-ink-500">{{ $label }}</p>
        @endif
        <a href="{{ $url }}" class="mt-3 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 hover:text-brand-700">
            View project
            <x-lucide name="arrow-right" class="h-4 w-4" />
        </a>
    </div>
</div>
