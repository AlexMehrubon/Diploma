<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $perPage = request('per_page', 10);
        return TagResource::collection(Tag::paginate($perPage))->response();
    }


    /**
     * @param StoreTagRequest $request
     * @return void
     */
    public function store(StoreTagRequest $request)
    {
        //
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function show(Tag $tag)
    {
        //
    }


    /**
     * @param UpdateTagRequest $request
     * @param Tag $tag
     * @return void
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        //
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function destroy(Tag $tag)
    {
        //
    }
}
