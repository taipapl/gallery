<?php
use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use Illuminate\View\View;
use App\Models\User;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public $albums = [];
    public $perPage = 10;

    public $is_archived = 0;

    public function loadMore(): void
    {
        $this->perPage += 10;
    }

    public function archived()
    {
        $this->is_archived = (int) !$this->is_archived;
        $this->resetPage();
    }

    public function mount(): void
    {
        seo()->title(__('Albums') . ' - ' . config('app.name'));
    }

    public function rendering(View $view): void
    {
        $query = Auth::user()->tags()->where('is_album', 1)->getQuery();

        if ($this->is_archived) {
            $query->where('is_archived', $this->is_archived);
        } else {
            $query->where('is_archived', 0);
        }

        $query->orderBy('created_at', 'desc');

        $view->albums = $query->paginate($this->perPage);
    }
};
?>
<x-container>

    <x-card>

        <div class="flex gap3 flex-col md:flex-row items-left">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Albums')</h2>

            <div class="px-4">
                <label wire:click="archived()" class="relative inline-flex items-center cursor-pointer">
                    <input wire:model="is_archived" type="checkbox" @if ($is_archived) checked @endif
                        class="sr-only peer" value="1">
                    <div
                        class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:start-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all dark:border-gray-600 peer-checked:bg-blue-600">
                    </div>
                    <span class="ms-3 text-sm font-medium text-gray-900 dark:text-gray-300">@lang('Archived')
                    </span>
                </label>
            </div>

            <livewire:albums.create />

        </div>

    </x-card>


    <x-card>
        <x-albums :albums="$albums" />
    </x-card>


</x-container>
