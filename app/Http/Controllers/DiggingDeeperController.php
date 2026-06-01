<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());



        $result['first'] = $collection->first();
        $result['last'] = $collection->last();

        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

        $result['where']['count'] = $result['where']['data']->count();
        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        $result['where_first'] = $collection
            ->firstWhere('created_at', '>' , '2020-02-24 03:46:16');

        $result['map']['all'] = $collection->map(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);

            return $newItem;
        });

        $result['map']['not_exists'] = $result['map']['all']
            ->where('exists', '=', false)
            ->values()
            ->keyBy('item_id');

        $collection->transform(function ($item) {
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });

        $newItem = new \stdClass;
        $newItem->id = 9999;

        $newItem2 = new \stdClass;
        $newItem2->id = 8888;

        $newItemFirst = $collection->prepend($newItem)->first();
        $newItemLast = $collection->push($newItem2)->last();
        $pulledItem = $collection->pull(1);

        $filtered = $collection->filter(function ($item) {
            if (!isset($item->created_at)) {
                return false;
            }
            $byDay = $item->created_at->isFriday();
            $byDate = $item->created_at->day == 11;

            return $byDay && $byDate;
        });

        $sortedSimpleCollection = collect([5, 3, 1, 2, 4])->sort()->values();
        $sortedAscCollection = $collection->sortBy('created_at');
        $sortedDescCollection = $collection->sortByDesc('item_id');

        // Повертаємо масив результатів у форматі JSON, щоб протестувати в браузері
        return response()->json(compact(
            'result',
            'newItemFirst',
            'newItemLast',
            'pulledItem',
            'filtered',
            'sortedSimpleCollection'
        ));
    }
}
