<div>
    <!-- Modal -->
    <div x-data="{ open: false }" <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
        @click="open = ! open">Toggle
        Modal</button>

        @teleport('body')
            <div x-show="open" class="">
                <div class="fixed inset-0 bg-black bg-opacity-75 transition-opacity" aria-hidden="true">
                    <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">


                        <input wire:model="video" />
                    </div>
                </div>

            </div>
        @endteleport
    </div>
</div>
