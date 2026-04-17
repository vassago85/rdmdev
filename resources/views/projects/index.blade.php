@extends('layouts.app')

@section('content')

<section class="bg-ink-800 text-white">
    <div class="container py-16 sm:py-20">
        <p class="eyebrow !text-brand-200">Our Work</p>
        <h1 class="!text-white mt-3 max-w-3xl">Completed renovations &amp; builds in Pretoria East</h1>
        <p class="mt-5 text-lg text-ink-100/90 max-w-2xl">
            Browse before-and-after renovations and completed build projects across
            Garsfontein, Faerie Glen, Moreleta Park, Woodhill, Silver Lakes and more.
        </p>

        <div class="mt-8 flex flex-wrap gap-2">
            @php
                $tabs = [
                    ['label' => 'All',            'type' => null],
                    ['label' => 'Renovations (before &amp; after)', 'type' => \App\Models\Project::TYPE_RENOVATION],
                    ['label' => 'Completed builds',  'type' => \App\Models\Project::TYPE_BUILD],
                ];
            @endphp
            @foreach ($tabs as $tab)
                @php
                    $url    = $tab['type'] ? route('projects.index', ['type' => $tab['type']]) : route('projects.index');
                    $active = ($activeType ?? null) === $tab['type'];
                @endphp
                <a href="{{ $url }}"
                   class="inline-flex items-center rounded-full px-4 py-2 text-sm font-semibold transition
                          {{ $active ? 'bg-white text-brand-700' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    {!! $tab['label'] !!}
                </a>
            @endforeach
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        @if ($projects->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($projects as $project)
                    @include('partials.project-card', ['project' => $project])
                @endforeach
            </div>

            <div class="mt-10">
                {{ $projects->links() }}
            </div>
        @else
            <div class="card p-10 text-center">
                <h3 class="!text-xl">Projects coming soon</h3>
                <p class="mt-2 text-ink-500">We're uploading our recent projects. In the meantime, get in touch for examples of our work.</p>
                <a href="{{ route('contact') }}" class="btn btn-md mt-6 bg-brand text-white hover:bg-brand-600">Get in touch</a>
            </div>
        @endif
    </div>
</section>

@include('partials.cta')

@endsection
