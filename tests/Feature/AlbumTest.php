<?php

use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});

test('Is albums work without login', function () {
    $response = $this->get('/albums');
    $response->assertRedirect('/login');
});

test('Is albums work with login', function () {
    $response = $this->actingAs($this->user)->get('/albums');
    $response->assertStatus(200);
});

test('Create album', function () {

    $response = $this->actingAs($this->user)->get('/albums');
    Volt::test('albums.create')
        ->call('createAlbum');

    $album = \App\Models\Tag::where('user_id', $this->user->id)->first();
    $response = $this->actingAs($this->user)->get('/album/' . $album->uuid);

    $response->assertStatus(200);
});