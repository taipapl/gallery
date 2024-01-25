<div>

    <label
        class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 cursor-pointer">
        <span>@lang('Add photos')</span>
        <input class="hidden" type="file" id="photos" wire:model="photos" accept="image/png, image/gif, image/jpeg"
            multiple>
    </label>

    @script
        <script>
            document.getElementById('photos').addEventListener('change', function(event) {

                let files = event.target.files;

                let dates = [];

                for (let i = 0; i < files.length; i++) {

                    console.log(files[i].lastModifiedDate);

                    dates.push(files[i].lastModifiedDate);

                }

                $wire.addDates(dates);


            });
        </script>
    @endscript




</div>
