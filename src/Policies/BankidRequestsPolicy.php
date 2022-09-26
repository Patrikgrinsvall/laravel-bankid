<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BankidRequests;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankidRequestsPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the bankidRequests can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list allbankidrequests');
    }

    /**
     * Determine whether the bankidRequests can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidRequests  $model
     * @return mixed
     */
    public function view(User $user, BankidRequests $model)
    {
        return $user->hasPermissionTo('view allbankidrequests');
    }

    /**
     * Determine whether the bankidRequests can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create allbankidrequests');
    }

    /**
     * Determine whether the bankidRequests can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidRequests  $model
     * @return mixed
     */
    public function update(User $user, BankidRequests $model)
    {
        return $user->hasPermissionTo('update allbankidrequests');
    }

    /**
     * Determine whether the bankidRequests can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidRequests  $model
     * @return mixed
     */
    public function delete(User $user, BankidRequests $model)
    {
        return $user->hasPermissionTo('delete allbankidrequests');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidRequests  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete allbankidrequests');
    }

    /**
     * Determine whether the bankidRequests can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidRequests  $model
     * @return mixed
     */
    public function restore(User $user, BankidRequests $model)
    {
        return false;
    }

    /**
     * Determine whether the bankidRequests can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidRequests  $model
     * @return mixed
     */
    public function forceDelete(User $user, BankidRequests $model)
    {
        return false;
    }
}
