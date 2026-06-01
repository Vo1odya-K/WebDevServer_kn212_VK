<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Repositories\BlogPostRepository;
use App\Repositories\BlogCategoryRepository;
use App\Http\Requests\BlogPostUpdateRequest;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\BlogPost;
use App\Http\Requests\BlogPostCreateRequest;


class PostController extends BaseController
{
    public function __construct(
        private BlogPostRepository $blogPostRepository,
        private BlogCategoryRepository $blogCategoryRepository)
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();

        return $paginator;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();

        // Створюємо об'єкт через модель. Обсервер автоматично додасть user_id та content_html
        $item = BlogPost::create($data);

        if ($item) {
            return response()->json([
                'success' => true,
                'message' => 'Успішно збережено',
                'item' => $item
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BlogPostUpdateRequest  $request, string $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) { //якщо ід не знайдено
            return ['message' => "Запис id=[{$id}] не знайдено"];
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми

        $result = $item->update($data); //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return [
                'success' => true,
                'message' => 'Успішно збережено'
            ];
        } else {
            return ['message' => 'Помилка збереження'];
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $result = BlogPost::destroy($id);

        if ($result) {
            return response()->json([
                'success' => true,
                'message' => "Запис з id=[{$id}] успішно видалено (м'яке видалення)"
            ], 200);
        } else {
            return response()->json(['message' => 'Помилка видалення або запис не існує'], 400);
        }
    }
}
