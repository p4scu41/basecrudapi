<?php

namespace p4scu41\BaseCRUDApi\Http\Controllers\Api\V1;

use p4scu41\BaseCRUDApi\Repositories\UserRepository;

/**
 * Controllers Base Class
 *
 * @category Controllers
 * @package  p4scu41\BaseCRUDApi\Http\Controllers\Api\V1
 * @author   Pascual Pérez <pasperezn@gmail.com>
 * @created  2018-04-04
 */
class UserController extends BaseApiController
{
    /**
     * @param p4scu41\BaseCRUDApi\Repositories\UserRepository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
