<?php

use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
    $this->videoUrl = '';
});

test('Is video page work', function () {

    $this->actingAs($this->user)->get('/video')
        ->assertStatus(200);
});


test('Is video valid', function () {

    $response = $this->actingAs($this->user)->get('/albums')
        ->assertStatus(200);

    Volt::test('video.add')
        ->set('video', 'dsfdsfsdfsdf')
        ->call('save')
        ->assertStatus(404);
})->skip('not working yet');


test('Is video add', function () {

    $response = $this->actingAs($this->user)->get('/albums')
        ->assertStatus(200);

    Volt::test('video.add')
        ->set('video', 'https://www.youtube.com/watch?v=SaMoPZwdOCY')
        ->call('changeURL')
        ->call('save')
        ->assertStatus(200);
});
