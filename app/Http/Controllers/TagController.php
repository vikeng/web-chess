<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;

use App\Repositories\TagRepository;
use App\Filters\VisibleFilter;

use Illuminate\Http\Request;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller {

	/**
	 * The Tag Repository
	 *
	 * @var TagRepository
	 */
	protected $tags;

	/**
	 * Instantiate a new TagController
	 *
	 * @param TagRepository $tagRepo
	 * @return void
	 */
	public function __construct(TagRepository $tagRepo)
	{
		$this->tags = $tagRepo;
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(VisibleFilter $filter)
	{
		$this->tags->addFilter($filter);
		return $this->tags->all();
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(StoreTagRequest $request)
	{
		$this->authorize();
		return $this->tags->create(array_merge($request->json('data'), ['owner_id' => Auth::user()->id]));
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$tag = $this->tags->skipPresenter()->find($id);
		$this->authorize($tag);
		return $tag->presenter();
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(UpdateTagRequest $request, $id)
	{
		$this->authorize($this->tags->skipPresenter()->find($id));
		$data = $request->json('data');
		unset($data['owner_id']);
		return $this->tags->update($request->json('data'), $id);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->authorize($this->tags->skipPresenter()->find($id));
		if ($this->tags->delete($id)) {
			return response('', 204);
		}

		return response('', 500);
	}

}
