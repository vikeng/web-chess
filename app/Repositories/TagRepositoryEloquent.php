<?php

namespace App\Repositories;

use App\Repositories\ExtendedRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\TagRepository;
use App\Entities\Tag;

/**
 * Class TagRepositoryEloquent
 * @package namespace App\Repositories;
 */
class TagRepositoryEloquent extends ExtendedRepository implements TagRepository
{
	protected $fieldSearchable = [
		'name' => 'like',
		'owner_id',
	];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Tag::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

	/**
	 * return the presenter to use for this repository
	 *
	 * @return string
	 */
	public function presenter()
	{
		return 'App\Presenters\TagPresenter';
	}
}
