<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Client;
use App\Models\FormSubmission;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if the user can view any submissions.
     */
    public function viewAny(User|Client $user): bool
    {
        // All authenticated users can view submissions
        return true;
    }

    /**
     * Determine if the user can view the submission.
     */
    public function view(User|Client $user, FormSubmission $submission): bool
    {
        // Super Admin can view all submissions
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin can view submissions for their shortcodes/forms
        if ($user->isAdmin() && $submission->admin_id == $user->id) {
            return true;
        }

        // Admin can also view submissions that belong to their client (e.g. from client profile or global forms)
        if ($user->isAdmin() && $submission->client_id) {
            $client = $submission->relationLoaded('client') ? $submission->client : $submission->client()->first();
            if ($client && $client->admin_id == $user->id) {
                return true;
            }
        }

        // Client (website owner) can view submissions from their website
        if ($user->isClient() && $submission->client_id == $user->id) {
            return true;
        }

        // Regular users can view submissions for forms they have access to
        if ($user->isRegularUser() && $submission->admin_id == $user->admin_id) {
            // Additional check: user should have created/edited the form
            if ($submission->user_id == $user->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the user can create submissions.
     * This is typically public (via shortcode), so we allow it
     */
    public function create(User $user = null): bool
    {
        // Submissions can be created publicly via shortcode
        return true;
    }

    /**
     * Determine if the user can update the submission.
     */
    public function update(User|Client $user, FormSubmission $submission): bool
    {
        // Generally, submissions should not be updated after creation
        // But if needed, same rules as view apply
        return $this->view($user, $submission);
    }

    /**
     * Determine if the user can delete the submission.
     */
    public function delete(User|Client $user, FormSubmission $submission): bool
    {
        // Super Admin can delete all submissions
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin can delete submissions for their forms
        if ($user->isAdmin() && $submission->admin_id == $user->id) {
            return true;
        }

        // Admin can delete submissions that belong to their client
        if ($user->isAdmin() && $submission->client_id) {
            $client = $submission->relationLoaded('client') ? $submission->client : $submission->client()->first();
            if ($client && $client->admin_id == $user->id) {
                return true;
            }
        }

        // Client can delete submissions from their website
        if ($user->isClient() && $submission->client_id == $user->id) {
            return true;
        }

        return false;
    }
}
