<?php

namespace App\Livewire;

use App\Rules\Me;
use Illuminate\Support\Str;
use App\Models\Pivot\UsersTags;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class SharedTag extends ModalComponent
{


    public $email = '';

    public string $tag_id = '';
    public $shared;

    public $tag;

    public $checkbox_public;

    public function add()
    {
        $validated = $this->validate([
            'email' => ['required', 'email', new Me]
        ]);

        $email = \App\Models\Email::firstOrCreate(['email' => $validated['email']]);

        $email->users()->attach(Auth::user());

        $usersTags = new UsersTags();
        $usersTags->user_id = Auth::id();
        $usersTags->email_id = $email->id;
        $usersTags->tag_id = $this->tag_id;

        $usersTags->save();

        $this->email = '';
    }


    public function close()
    {
        $this->closeModal();
    }

    public function delete($id)
    {
        $usersTags = UsersTags::find($id);
        $usersTags->delete();
    }

    public function publicAlbum()
    {
        $tag = \App\Models\Tag::find($this->tag_id);

        if (empty($tag->public_url)) {
            $tag->public_url = Str::uuid();
        }

        $tag->is_public = $this->checkbox_public;

        $tag->save();
    }

    public function changePublicUrl()
    {
        $tag = \App\Models\Tag::find($this->tag_id);
        $tag->public_url = Str::uuid();
        $tag->save();
    }


    public function mount()
    {
        $this->shared = UsersTags::where('tag_id', $this->tag_id)->get();

        $this->tag = \App\Models\Tag::find($this->tag_id);

        $this->checkbox_public = $this->tag->is_public;
    }


    public function render()
    {


        return view('livewire.shared-tag');
    }
}