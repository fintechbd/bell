<?php

namespace Fintech\Bell\Http\Controllers;

use Exception;
use Fintech\Bell\Facades\Bell;
use Fintech\Bell\Http\Requests\ImportNotificationRequest;
use Fintech\Bell\Http\Requests\IndexNotificationRequest;
use Fintech\Bell\Http\Resources\NotificationCollection;
use Fintech\Bell\Http\Resources\NotificationResource;
use Fintech\Core\Exceptions\DeleteOperationException;
use Fintech\Core\Exceptions\RestoreOperationException;
use Fintech\Core\Exceptions\UpdateOperationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 * Class NotificationController
 *
 * @lrd:start
 * This class handle create, display, update, delete & restore
 * operation related to Notification
 *
 * @lrd:end
 */
class NotificationController extends Controller
{
    /**
     * @lrd:start
     * Return a listing of the *Notification* resource as collection.
     *
     * *```paginate=false``` returns all resource as list not pagination*
     *
     * @lrd:end
     */
    public function index(IndexNotificationRequest $request): NotificationCollection|JsonResponse
    {
        try {
            $inputs = $request->validated();

            $inputs['notifiable_type'] = config('fintech.auth.user_model', \Fintech\Auth\Models\User::class);
            $inputs['user_id'] = $inputs['user_id'] ?? $request->user('sanctum')->getKey();
            $inputs['type'] = $inputs['type'] ?? \Fintech\Bell\Notifications\DynamicNotification::class;

            $notificationPaginate = Bell::notification()->list($inputs);

            return new NotificationCollection($notificationPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Return a specified *Notification* resource found by id.
     *
     * @lrd:end
     *
     * @throws ModelNotFoundException
     */
    public function show(string|int $id): NotificationResource|JsonResponse
    {
        try {

            $notification = Bell::notification()->find($id);

            if (! $notification) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.notification_controller_model'), $id);
            }

            return new NotificationResource($notification);

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

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
    public function update(Request $request, string|int $id): JsonResponse
    {
        try {

            $template = Bell::notification()->find($id);

            if (! $template) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.notification_model'), $id);
            }

            $inputs = $request->validated();

            if (! Bell::notification()->update($id, $inputs)) {

                throw (new UpdateOperationException)->setModel(config('fintech.bell.notification_model'), $id);
            }

            return response()->updated(__('core::messages.resource.updated', ['model' => 'Notification']));

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Soft delete a specified *Notification* resource using id.
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

            $notification = Bell::notification()->find($id);

            if (! $notification) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.notification_controller_model'), $id);
            }

            if (! Bell::notification()->destroy($id)) {

                throw (new DeleteOperationException)->setModel(config('fintech.bell.notification_controller_model'), $id);
            }

            return response()->deleted(__('core::messages.resource.deleted', ['model' => 'Notification Controller']));

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Restore the specified *Notification* resource from trash.
     * ** ```Soft Delete``` needs to enabled to use this feature**
     *
     * @lrd:end
     *
     * @return JsonResponse
     */
    public function restore(string|int $id)
    {
        try {

            $notification = Bell::notification()->find($id, true);

            if (! $notification) {
                throw (new ModelNotFoundException)->setModel(config('fintech.bell.notification_controller_model'), $id);
            }

            if (! Bell::notification()->restore($id)) {

                throw (new RestoreOperationException)->setModel(config('fintech.bell.notification_controller_model'), $id);
            }

            return response()->restored(__('core::messages.resource.restored', ['model' => 'Notification Controller']));

        } catch (ModelNotFoundException $exception) {

            return response()->notfound($exception->getMessage());

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *Notification* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     */
    public function export(IndexNotificationRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $notificationPaginate = Bell::notification()->export($inputs);

            return response()->exported(__('core::messages.resource.exported', ['model' => 'Notification Controller']));

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }

    /**
     * @lrd:start
     * Create a exportable list of the *Notification* resource as document.
     * After export job is done system will fire  export completed event
     *
     * @lrd:end
     *
     * @return NotificationCollection|JsonResponse
     */
    public function import(ImportNotificationRequest $request): JsonResponse
    {
        try {
            $inputs = $request->validated();

            $notificationPaginate = Bell::notification()->list($inputs);

            return new NotificationCollection($notificationPaginate);

        } catch (Exception $exception) {

            return response()->failed($exception);
        }
    }
}
