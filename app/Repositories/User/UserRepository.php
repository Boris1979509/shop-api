<?php

namespace App\Repositories\User;

use App\Models\User as Model;
use App\Repositories\Chart;
use App\Repositories\CoreRepository;

/**
 * Class UserRepository
 * @package App\Repositories\User
 */
class UserRepository extends CoreRepository implements UserRepositoryInterface
{
    use Chart;

    /**
     * @return string
     */
    protected function getModelClass(): string
    {
        return Model::class;
    }

    /**
     * @param string $status
     * @return mixed
     */
    public function getByStatus(string $status)
    {
        $query = $this->startConditions()
            ->where('status', $status);
        return $this->getChart($query);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->startConditions()->count();
    }
}
