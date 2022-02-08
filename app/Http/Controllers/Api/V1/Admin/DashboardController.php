<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Models\User;

/**
 * Class DashboardController
 * @package App\Http\Controllers\Api\V1\Admin
 */
class DashboardController extends BaseController
{
    /**
     * @return array
     */
    public function index(): array
    {
        return $this->tags();
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        return [
            'users'      => [
                'count' => $this->userRepository->count(),
                'chart' => [
                    User::STATUS_ACTIVE  => $this->userRepository->getByStatus(User::STATUS_ACTIVE),
                    User::STATUS_PENDING => $this->userRepository->getByStatus(User::STATUS_PENDING),
                ],
            ],
            'products'   => [
                'count' => 0 //$this->productRepository->count(),
            ],
            'categories' => [
                'count' => 0 //$this->categoryRepository->count(),
            ],
        ];
    }

    /**
     * @return array
     */
    public function sidebar(): array
    {
        return [
            'users'      => [
                'count' => $this->userRepository->count(),
            ],
            'products'   => [
                'count' => 0 //$this->productRepository->count(),
            ],
            'categories' => [
                'count' => 0 //$this->categoryRepository->count(),
            ],
        ];
    }
}
