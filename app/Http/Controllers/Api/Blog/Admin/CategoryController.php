<?php

namespace App\Http\Controllers\Api\Blog\Admin;

use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;


class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->blogCategoryRepository->getAllWithPaginate(5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        // КОД ГЕНЕРАЦІЇ СЛАГА ВИДАЛЕНО — ТЕПЕР ПРАЦЮЄ ОБСЕРВЕР

        $item = BlogCategory::create($data);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'item'    => $item
            ], 201);
        } else {
            return response()->json(['message' => 'Помилка збереження'], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);

        if (empty($item)) {
            return response()->json(['message' => 'Запис не знайдено'], 404);
        }

        $data = $request->all();
        $result = $item->update($data);

        if ($result) {
            return response()->json(['success' => true, 'message' => 'Успішно збережено', 'item' => $item], 200);
        } else {
            return response()->json(['message' => 'Помилка збереження'], 400);
        }
    }

    public function show(string $id) {}
    public function destroy(string $id) {}
}
