<?php

namespace Module\MyProcurement\Policies;

use Module\System\Models\SystemUser;
use Module\MyProcurement\Models\MyProcurementHistory;
use Illuminate\Auth\Access\Response;

class MyProcurementHistoryPolicy
{
    /**
    * Perform pre-authorization checks.
    */
    public function before(SystemUser $user, string $ability): bool|null
    {
        if ($user->hasLicenseAs('myprocurement-superadmin')) {
            return true;
        }
    
        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function view(SystemUser $user): bool
    {
        return $user->hasPermission('view-myprocurement-history');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, MyProcurementHistory $myProcurementHistory): bool
    {
        return $user->hasPermission('show-myprocurement-history');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-myprocurement-history');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, MyProcurementHistory $myProcurementHistory): bool
    {
        return $user->hasPermission('update-myprocurement-history');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, MyProcurementHistory $myProcurementHistory): bool
    {
        return $user->hasPermission('delete-myprocurement-history');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, MyProcurementHistory $myProcurementHistory): bool
    {
        return $user->hasPermission('restore-myprocurement-history');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, MyProcurementHistory $myProcurementHistory): bool
    {
        return $user->hasPermission('destroy-myprocurement-history');
    }
}
