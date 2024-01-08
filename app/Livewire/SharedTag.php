<?php

namespace App\Livewire;

use App\Rules\Me;
use Livewire\Form;
use App\Models\Email;
use App\Models\Pivot\UsersTags;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use LivewireUI\Modal\ModalComponent;

class SharedTag extends ModalComponent
{


    public $email = '';

    public string $tag_id = '';
    public $shared;

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

    public function render()
    {
        $this->shared = UsersTags::where('tag_id', $this->tag_id)->get();

        return view('livewire.shared-tag');
    }
}
