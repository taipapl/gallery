<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class AddBlogPhoto extends ModalComponent
{
    use WithPagination;

    public $perPage = 10;
    public $photoIds = [];
    public $modelId;
    public $post;

    public function mount()
    {
        $this->post = Post::find($this->modelId);

        $this->photoIds =  $this->post->photos()->pluck('photo_id')->toArray();
    }

    public function addPhoto($id)
    {
        $photo = \App\Models\Photo::find($id);

        if (in_array($id, $this->photoIds)) {
            $this->post->photos()->detach($id);
            unset($this->photoIds[array_search($id, $this->photoIds)]);
        } else {
            $this->post->photos()->attach($photo, ['created_at' => now(), 'updated_at' => now()]);
            $this->photoIds[] = $id;
        }

        $this->dispatch('appendPhoto', $photo);
    }


    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function render()
    {
        return view(
            'livewire.add-blog-photo',
            [
                'photos' => auth()->user()->photos()->paginate($this->perPage),
            ]
        );
    }
}
