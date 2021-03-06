<?php

namespace App\Repositories;

use Prettus\Repository\Criteria\RequestCriteria;
use App\Entities\User;

/**
 * Class UserRepositoryEloquent.
 */
class UserRepositoryEloquent extends ExtendedRepository implements UserRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
        'email',
    ];

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * return the presenter to use for this repository.
     *
     * @return string
     */
    public function presenter()
    {
        return 'App\Presenters\UserPresenter';
    }
}
