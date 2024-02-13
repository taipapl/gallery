<?php

namespace App\Livewire;

use App\Mail\AlbumShared;
use App\Models\pivot\UsersTags;
use App\Rules\Me;
use App\Rules\OneEmail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use LivewireUI\Modal\ModalComponent;

class SharedTag extends ModalComponent
{
    public $email = '';

    public $tagId;

    public $shared;

    public $tag;

    public $checkbox_public;

    public function add()
    {
        $validated = $this->validate([
            'email' => ['required', 'email', new OneEmail($this->tagId), new Me],
        ]);

        $email = \App\Models\Email::firstOrCreate(['email' => $validated['email']]);

        $email->users()->attach(Auth::user());

        $usersTags = new UsersTags();
        $usersTags->user_id = Auth::id();
        $usersTags->email_id = $email->id;
        $usersTags->tag_id = $this->tagId;

        $usersTags->save();

        $this->shared = UsersTags::where('tag_id', $this->tagId)->get();
        $this->email = '';

        Mail::to($validated['email'])->send(new AlbumShared($this->tag, $usersTags));
    }

    public function close()
    {
        $this->closeModal();
    }

    public function delete(UsersTags $usersTags)
    {
        $usersTags->delete();

        $this->shared = UsersTags::where('tag_id', $this->tagId)->get();
    }

    public function publicAlbum()
    {
        $tag = \App\Models\Tag::find($this->tagId);

        if (empty($tag->public_url)) {
            $tag->public_url = Str::uuid();
        }

        $tag->is_public = $this->checkbox_public;

        $tag->save();

        $this->tag = $tag;
    }

    public function changePublicUrl()
    {
        $tag = \App\Models\Tag::find($this->tagId);
        $tag->public_url = Str::uuid();
        $tag->save();
    }

    public function mount()
    {
        $this->shared = UsersTags::where('tag_id', $this->tagId)->get();

        $this->tag = \App\Models\Tag::find($this->tagId);

        $this->checkbox_public = $this->tag->is_public;
    }

    public static function modalMaxWidth(): string
    {
        return '7xl';
    }

    public function render()
    {

        return view('livewire.shared-tag');
    }
}