<?php

namespace App\Policies;

use App\Models\ContentItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContentItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContentItem $contentItem): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->id !== null;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContentItem $contentItem): bool
    {
        return $user->is_admin || $user->id === $contentItem->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContentItem $contentItem): bool
    {
        return $user->is_admin || $user->id === $contentItem->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContentItem $contentItem): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContentItem $contentItem): bool
    {
        return false;
    }
}
