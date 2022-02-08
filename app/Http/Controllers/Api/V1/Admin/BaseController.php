<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;

/**
 * Class BaseController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class BaseController extends Controller
{
    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * BaseController constructor.
     * @param $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

}
