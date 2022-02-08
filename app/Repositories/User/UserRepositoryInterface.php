<?php

namespace App\Repositories\User;
/**
 * Interface UserRepositoryInterface
 * @package App\Repositories\User
 */
interface UserRepositoryInterface
{
    /**
     * @param string $status
     * @return mixed
     */
    public function getByStatus(string $status);

    /**
     * @return int
     */
    public function count(): int;

}
