@props(['before', 'after', 'beforeAlt' => 'Before', 'afterAlt' => 'After'])

<div x-data="{ pos: 50 }"
     class="relative aspect-[4/3] rounded-xl overflow-hidden bg-ink-200 select-none shadow-card">
    <img src="{{ $before }}" alt="{{ $beforeAlt }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">

    <div class="absolute inset-0 overflow-hidden"
         :style="`clip-path: inset(0 ${100 - pos}% 0 0);`">
        <img src="{{ $after }}" alt="{{ $afterAlt }}" class="absolute inset-0 w-full h-full object-cover" loading="lazy">
    </div>

    {{-- Labels --}}
    <span class="absolute top-3 left-3 chip !bg-white/90 !text-ink-700">Before</span>
    <span class="absolute top-3 right-3 chip !bg-brand-600 !text-white">After</span>

    {{-- Divider --}}
    <div class="absolute top-0 bottom-0 w-0.5 bg-white shadow-lg pointer-events-none"
         :style="`left: ${pos}%; transform: translateX(-50%);`">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 h-10 w-10 rounded-full bg-white shadow-md grid place-items-center text-brand-700">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"/>
                <polyline points="9 6 15 12 9 18" transform="translate(6 0)"/>
            </svg>
        </div>
    </div>

    <input type="range" min="0" max="100" x-model.number="pos"
           aria-label="Drag to compare before and after"
           class="absolute inset-0 w-full h-full opacity-0 cursor-ew-resize">
</div>
