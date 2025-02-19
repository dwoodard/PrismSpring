<?php

namespace App\Policies;

use App\Models\Entry;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EntryPolicy
{
    /**
     * Determine whether the user can view any entries.
     */
    public function viewAny(User $user): bool
    {
        // For example, allow all authenticated users to view any entries.
        return true;
    }

    /**
     * Determine whether the user can view the entry.
     */
    public function view(User $user, Entry $entry): bool
    {
        // Allow the owner of the entry or an admin (if applicable) to view the entry.
        return $user->id === $entry->user_id;
    }

    /**
     * Determine whether the user can create entries.
     */
    public function create(User $user): bool
    {
        // Allow any authenticated user to create an entry.
        return true;
    }

    /**
     * Determine whether the user can update the entry.
     */
    public function update(User $user, Entry $entry): bool
    {
        // Only allow the owner of the entry to update it.
        return $user->id === $entry->user_id;
    }

    /**
     * Determine whether the user can delete the entry.
     */
    public function delete(User $user, Entry $entry): bool
    {
        // Only allow the owner of the entry to delete it.
        return $user->id === $entry->user_id;
    }

    /**
     * Determine whether the user can restore the entry.
     */
    public function restore(User $user, Entry $entry): bool
    {
        // Only allow the owner of the entry to restore it.
        return $user->id === $entry->user_id;
    }

    /**
     * Determine whether the user can permanently delete the entry.
     */
    public function forceDelete(User $user, Entry $entry): bool
    {
        // Prevent permanent deletion by default.
        return false;
    }
}
