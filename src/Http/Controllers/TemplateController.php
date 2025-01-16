<?php

namespace Fintech\Bell\Http\Controllers;
use Exception;
use Fintech\Core\Exceptions\StoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Bell\Facades\Bell;
use Fintech\RestApi\Http\Resources\Bell\TemplateControllerResource;
use Fintech\RestApi\Http\Resources\Bell\TemplateControllerCollection;
use Fintech\RestApi\Http\Requests\Bell\ImportTemplateControllerRequest;
use Fintech\RestApi\Http\Requests\Bell\StoreTemplateControllerRequest;
use Fintech\RestApi\Http\Requests\Bell\UpdateTemplateControllerRequest;
use Fintech\RestApi\Http\Requests\Bell\IndexTemplateControllerRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Class TemplateController
 * @package Fintech\Bell\Http\Controllers
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to TemplateController
 * @lrd:end
 *
 */

class TemplateController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *TemplateController* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     * @lrd:end
     *
     * @param IndexTemplateControllerRequest $request
     * @return TemplateControllerCollection|JsonResponse
     */
    public function index(IndexTemplateControllerRequest $request): TemplateControllerCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templateControllerPaginate = Bell::templateController()->list($inputs);

            return new TemplateControllerCollection($templateControllerPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a new *TemplateController* resource in storage.
     * @lrd:end
     *
     * @param StoreTemplateControllerRequest $request
     * @return JsonResponse
     * @throws StoreOperationException
     */
    public function store(StoreTemplateControllerRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templateController = Bell::templateController()->create($inputs);

            if (!$templateController) {
                throw (new StoreOperationException)->setModel(config('fintech.bell.template_controller_model'));
            }

            return response()->created([
                'message' => __('restapi::messages.resource.created', ['model' => 'Template Controller']),
                'id' => $templateController->id
             ]);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *TemplateController* resource found by id.
     * @lrd:end
     *
     * @param string|int $id
     * @return TemplateControllerResource|JsonResponse
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): TemplateControllerResource|JsonResponse
    {
        try {

            $templateController = Bell::templateController()->find($id);

            if (!$templateController) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return new TemplateControllerResource($templateController);

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Update a specified *TemplateController* resource using id.
     * @lrd:end
     *
     * @param UpdateTemplateControllerRequest $request
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws UpdateOperationException
     */
    public function update(UpdateTemplateControllerRequest $request, string|int $id): JsonResponse
    {
        try {

            $templateController = Bell::templateController()->find($id);

            if (!$templateController) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            $inputs = $request->validated();

            if (!Bell::templateController()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return response()->updated(__('restapi::messages.resource.updated', ['model' => 'Template Controller']));

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *TemplateController* resource using id.
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     * @throws ModelNotFoundException
     * @throws DeleteOperationException
     */
    public function destroy(string|int $id)
    {
        try {

            $templateController = Bell::templateController()->find($id);

            if (!$templateController) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            if (!Bell::templateController()->destroy($id)) {

                throw (new DeleteOperationException())->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return response()->deleted(__('restapi::messages.resource.deleted', ['model' => 'Template Controller']));

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *TemplateController* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     * @lrd:end
     *
     * @param string|int $id
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $templateController = Bell::templateController()->find($id, true);

            if (!$templateController) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            if (!Bell::templateController()->restore($id)) {

                throw (new RestoreOperationException())->setModel(config('fintech.bell.template_controller_model'), $id);
            }

            return response()->restored(__('restapi::messages.resource.restored', ['model' => 'Template Controller']));

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

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
     * @param IndexTemplateControllerRequest $request
     * @return JsonResponse
     */
    public function export(IndexTemplateControllerRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templateControllerPaginate = Bell::templateController()->export($inputs);

            return response()->exported(__('restapi::messages.resource.exported', ['model' => 'Template Controller']));

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
     * @param ImportTemplateControllerRequest $request
     * @return TemplateControllerCollection|JsonResponse
     */
    public function import(ImportTemplateControllerRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $templateControllerPaginate = Bell::templateController()->list($inputs);

            return new TemplateControllerCollection($templateControllerPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
