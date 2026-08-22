<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use function Pest\Laravel\get;

test('cannot be accessed by guest', function () {
    get(route('super_admin.admins.index'))
        ->assertRedirect(route('login'));
});

test('need manage admin permission to access', function () {
    $user = User::factory()->create();
    setPermissionsTeamId(1); // Assume 1 as a global team id
    $user->assignRole('super admin');
    loginAsUser($user);
    get(route('super_admin.admins.index'))
        ->assertOk();
});

test('cannot be accessed without manage admin permission', function () {
    $user = User::factory()->create();
    loginAsUser($user);
    get(route('super_admin.admins.index'))
        ->assertForbidden();

});

describe('when accessing the page', function () {
    beforeEach(function () {
        $this->admins = User::factory()->create([
            'name' => 'admin 1',
            'email' => 'admin1@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
        $this->user = User::factory()->create();
        $this->user->assignRole('super admin');
        loginAsUser($this->user);
    });

});
