<?php

namespace App\Policies;

use App\Models\FileModel;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FilePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isApproved();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FileModel $fileModel): bool
    {
        if (!$user->isApproved()) {
            return false;
        }

        // Admin can view all files
        if ($user->isAdmin()) {
            return true;
        }

        // User can view their own files
        if ($user->id === $fileModel->uploaded_by) {
            return true;
        }

        // User can view files from same instansi/unit
        if ($user->instansi && $fileModel->uploader && $user->instansi === $fileModel->uploader->instansi) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isApproved();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FileModel $fileModel): bool
    {
        return $user->isApproved() && ($user->isAdmin() || $user->id === $fileModel->uploaded_by);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FileModel $fileModel): bool
    {
        return $user->isApproved() && ($user->isAdmin() || $user->id === $fileModel->uploaded_by);
    }

    /**
     * Determine whether the user can download the model.
     */
    public function download(User $user, FileModel $fileModel): bool
    {
        if (!$user->isApproved()) {
            return false;
        }

        // Admin can download all files
        if ($user->isAdmin()) {
            return true;
        }

        // User can download their own files
        if ($user->id === $fileModel->uploaded_by) {
            return true;
        }

        // User can download files from same instansi/unit
        if ($user->instansi && $fileModel->uploader && $user->instansi === $fileModel->uploader->instansi) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FileModel $fileModel): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FileModel $fileModel): bool
    {
        return false;
    }
}
