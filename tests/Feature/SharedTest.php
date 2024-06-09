<?php

beforeEach(function () {
    $this->user = \App\Models\User::factory()->create();
});

test('Is trash work without login', function () {
    $response = $this->get('/shared');
    $response->assertRedirect('/login');
});

test('Is blog work with login', function () {
    $response = $this->actingAs($this->user)->get('/shared');
    $response->assertStatus(200);
});
