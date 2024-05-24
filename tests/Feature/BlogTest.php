<?php

use Livewire\Volt\Volt;

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});


test('Is blog work without login', function () {
    $response = $this->get('/blog');
    $response->assertRedirect('/login');
});

test('Is blog work with login', function () {
    $response = $this->actingAs($this->user)->get('/blog');
    $response->assertStatus(200);
});


test('Create post valid', function () {
    $response = $this->actingAs($this->user)->get('/blog');

    Volt::test('blog.create')
        ->call('addPost')
        ->assertHasErrors('title');
});

test('Can create and edit post', function () {
    $response = $this->actingAs($this->user)->get('/blog');

    Volt::test('blog.create')
        ->set('title', 'Test title')
        ->set('content', 'Test content')
        ->set('date', '2021-01-01')
        ->call('addPost');
    $response->assertStatus(200);

    $blog =  \App\Models\Post::where('user_id', $this->user->id)->first();

    Volt::test('blog.edit', ['uuid' => $blog->uuid])
        ->set('title', 'Test title change')
        ->set('content', 'Test content change')
        ->set('date', '2024-01-01')
        ->call('addPost');
    $response->assertStatus(200);
});
