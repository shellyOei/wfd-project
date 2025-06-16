<?php

namespace App\Policies;

use App\Models\Admin;
use App\Models\DayAvailable;
use Illuminate\Auth\Access\Response;

class DayAvailablePolicy
{
    /**
     * Metode ini akan dijalankan sebelum metode otorisasi lainnya.
     * Ini sangat berguna untuk super admin yang selalu memiliki akses penuh.
     */
    public function before(Admin $admin, string $ability): ?bool
    {
        if ($admin->isSuperAdmin()) {
            return true; // Super admin selalu bisa melakukan apapun
        }

        return null; // Lanjutkan ke metode otorisasi spesifik
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Admin $admin): bool
    {
        // Admin dokter hanya bisa melihat availibilitas yang terkait dengan dirinya
        return $admin->isDoctorAdmin();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Admin $admin, DayAvailable $dayAvailable): bool
    {
        // Admin dokter hanya bisa melihat availibilitas yang terkait dengan doctor_id-nya
        return $admin->doctor_id === $dayAvailable->doctor_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Admin $admin): bool
    {
        // Admin dokter bisa membuat availibilitas untuk dirinya sendiri
        return $admin->isDoctorAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Admin $admin, DayAvailable $dayAvailable): bool
    {
        // Admin dokter hanya bisa memperbarui availibilitas yang terkait dengan doctor_id-nya
        return $admin->doctor_id === $dayAvailable->doctor_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Admin $admin, DayAvailable $dayAvailable): bool
    {
        // Admin dokter hanya bisa menghapus availibilitas yang terkait dengan doctor_id-nya
        return $admin->doctor_id === $dayAvailable->doctor_id;
    }

    // Metode restore dan forceDelete (jika Anda menggunakan soft deletes)
    public function restore(Admin $admin, DayAvailable $dayAvailable): bool
    {
        return false; // Hanya super admin yang bisa, tapi sudah dihandle di before
    }

    public function forceDelete(Admin $admin, DayAvailable $dayAvailable): bool
    {
        return false; // Hanya super admin yang bisa, tapi sudah dihandle di before
    }
}