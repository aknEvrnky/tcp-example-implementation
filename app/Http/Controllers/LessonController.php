<?php

namespace App\Http\Controllers;

use App\Services\LessonService;
use Illuminate\Http\Request;
use OpenSwoole\Core\Coroutine\WaitGroup;

class LessonController extends Controller
{
    public function index(Request $request, LessonService $lessonService)
    {
        $limit = 10;
        $elapsedTime = [];

        for ($i = 1; $i <= $limit; $i++) {
            $start = microtime(true);
            $lessonService->find($i);
            $end = microtime(true);
            $elapsedTime[$i] = $end - $start;
        }

        // return results as json
        return response()->json(collect($elapsedTime)->map(fn($item, $id) => [
            'id' => $id,
            'elapsed_time' => $item
        ])->values());
    }

    public function indexCoroutine(LessonService $lessonService)
    {
        $wg = new WaitGroup();
        $result = [];

        for ($id = 1; $id <= 10; $id++) {
            go(function () use ($lessonService, $id, $wg, &$result) {
                $wg->add();

                $start = microtime(true);
                $lessonService->find($id);
                $end = microtime(true);
                $result[$id] = sprintf('%.2f ms', ($end - $start) * 1000);

                $wg->done();
            });
        }

        $wg->wait(10);

        return $result;
    }

}
