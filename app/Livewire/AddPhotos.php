<?php

namespace App\Livewire;

use App\Models\Tag;
use Livewire\WithPagination;
use LivewireUI\Modal\ModalComponent;

class AddPhotos extends ModalComponent
{

    use WithPagination;

    public $perPage = 10;
    public string $tag_id = '';
    public $photoIds = [];

    public function mount()
    {
        $this->photoIds = Tag::find($this->tag_id)->photos()->pluck('photo_id')->toArray();
    }

    public function loadMore()
    {
        $this->perPage += 10;
    }

    public function addPhoto($id)
    {

        if (in_array($id, $this->photoIds)) {
            $photo  = \App\Models\pivot\PhotoTag::where('photo_id', $id)->where('tag_id', $this->tag_id)->first();
            $photo->delete();
            unset($this->photoIds[array_search($id, $this->photoIds)]);
        } else {

            $photo  = new \App\Models\pivot\PhotoTag();
            $photo->photo_id = $id;
            $photo->tag_id = $this->tag_id;
            $photo->user_id = auth()->id();
            $photo->save();

            $this->photoIds[] = $id;

            $tag = Tag::find($this->tag_id);

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
                'photos' => auth()->user()->photos()->paginate($this->perPage)
            ]
        );
    }
}
