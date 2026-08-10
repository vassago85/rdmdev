@php
    $defaultService = $defaultService ?? null;
    $source         = $source         ?? 'page';
    $compact        = $compact        ?? false;
@endphp

<div id="enquiry" class="card p-6 sm:p-8">
    @if (session('enquiry.success'))
        <div class="mb-6 rounded-md bg-brand-50 border border-brand-100 p-4 text-brand-800">
            {{ session('enquiry.success') }}
        </div>
    @endif

    <h3 class="!text-2xl mb-2">Request a quote</h3>
    <p class="text-ink-500 mb-6">
        Fill in the form below and {{ config('rdm.owner') }} will be in touch shortly.
        Prefer to speak directly?
        <a class="link-underline" href="tel:{{ config('rdm.phone_tel') }}">{{ config('rdm.phone') }}</a>.
    </p>

    <form action="{{ route('enquiries.store') }}" method="POST" enctype="multipart/form-data" class="grid gap-4 {{ $compact ? '' : 'sm:grid-cols-2' }}">
        @csrf
        <input type="hidden" name="source" value="{{ $source }}">

        {{-- Honeypot --}}
        <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">

        <div>
            <label for="f_name" class="label">Your name</label>
            <input type="text" id="f_name" name="name" value="{{ old('name') }}" required class="input">
            @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="f_phone" class="label">Phone</label>
            <input type="tel" id="f_phone" name="phone" value="{{ old('phone') }}" required class="input" placeholder="e.g. 082 123 4567">
            @error('phone') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="f_email" class="label">Email (optional)</label>
            <input type="email" id="f_email" name="email" value="{{ old('email') }}" class="input">
            @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="f_service" class="label">Service</label>
            <select id="f_service" name="service_type" class="input">
                <option value="">Not sure yet</option>
                @foreach (\App\Models\Service::published()->ordered()->get() as $svc)
                    <option value="{{ $svc->title }}"
                        @selected(old('service_type', $defaultService) === $svc->title)>
                        {{ $svc->title }}
                    </option>
                @endforeach
                <option value="Other" @selected(old('service_type') === 'Other')>Something else</option>
            </select>
        </div>

        <div class="{{ $compact ? '' : 'sm:col-span-2' }}">
            <label for="f_suburb" class="label">Suburb (optional)</label>
            <input type="text" id="f_suburb" name="suburb" value="{{ old('suburb') }}" class="input" placeholder="Garsfontein, Faerie Glen…">
        </div>

        <div class="{{ $compact ? '' : 'sm:col-span-2' }}">
            <label for="f_message" class="label">Project details</label>
            <textarea id="f_message" name="message" rows="5" required class="input" placeholder="Tell us about your project…">{{ old('message') }}</textarea>
            @error('message') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="{{ $compact ? '' : 'sm:col-span-2' }}"
             x-data="{ files: [] }">
            <label for="f_photos" class="label">Photos (optional)</label>
            <label for="f_photos"
                   class="flex items-center gap-3 rounded-md border-2 border-dashed border-ink-200 bg-ink-50/50 px-4 py-3 cursor-pointer transition hover:border-brand-300 hover:bg-brand-50/40">
                <span class="inline-flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg bg-brand-50 text-brand-600">
                    <x-lucide name="camera" class="h-5 w-5" />
                </span>
                <span class="text-sm text-ink-600">
                    <span x-show="files.length === 0">Snap or attach a photo of the job — a leaking roof, a tired bathroom, the space to build.</span>
                    <span x-show="files.length > 0" x-cloak class="font-semibold text-ink-800"
                          x-text="files.length + (files.length === 1 ? ' photo attached' : ' photos attached')"></span>
                </span>
            </label>
            <input type="file" id="f_photos" name="photos[]" multiple accept="image/*"
                   class="sr-only"
                   x-on:change="files = Array.from($event.target.files).map(f => f.name)">
            <p class="text-xs text-ink-400 mt-1.5">Up to 5 images · JPG, PNG, WEBP or HEIC · max 8MB each.</p>
            @error('photos') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            @error('photos.*') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="{{ $compact ? '' : 'sm:col-span-2' }} flex flex-col sm:flex-row sm:items-center gap-3 pt-2">
            <button type="submit" class="btn-primary">
                <x-lucide name="arrow-right" class="h-5 w-5" />
                Request My Quote
            </button>
            <p class="text-xs text-ink-400">We'll only use your details to respond to this enquiry.</p>
        </div>
    </form>
</div>
