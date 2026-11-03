@php
    $url   = route('projects.show', $project->slug);
    $image = $project->coverImageUrl();
    $isRenovation = $project->project_type === \App\Models\Project::TYPE_RENOVATION;
@endphp

<div class="card-interactive group block overflow-hidden flex flex-col">
    <div class="aspect-[4/3] bg-ink-100 overflow-hidden relative">
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
