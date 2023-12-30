<?php

namespace App\Http\Controllers;

use App\Services\LessonService;
use Illuminate\Http\Request;
use OpenSwoole\Core\Coroutine\WaitGroup;

class LessonController extends Controller
{
    public function index(Request $request, LessonService $lessonService)
    {
        $wg = new WaitGroup();
        $result = [];

        for ($id = 1; $id <= 10; $id++) {
            // add to wait-group
            $wg->add();

            go(function () use ($lessonService, $id, $wg, &$result) {
                $start = microtime(true);
                $lessonService->find($id);
                $end = microtime(true);
                $result[$id] = sprintf('%.2f ms', ($end - $start) * 1000);

                // done
                $wg->done();
            });
        }

        // wait for all for 10 seconds
        $wg->wait(10);

        return $result;
    }
}
