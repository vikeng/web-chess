<?php namespace App\Http\Controllers;

use Auth;
use App\Repositories\TagRepository;
use App\Criteria\VisibleTagCriterion;
use App\Entities\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * The Tag Repository.
     *
     * @var TagRepository
     */
    protected $tags;

    /**
     * The name of the presenter to use for summary representation.
     *
     * @var string
     */
    protected $summaryPresenter = 'App\Presenters\TagSummaryPresenter';

    /**
     * Instantiate a new TagController.
     *
     * @param TagRepository $tagRepo
     *
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
    public function index(VisibleTagCriterion $criterion)
    {
        $this->tags->pushCriteria($criterion);
        $this->tags->setPresenter($this->summaryPresenter);

        return $this->tags->paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreTagRequest $request)
    {
        $this->authorize('store', Tag::class);

        return $this->tags->create(array_merge($request->json('data'), ['owner_id' => Auth::user()->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tag = $this->tags->skipPresenter()->find($id);
        if ($tag->public < 1) {
            $this->authorize($tag);
        }

        return $tag->presenter();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(UpdateTagRequest $request, $id)
    {
        $tag = $this->tags->skipPresenter()->find($id);
        if ($tag->public < 3) {
            $this->authorize($tag);
        }
        $data = $request->json('data');
        unset($data['owner_id']);

        return $this->tags->skipPresenter(false)->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tag = $this->tags->skipPresenter()->find($id);
        if ($tag->public < 3) {
            $this->authorize($tag);
        }
        if ($this->tags->delete($id)) {
            return response('', 204);
        }

        return response('', 500);
    }
}
