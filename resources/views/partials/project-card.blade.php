@php
    $image = $project->featuredImageUrl();
@endphp
<a href="{{ route('projects.show', $project->slug) }}"
   class="card-interactive group block">
    <div class="aspect-[4/3] bg-ink-100 overflow-hidden relative">
        @if ($image)
            <img src="{{ $image }}"
                 alt="{{ $project->baseTitle() }}"
                 loading="lazy"
                 decoding="async"
                 class="w-full h-full object-cover transition duration-500 ease-out group-hover:scale-105">
        @else
            <div class="w-full h-full grid place-items-center text-ink-300 bg-gradient-to-br from-ink-100 to-ink-200">
                <x-rdm-logo class="h-10 opacity-40" :eager="false" />
            </div>
        @endif
        <div class="absolute top-3 left-3 flex gap-2">
            @if ($project->category)
                <span class="chip">{{ $project->category }}</span>
            @endif
            @if ($project->project_type === \App\Models\Project::TYPE_RENOVATION)
                <span class="chip !bg-white/90 !text-ink-700">Before &amp; After</span>
            @elseif ($project->project_type === \App\Models\Project::TYPE_BUILD)
                <span class="chip !bg-white/90 !text-ink-700">Completed Build</span>
            @endif
        </div>
    </div>
    <div class="p-5 sm:p-6">
        <h3 class="!text-lg group-hover:text-brand-600 transition">{{ $project->baseTitle() }}</h3>
        @if ($label = $project->locationLabel())
            <p class="mt-1 text-sm text-ink-500">{{ $label }}</p>
        @endif
    </div>
</a>
