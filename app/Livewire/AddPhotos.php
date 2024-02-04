<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class AddPhotos extends ModalComponent
{
    use WithPagination;

    public $perPage = 10;

    public $tagId;

    public $photoIds = [];

    public function mount()
    {
        $this->photoIds = Tag::find($this->tagId)->photos()->pluck('photo_id')->toArray();
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function addPhoto($id)
    {

        if (in_array($id, $this->photoIds)) {
            $photo = \App\Models\pivot\PhotoTag::where('photo_id', $id)->where('tag_id', $this->tagId)->first();
            $photo->delete();
            unset($this->photoIds[array_search($id, $this->photoIds)]);
        } else {

            $photo = new \App\Models\pivot\PhotoTag();
            $photo->photo_id = $id;
            $photo->tag_id = $this->tagId;
            $photo->user_id = auth()->id();
            $photo->save();

            $this->photoIds[] = $id;

            $tag = Tag::find($this->tagId);

            if (empty($tag->cover)) {
                $photo2 = \App\Models\Photo::find($id);
                $tag->cover = $photo2->path;
                $tag->save();
            }
        }

        $this->dispatch('appendPhoto', $photo);
    }

    public function render()
    {
        return view(
            'livewire.add-photos',
            [
                'photos' => auth()->user()->photos()->paginate($this->perPage),
            ]
        );
    }
}
