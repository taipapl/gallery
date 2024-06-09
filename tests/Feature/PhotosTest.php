<?php

use App\Livewire\FileUploads;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;


beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});


test('Is video page work', function () {

    $this->actingAs($this->user)->get('/photos')
        ->assertStatus(200);
});

test('Update photos', function () {

    $this->actingAs($this->user)->get('/photos');

    $file1 = UploadedFile::fake()->image('photo1.jpg');
    $file2 = UploadedFile::fake()->image('photo2.jpg');

    Livewire::test(FileUploads::class)
        ->set('photos', [$file1, $file2]);

    Storage::disk('local')->assertExists('tmp-for-tests/' . $file1->hashName());
})->skip('not working yet');
