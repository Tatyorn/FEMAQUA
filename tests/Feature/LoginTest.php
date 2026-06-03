<?php

use App\Models\User;
use Illuminate\Validation\ValidationException;

use function Pest\Laravel\postJson;

it('should authenticate successfully', function () {
    $user = User::factory()->create(['password' => 'teste']);

    postJson(route('login'), [
        'email' => $user->email,
        'password' => 'teste',
    ])->assertOk();
});

it('should throw exception', function () {
    $user = User::factory()->create(['password' => 'teste']);

    postJson(route('login'), [
        'email' => $user->email,
        'password' => 'outro',
    ])->assertJsonValidationErrors(['email']);
})->throws(ValidationException::class);
