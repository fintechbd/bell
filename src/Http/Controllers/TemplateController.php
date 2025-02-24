<?php

namespace Fintech\Bell\Http\Controllers;

use Exception;
use Fintech\Bell\Facades\Bell;
use Fintech\Bell\Http\Requests\ImportTemplateRequest;
use Fintech\Bell\Http\Requests\IndexTemplateRequest;
use Fintech\Bell\Http\Requests\StoreTemplateRequest;
use Fintech\Bell\Http\Requests\UpdateTemplateRequest;
use Fintech\Bell\Http\Resources\TemplateCollection;
use Fintech\Bell\Http\Resources\TemplateResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class TemplateController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to TemplateController
 *
 * @lrd:end
 */
class TemplateController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *TemplateController* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexTemplateRequest $request): TemplateCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templatePaginate = Bell::template()->list($inputs);

            return new TemplateCollection($templatePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *TemplateController* resource in storage.
     *
     * @lrd:end
     *
     * @throws StoreOperationException
     */
    public function store(StoreTemplateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $template = Bell::template()->create($inputs);

            if (! $template) {
                throw (new StoreOperationException)->setModel(config('fintech.bell.template_controller_model'));
            }

            return response()->created([
                'message' => __('core::messages.resource.created', ['model' => 'Template']),
                'id' => $template->id,
            ]);

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
    public function show(string|int $id): TemplateResource|JsonResponse
    {
        try {

            $template = Bell::template()->find($id);

            if (! $template) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return new TemplateResource($template);

        }  catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *TemplateController* resource using id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateTemplateRequest $request, string|int $id): JsonResponse
    {
        try {

            $template = Bell::template()->find($id);

            if (! $template) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            $inputs = $request->validated();

            if (! Bell::template()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Template']));

        }  catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *TemplateController* resource using id.
     *
     * @lrd:end
     *
     * @return JsonResponse
     *
     * @throws ModelNotFoundException
     * @throws DeleteOperationException
     */
    public function destroy(string|int $id)
    {
        try {

            $template = Bell::template()->find($id);

            if (! $template) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            if (! Bell::template()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Template']));

        }  catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *TemplateController* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $template = Bell::template()->find($id, true);

            if (! $template) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            if (! Bell::template()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Template']));

        }  catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *TemplateController* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexTemplateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templatePaginate = Bell::template()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Template']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *TemplateController* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return TemplateCollection|JsonResponse
     */
    public function import(ImportTemplateRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templatePaginate = Bell::template()->list($inputs);

            return new TemplateCollection($templatePaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
