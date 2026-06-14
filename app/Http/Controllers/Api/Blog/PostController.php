<?php

namespace App\Http\Controllers\Api\Blog;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class PostController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Додаємо пагінацію по 25 елементів та підвантажуємо зв'язки для таблиці Nuxt UI
        $items = BlogPost::with(['user', 'category'])
            ->orderBy('id', 'desc')
            ->paginate(25);

        return response()->json($items);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
