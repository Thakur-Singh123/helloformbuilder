<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use App\Models\CustomForm;
use Illuminate\Auth\Access\HandlesAuthorization;

class FormPolicy
{
    use HandlesAuthorization;

    protected function canAccessForm(User|Client $user, CustomForm $form): bool
    {
        if ($user->isClient()) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            if ((int) $form->admin_id === (int) $user->id) {
                return true;
            }
            if ((int) $form->user_id === (int) $user->id) {
                return true;
            }
            if ($form->shortcodes()->where('user_id', $user->id)->exists()) {
                return true;
            }
            return false;
        }

        if ($user->isRegularUser()) {
            if ($form->admin_id && (int) $form->admin_id === (int) $user->admin_id) {
                return true;
            }
            if ((int) $form->user_id === (int) $user->id) {
                return true;
            }
        }

        return false;
    }

    public function viewAny(User|Client $user): bool
    {
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isClient()) {
            return true;
        }

        return $user->isRegularUser();
    }

    public function view(User|Client $user, CustomForm $form): bool
    {
        return $this->canAccessForm($user, $form);
    }

    public function create(User|Client $user): bool
    {
        return !$user->isClient();
    }

    public function update(User|Client $user, CustomForm $form): bool
    {
        return $this->canAccessForm($user, $form);
    }

    public function delete(User|Client $user, CustomForm $form): bool
    {
        return $this->canAccessForm($user, $form);
    }
}
