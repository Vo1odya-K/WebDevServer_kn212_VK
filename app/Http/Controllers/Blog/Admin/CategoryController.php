<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use Illuminate\Support\Str;


class CategoryController extends BaseController
{
    public function __construct(private BlogCategoryRepository $blogCategoryRepository)
    {
        //parent::__construct();

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return $paginator;
        //dd(__METHOD__);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

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
     * Display the specified resource.
     */
    public function show(string $id)
    {
//        dd(__METHOD__);
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
            return response()->json(['success' => 'Успішно збережено', 'item' => $item], 200);
        } else {
            return response()->json(['msg' => 'Помилка збереження'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
//        dd(__METHOD__);
    }
}
