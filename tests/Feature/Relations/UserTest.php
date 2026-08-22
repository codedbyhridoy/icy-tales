<?php

use App\Models\Pharmacy;
use App\Models\User;

test('owner user has one pharmacy', function () {
    $user = User::factory()->has(Pharmacy::factory())->create();
    expect($user->pharmacy)->toBeInstanceOf(Pharmacy::class);
    expect($user->id)->toBe($user->pharmacy->user_id);
});
