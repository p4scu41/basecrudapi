<?php

namespace p4scu41\BaseCRUDApi\Policies;

use p4scu41\BaseCRUDApi\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Notifications Base Class
 *
 * @category Notifications
 * @package  p4scu41\BaseCRUDApi\Notifications
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class BasePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
