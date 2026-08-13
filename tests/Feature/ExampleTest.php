<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the home page renders for guests', function () {
    $this->get('/')->assertStatus(200);
});
