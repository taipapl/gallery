<?php

use function Livewire\Volt\{state};
use Livewire\Attributes\{Layout};
use Livewire\Volt\Component;
use Livewire\WithPagination;
use App\Models\User;
use App\Models\Tag;

new #[Layout('layouts.user')] class extends Component {
    use WithPagination;

    public $album;

    public function mount($public_url)
    {
        $this->album = Tag::where('public_url', $public_url)
            ->where('is_public', 1)
            ->firstOrFail();
    }
};

?>

<div>
    //
</div>
