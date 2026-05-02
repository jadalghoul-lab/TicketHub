<?php

use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test('organizer.scanner')
        ->assertStatus(200);
});
