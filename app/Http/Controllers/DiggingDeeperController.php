<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use Carbon\Carbon;
use App\Jobs\ProcessVideoJob;

class DiggingDeeperController extends Controller
{
    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

        //dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());

        /**
         * @var \Illuminate\Support\Collection $collection
         * 
         * создание коллекции с помощью хелперской функции collect
         * на основе данных, передаваемых в аргументе collect(),
         * без данных получится пустая коллекция.
         */
        $collection = collect($eloquentCollection->toArray());

        //  dd(
        //         get_class($eloquentCollection),
        //         get_class($collection),
        //         $collection
        //     );

        //        $result['first'] = $collection->first();
        //        $result['last'] = $collection->last();

        //  dd($result); 

        // выбрать те статьи, у которых категория id = 10
        // $result['where']['data'] = $collection
        //    ->where('category_id', 10)
        //    ->values()
        //    ->keyBy('id');         

        // $result['where']['count'] = $result['where']['data']->count();
        // $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
        // $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();       

        // $result['first_where'] = $collection
        //    ->firstWhere('created_at', '>','2020-01-01');

        // dd($result);   



        // Метод map() . Базовая переменная не изменится, вернётся изменённая версия.
        // $result['map']['all'] = $collection->map(function ($item){
        //   $newItem = new \stdClass();
        //   $newItem->item_id = $item['id'];
        //   $newItem->item_name = $item['title'];
        //   $newItem->exist = is_null($item['deleted_at']);

        //   return $newItem;
        // });

         

        // $result['map']['not_exists'] = $result['map']['all']
        //    ->where('exist', '=', false)
        //    ->values()
        //    ->keyBy('item_id');

        // dd($result);

        // Метод transform() . Базовая переменная изменится (трансформируется).
        // $collection->transform(function (array $item) {
        //    $newItem = new \stdClass();
        //    $newItem->item_id = $item['id'];
        //    $newItem->item_name = $item['title'];
        //    $newItem->exist = is_null($item['deleted_at']);
        //    $newItem->created_at = Carbon::parse($item['created_at']);

        //    return $newItem;
        // });
        

        dd($collection);
       

    }

    public function processVideo() 
    {
        ProcessVideoJob::dispatch();
    }

    public function prepareCatalog() {
        //GenerateCatalogMainJob::dispatch();
    }
    


}
