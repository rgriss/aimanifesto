<?php

use Inertia\Testing\AssertableInertia as Assert;

test('homepage includes manifesto version in shared props', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->has('manifestoVersion')
        ->where('manifestoVersion', fn ($version) => is_string($version) && strlen($version) > 0)
    );
});

test('homepage includes manifesto last updated date in shared props', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->has('manifestoLastUpdated')
        ->where('manifestoLastUpdated', fn ($date) => is_string($date) && strlen($date) > 0)
    );
});

test('manifesto version is in correct format', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->where('manifestoVersion', fn ($version) =>
            preg_match('/^\d+\.\d+(\.\d+)?$/', $version) === 1
        )
    );
});

test('manifesto last updated date is in correct format', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->where('manifestoLastUpdated', fn ($date) =>
            preg_match('/^[A-Za-z]+ \d{1,2}, \d{4}$/', $date) === 1
        )
    );
});

test('manifesto version matches config value', function () {
    // This test verifies the config-based version is being used
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertInertia(fn (Assert $page) => $page
        ->where('manifestoVersion', config('app.version'))
    );
});
