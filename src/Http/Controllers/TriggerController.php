<?php

namespace Fintech\Bell\Http\Controllers;

use Exception;
use Fintech\Bell\Facades\Bell;
use Fintech\Bell\Http\Resources\TriggerCollection;
use Fintech\Bell\Http\Resources\TriggerResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class TriggerController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *TemplateController* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(Request $request): TriggerCollection|JsonResponse
    {
        try {
            $inputs = $request->all();

            $triggers = Bell::trigger()->list($inputs);

            return new TriggerCollection($triggers);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *TemplateController* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): TriggerResource|JsonResponse
    {
        try {

            $trigger = Bell::trigger()->find($id);

            if (! $trigger) {
                throw (new ModelNotFoundException)->setModel('Trigger', $id);
            }

            return new TriggerResource($trigger);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *TemplateController* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function sync(): JsonResponse
    {
        try {

            Bell::trigger()->sync();

            return response()->success('Trigger Cache Synced');

        } catch (Exception $exception) {
            return response()->failed($exception);
        }
    }
}
