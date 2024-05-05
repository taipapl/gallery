<div class="absolute bottom-0 right-0  flex items-start justify-center" x-data="{ showContextMenu: false }">
    <div class="relative" @click.away="showContextMenu=false">
        <button
            class="bg-white h-10 w-10 leading-10 text-center text-gray-800 text-xl shadow-md border border-gray-200 hover:border-gray-300 focus:border-gray-300 rounded-lg transition-all font-semibold outline-none focus:outline-none"
            x-on:click="$event.preventDefault();showContextMenu=true">
            ...
        </button>
        <div class="absolute mt-12 top-0 left-1 min-w-full w-48 z-30" style="display:none;" x-show="showContextMenu"
            x-transition:enter="transition ease duration-100 transform"
            x-transition:enter-start="opacity-0 scale-90 translate-y-1"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease duration-100 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-90 translate-y-1">
            <span
                class="absolute top-0 left-0 w-2 h-2 bg-white transform rotate-45 -mt-1 ml-3 border-gray-300 border-l border-t z-20"></span>
            <div
                class="bg-white overflow-auto rounded-lg shadow-md w-full relative z-10 py-2 border border-gray-300 text-gray-800 text-xs">
                <ul class="list-reset">
                    <li>
                        {{ $slot }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
