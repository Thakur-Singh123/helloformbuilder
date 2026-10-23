<?php

namespace App\Policies;

use App\Models\CustomForm;
use App\Models\User;
use App\Models\Client;

/**
 * Laravel auto-discovers this policy for CustomForm.
 * Must NOT return false for everything — use FormPolicy logic.
 */
class CustomFormPolicy extends FormPolicy
{
    public function viewAny(User|Client $user): bool
    {
        return parent::viewAny($user);
    }

    public function view(User|Client $user, CustomForm $form): bool
    {
        return parent::view($user, $form);
    }

    public function create(User|Client $user): bool
    {
        return parent::create($user);
    }

    public function update(User|Client $user, CustomForm $form): bool
    {
        return parent::update($user, $form);
    }

    public function delete(User|Client $user, CustomForm $form): bool
    {
        return parent::delete($user, $form);
    }
}
