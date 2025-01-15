<?php

namespace Fintech\Bell\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Fintech\Bell\Facades\Bell;
use Fintech\Bell\Http\Resources\TriggerCollection;
use Fintech\Core\Traits\HasFindWhereSearch;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AvailableTriggerController extends Controller
{
    use HasFindWhereSearch;

    /**
     * @lrd:start
     * Return a listing of the *Trigger* resource as collection.
     *
     * @lrd:end
     */
    public function __invoke(Request $request): TriggerCollection|JsonResponse
    {
        try {
            $inputs = $request->all();

            $triggers = Bell::trigger()->list($inputs);

            return new TriggerCollection($triggers);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
