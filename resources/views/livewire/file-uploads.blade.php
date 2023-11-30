<form wire:submit="save">
    <input type="file" wire:model="photos" multiple>

    @error('photos.*')
        <span class="error">{{ $message }}</span>
    @enderror

    <button type="submit">Save photo</button>
</form>
