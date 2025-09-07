<div class="max-w-4xl mx-auto mt-10 p-6">

    <form wire:submit="create">
        {{ $this->form }}

        <x-filament::button type="submit" class="mt-4 bg-blue-600 text-white hover:bg-blue-500" wire:loading.class="pointer-events-none opacity-50" wire:target="create" size="lg">
            Simpan
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>