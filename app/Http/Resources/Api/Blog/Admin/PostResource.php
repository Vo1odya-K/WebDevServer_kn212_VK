<?php

namespace App\Http\Resources\Api\Blog\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PostResource extends JsonResource
{
    /**
     * Трансформація ресурсу в масив.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Безпечно форматуємо дату, навіть якщо вона прийшла як рядок або null
        $publishedAt = $this->published_at;
        if ($publishedAt && !($publishedAt instanceof Carbon)) {
            $publishedAt = Carbon::parse($publishedAt);
        }

        return [
            'id'             => $this->id,
            'title'          => $this->title,
            'slug'           => $this->slug,
            'is_published'   => (bool) $this->is_published,

            // Форматуємо дату для зручності фронтенду
            'date_published' => $publishedAt ? $publishedAt->format('Y-m-d H:i:s') : null,

            'user_id'        => $this->user_id,
            'category_id'    => $this->category_id,

            // Використовуємо зв'язки з безпечною перевіркою через optional() або оператор ?->
            'category_title' => $this->category?->title ?? 'Без категорії',
            'author_name'    => $this->user?->name ?? 'Невідомо',

            'content_html'   => $this->content_html,
            'content_raw'    => $this->content_raw,
        ];
    }
}
