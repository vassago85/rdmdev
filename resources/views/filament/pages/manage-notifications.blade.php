<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3">
            <x-filament::button type="submit">
                Save settings
            </x-filament::button>

            <span class="text-sm text-gray-500 dark:text-gray-400">
                Changes apply immediately. Use the test buttons (top right) to verify without saving.
            </span>
        </div>
    </form>
</x-filament-panels::page>
