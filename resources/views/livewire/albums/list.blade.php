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

    <x-panel>

        <div class="flex gap3 flex-col md:flex-row items-left">

            <h2 class="px-5 text-lg font-medium text-gray-800 dark:text-white">@lang('Albums')</h2>

            <x-check-box :model="'is_archived'" :checked="$is_archived" :action="'archived()'">
                @lang('Archived')
            </x-check-box>

            <livewire:albums.create />

        </div>

    </x-panel>


    <x-panel>
        <x-albums :albums="$albums" />
    </x-panel>


</x-container>
