<div>
    <label
        class="cursor-pointer flex items-center w-full px-5 py-2 transition-colors duration-200 dark:hover:bg-gray-800 gap-x-2 hover:bg-gray-100 focus:outline-none">
        <span>@lang('Add photos') {{ $error }}</span>
        <input class="hidden" type="file" id="photos" wire:model="photos" accept="image/png, image/gif, image/jpeg"
            multiple>
    </label>

    @script
        <script>
            document.getElementById('photos').addEventListener('change', function(event) {

                let files = event.target.files;

                let dates = [];

                for (let i = 0; i < files.length; i++) {
                    dates.push(files[i].lastModifiedDate);
                }

                $wire.addDates(dates);
            });
        </script>
    @endscript
</div>
