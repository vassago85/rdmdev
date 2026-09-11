<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Edit the website
        </x-slot>
        <x-slot name="description">
            Everything the designer can publish from this dashboard — no developer, no deploy.
        </x-slot>

        <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
            @foreach ($this->items() as $item)
                <a href="{{ $item['url'] }}"
                   class="flex gap-3 rounded-xl border border-gray-200 bg-white p-4 shadow-sm transition hover:border-primary-400 hover:shadow-md dark:border-gray-700 dark:bg-gray-900 dark:hover:border-primary-500">
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-500/10 dark:text-primary-400">
                        <x-filament::icon :icon="$item['icon']" class="h-5 w-5" />
                    </span>
                    <span class="min-w-0">
                        <span class="block font-semibold text-gray-950 dark:text-white">{{ $item['label'] }}</span>
                        <span class="mt-0.5 block text-sm text-gray-500 dark:text-gray-400">{{ $item['description'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>

        <div class="mt-4">
            <x-filament::link :href="route('home')" target="_blank" icon="heroicon-m-arrow-top-right-on-square">
                View the live site
            </x-filament::link>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
