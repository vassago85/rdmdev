<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3">
            <x-filament::button type="submit">
                Publish
            </x-filament::button>

            <span class="text-sm text-gray-500 dark:text-gray-400">
                Publish writes to the live site immediately. Use the tabs for Homepage, About, Team, Testimonials, Contact and SEO.
            </span>
        </div>
    </form>
</x-filament-panels::page>
