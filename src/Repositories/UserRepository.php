<?php

namespace p4scu41\BaseCRUDApi\Repositories;

use p4scu41\BaseCRUDApi\Models\User;

/**
 * User Repository Class
 *
 * @category Repositories
 * @package  p4scu41\BaseCRUDApi\Repositories
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-05-29
 */
class UserRepository extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }
}
