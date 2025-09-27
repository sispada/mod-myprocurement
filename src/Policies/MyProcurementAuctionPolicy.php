<?php

namespace Module\MyProcurement\Policies;

use Module\System\Models\SystemUser;
use Module\MyProcurement\Models\MyProcurementAuction;
use Illuminate\Auth\Access\Response;

class MyProcurementAuctionPolicy
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
        return $user->hasPermission('view-myprocurement-auction');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(SystemUser $user, MyProcurementAuction $myProcurementAuction): bool
    {
        return $user->hasPermission('show-myprocurement-auction');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(SystemUser $user): bool
    {
        return $user->hasPermission('create-myprocurement-auction');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(SystemUser $user, MyProcurementAuction $myProcurementAuction): bool
    {
        return $user->hasPermission('update-myprocurement-auction');
    }

    /**
     * Determine whether the user can submitted the model.
     */
    public function submitted(SystemUser $user, MyProcurementAuction $myProcurementAuction): bool
    {
        return
            $user->hasLicenseAs('myprocurement-ppk') &&
            $user->hasPermission('update-myprocurement-auction') &&
            ($myProcurementAuction->status === 'DRAFTED' || $myProcurementAuction->status === 'REJECTED');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(SystemUser $user, MyProcurementAuction $myProcurementAuction): bool
    {
        return $user->hasPermission('delete-myprocurement-auction');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(SystemUser $user, MyProcurementAuction $myProcurementAuction): bool
    {
        return $user->hasPermission('restore-myprocurement-auction');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function destroy(SystemUser $user, MyProcurementAuction $myProcurementAuction): bool
    {
        return $user->hasPermission('destroy-myprocurement-auction');
    }
}
