<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Chart
 * @package App\Repositories
 */
trait Chart
{
    /**
     * @param Builder $model
     * @param string $column
     * @return mixed
     */
    public function getChart(Builder $model, string $column = 'created_at')
    {
        return $model->selectRaw('COUNT(*) AS count, MONTHNAME(' . $column . ') AS month, max(' . $column . ') as createdAt')
            ->whereYear($column, now()->year)
            ->orderBy('createdAt')
            ->groupBy('month')
            ->pluck('count', 'month');
    }
}
