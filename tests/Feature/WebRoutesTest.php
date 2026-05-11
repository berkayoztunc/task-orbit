<?php

use App\Models\Internship;
use App\Models\User;

test('guests are redirected to login for companies page', function () {
    $this->get(route('page.companies'))->assertRedirect(route('login'));
});

test('guests are redirected to login for internships page', function () {
    $this->get(route('page.internships'))->assertRedirect(route('login'));
});

test('authenticated users can visit companies page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('page.companies'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('companies/index'));
});

test('authenticated users can visit internships index page', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('page.internships'))
        ->assertOk()
        ->assertInertia(fn ($page) => $page->component('internships/index'));
});

test('authenticated users can visit internship show page', function () {
    $user = User::factory()->create();
    $internship = Internship::factory()->create();

    $this->actingAs($user)
        ->get(route('page.internships.show', $internship))
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('internships/show')
                ->has('internshipId'),
        );
});

test('authenticated users can visit internship lessons page', function () {
    $user = User::factory()->create();
    $internship = Internship::factory()->create();

    $this->actingAs($user)
        ->get(route('page.internships.lessons', $internship))
        ->assertOk()
        ->assertInertia(
            fn ($page) => $page
                ->component('internships/lessons')
                ->has('internshipId'),
        );
});
