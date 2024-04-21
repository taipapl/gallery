<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\Photo;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

new #[Layout('layouts.app')] class extends Component {
    use WithFileUploads;

    public $date;

    public function mount()
    {
    }

    public function save()
    {
        dd($this->date);
    }

    public function addDate($date)
    {
        $this->date = $date;
    }
};

?>
<div x-data="{ active: true }">

    <div x-show="active" @click.away="active = false"
        class="fixed right-0 top-0 mr-14 h-screen py-8 overflow-y-auto bg-white border-l border-r w-40 dark:bg-gray-900 dark:border-gray-700">



        <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">{{ __('Photos') }}</h2>

        <div class="mt-8 space-y-4">



            <livewire:file-uploads />

            <x-sub-nav-link href="{{ route('photos.archived') }}">
                @lang('Archived Photos')
            </x-sub-nav-link>

        </div>
    </div>


    <div class="py-12">



        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">



                    <label>Drag and Drop Multiple Images (JPG, JPEG, PNG, .webp)</label>

                    <form action="#" method="POST" enctype="multipart/form-data" class="dropzone"
                        id="myDragAndDropUploader">
                        @csrf
                    </form>

                    <h5 id="message"></h5>

                </div>

            </div>
        </div>
    </div>


</div>

@script
    <script>
        Dropzone.options.myDragAndDropUploader = {
            init: function() {
                this.on("sending", function(file, xhr, formData) {

                    $wire.addDate(file.lastModifiedDate);

                });
            }
        };
    </script>
@endscript

</div>
