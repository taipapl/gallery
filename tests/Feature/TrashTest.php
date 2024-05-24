<?php

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});

test('Is trash work without login', function () {
    $response = $this->get('/trash');
    $response->assertRedirect('/login');
});

test('Is blog work with login', function () {
    $response = $this->actingAs($this->user)->get('/trash');
    $response->assertStatus(200);
});
