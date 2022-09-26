<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BankidIntegration;
use Illuminate\Auth\Access\HandlesAuthorization;

class BankidIntegrationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the bankidIntegration can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('list bankidintegrations');
    }

    /**
     * Determine whether the bankidIntegration can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidIntegration  $model
     * @return mixed
     */
    public function view(User $user, BankidIntegration $model)
    {
        return $user->hasPermissionTo('view bankidintegrations');
    }

    /**
     * Determine whether the bankidIntegration can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermissionTo('create bankidintegrations');
    }

    /**
     * Determine whether the bankidIntegration can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidIntegration  $model
     * @return mixed
     */
    public function update(User $user, BankidIntegration $model)
    {
        return $user->hasPermissionTo('update bankidintegrations');
    }

    /**
     * Determine whether the bankidIntegration can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidIntegration  $model
     * @return mixed
     */
    public function delete(User $user, BankidIntegration $model)
    {
        return $user->hasPermissionTo('delete bankidintegrations');
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidIntegration  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return $user->hasPermissionTo('delete bankidintegrations');
    }

    /**
     * Determine whether the bankidIntegration can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidIntegration  $model
     * @return mixed
     */
    public function restore(User $user, BankidIntegration $model)
    {
        return false;
    }

    /**
     * Determine whether the bankidIntegration can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\BankidIntegration  $model
     * @return mixed
     */
    public function forceDelete(User $user, BankidIntegration $model)
    {
        return false;
    }
}
