@extends('layouts.app')

@section('content')

@php
    $before    = $project->beforeImages;
    $after     = $project->afterImages;
    $gallery   = $project->galleryImages;
    $hero      = $project->featuredImageUrl();
    $pairCount = min($before->count(), $after->count());
@endphp

<section class="bg-ink-800 text-white">
    <div class="container py-14 sm:py-20">
        <nav class="text-sm text-ink-200 mb-5" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-white">Home</a>
            <span class="mx-1.5 text-ink-400">/</span>
            <a href="{{ route('projects.index') }}" class="hover:text-white">Projects</a>
            <span class="mx-1.5 text-ink-400">/</span>
            <span class="text-white">{{ $project->title }}</span>
        </nav>

        <div class="flex flex-wrap gap-2 mb-3">
            @if ($project->category)
                <span class="chip !bg-white/10 !text-brand-100">{{ $project->category }}</span>
            @endif
            @if ($project->project_type === \App\Models\Project::TYPE_RENOVATION)
                <span class="chip !bg-white !text-ink-700">Before &amp; After</span>
            @elseif ($project->project_type === \App\Models\Project::TYPE_BUILD)
                <span class="chip !bg-white !text-ink-700">Completed Build</span>
            @endif
        </div>

        <h1 class="!text-white max-w-4xl">
            {{ $project->title }}
            @if ($project->location)
                <span class="block text-brand-200 mt-2 text-2xl sm:text-3xl font-display">in {{ $project->location }}, Pretoria East</span>
            @endif
        </h1>

        <div class="mt-6 flex flex-wrap gap-4 text-sm text-ink-200">
            @if ($project->completed_on)
                <span>Completed {{ $project->completed_on->format('M Y') }}</span>
            @endif
            @if ($project->location)
                <span>·</span>
                <span>Location: {{ $project->location }}, Pretoria East</span>
            @endif
        </div>
    </div>
</section>

{{-- Hero image --}}
@if ($hero)
<section class="bg-ink-800 pb-10 -mt-4">
    <div class="container">
        <img src="{{ $hero }}" alt="{{ $project->title }}" class="w-full aspect-video object-cover rounded-xl shadow-card" loading="eager">
    </div>
</section>
@endif

{{-- Description --}}
<section class="section">
    <div class="container grid gap-10 lg:grid-cols-[1.5fr_1fr]">
        <article class="prose-rdm">
            {!! $project->description !!}
            @if (! $project->description)
                <p>
                    {{ $project->title }} — a project completed by {{ config('rdm.name') }}{{ $project->location ? ' in '.$project->location.', Pretoria East' : '' }}.
                </p>
            @endif
        </article>

        <aside class="space-y-4">
            <div class="card p-6">
                <h3 class="!text-lg mb-3">Project details</h3>
                <dl class="grid grid-cols-3 gap-x-4 gap-y-3 text-sm">
                    @if ($project->category)
                        <dt class="col-span-1 text-ink-500">Category</dt>
                        <dd class="col-span-2 font-semibold">{{ $project->category }}</dd>
                    @endif
                    @if ($project->project_type)
                        <dt class="col-span-1 text-ink-500">Type</dt>
                        <dd class="col-span-2 font-semibold capitalize">{{ $project->project_type }}</dd>
                    @endif
                    @if ($project->location)
                        <dt class="col-span-1 text-ink-500">Location</dt>
                        <dd class="col-span-2 font-semibold">{{ $project->location }}, Pretoria East</dd>
                    @endif
                    @if ($project->completed_on)
                        <dt class="col-span-1 text-ink-500">Completed</dt>
                        <dd class="col-span-2 font-semibold">{{ $project->completed_on->format('M Y') }}</dd>
                    @endif
                </dl>
            </div>

            <div class="card p-6 bg-brand-50 border-brand-100">
                <h3 class="!text-lg mb-2">Thinking of a similar project?</h3>
                <p class="text-ink-600 mb-4">Get a no-obligation quote from {{ config('rdm.owner') }}.</p>
                <div class="flex flex-wrap gap-2">
                    <a href="tel:{{ config('rdm.phone_tel') }}" class="btn btn-md bg-brand text-white hover:bg-brand-600">Call {{ config('rdm.phone') }}</a>
                    <a href="https://wa.me/{{ config('rdm.whatsapp') }}?text={{ urlencode('Hi Ruben, I have a project similar to: '.$project->title) }}"
                       target="_blank" rel="noopener" class="btn btn-md bg-[#25D366] text-white hover:bg-[#1fb957]">WhatsApp</a>
                </div>
            </div>
        </aside>
    </div>
</section>

{{-- BEFORE / AFTER --}}
@if ($pairCount > 0)
<section class="section-tight bg-ink-50/60">
    <div class="container">
        <p class="eyebrow">The transformation</p>
        <h2 class="mt-2">Before &amp; after</h2>
        <p class="mt-3 text-ink-500 max-w-2xl">Drag the slider to compare before and after for each paired image.</p>

        <div class="mt-10 grid gap-8 sm:grid-cols-2">
            @for ($i = 0; $i < $pairCount; $i++)
                <x-before-after
                    :before="$before[$i]->url()"
                    :after="$after[$i]->url()"
                    :beforeAlt="$before[$i]->alt ?: 'Before — '.$project->title"
                    :afterAlt="$after[$i]->alt ?: 'After — '.$project->title" />
            @endfor
        </div>
    </div>
</section>
@endif

{{-- Any remaining before-only images --}}
@php
    $extraBefore = $before->slice($pairCount);
    $extraAfter  = $after->slice($pairCount);
@endphp
@if ($extraBefore->count() || $extraAfter->count())
<section class="section-tight">
    <div class="container grid gap-10 lg:grid-cols-2">
        @if ($extraBefore->count())
            <div>
                <h3 class="!text-xl mb-4">More before</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($extraBefore as $img)
                        <img src="{{ $img->url() }}" alt="{{ $img->alt ?: 'Before — '.$project->title }}" loading="lazy" class="aspect-square object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
        @endif
        @if ($extraAfter->count())
            <div>
                <h3 class="!text-xl mb-4">More after</h3>
                <div class="grid grid-cols-2 gap-3">
                    @foreach ($extraAfter as $img)
                        <img src="{{ $img->url() }}" alt="{{ $img->alt ?: 'After — '.$project->title }}" loading="lazy" class="aspect-square object-cover rounded-lg">
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endif

{{-- GENERAL GALLERY (for builds or additional shots) --}}
@if ($gallery->count())
<section class="section-tight {{ $pairCount ? '' : 'bg-ink-50/60' }}">
    <div class="container">
        <p class="eyebrow">Gallery</p>
        <h2 class="mt-2">{{ $project->project_type === \App\Models\Project::TYPE_BUILD ? 'Completed build' : 'Project' }} gallery</h2>

        <div class="mt-8 grid gap-4 grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($gallery as $img)
                <a href="{{ $img->url() }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-lg bg-ink-100">
                    <img src="{{ $img->url() }}" alt="{{ $img->alt ?: $project->title }}" loading="lazy" class="aspect-square object-cover w-full hover:scale-[1.03] transition">
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="section-tight" id="enquiry">
    <div class="container max-w-3xl">
        @include('partials.enquiry-form', [
            'defaultService' => $project->category,
            'source' => 'project:'.$project->slug,
        ])
    </div>
</section>

@if ($related->count())
<section class="section-tight bg-ink-50/60">
    <div class="container">
        <h2 class="!text-2xl mb-8">More projects you might like</h2>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($related as $r)
                @include('partials.project-card', ['project' => $r])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')

@endsection
