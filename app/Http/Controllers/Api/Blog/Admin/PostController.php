<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;

class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository
    ) {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->blogPostRepository->getAllWithPaginate();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        $item = BlogPost::create($data);

        if ($item) {

            BlogPostAfterCreateJob::dispatch($item);

            return response()->json(['success' => true, 'message' => 'Успішно збережено', 'item' => $item], 201);
        } else {
            return response()->json(['message' => 'Помилка збереження'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostUpdateRequest $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => "Запис id=[{$id}] не знайдено"], 404);
        }

        $data = $request->all();
        $result = $item->update($data);

        if ($result) {
            return response()->json(['success' => true, 'message' => 'Успішно збережено'], 200);
        } else {
            return response()->json(['message' => 'Помилка збереження'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            BlogPostAfterDeleteJob::dispatch($id)->delay(20);

            return response()->json(['success' => true, 'message' => "Запис з id=[{$id}] успішно видалено"]);
        } else {
            return response()->json(['message' => 'Помилка видалення'], 400);
        }
    }

    public function show(string $id) {}
}
