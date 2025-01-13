<?php

namespace Fintech\Bell\Facades;

use Fintech\Bell\Services\NotificationTemplateService;
use Fintech\Bell\Services\TriggerActionService;
use Fintech\Bell\Services\TriggerRecipientService;
use Fintech\Bell\Services\TriggerService;
use Fintech\Bell\Services\TriggerVariableService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|TriggerService trigger(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|TriggerRecipientService triggerRecipient(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|TriggerVariableService triggerVariable(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|NotificationTemplateService notificationTemplate(array $filters = null)
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|TriggerActionService triggerAction(array $filters = null)
 *                                                                                                                                                    @method static \Fintech\Bell\Services\NotificationService notification()
 * @method static \Fintech\Bell\Services\NotificationService notification()
 * // Crud Service Method Point Do not Remove //
 *
 * @see \Fintech\Bell\Bell
 */
class Bell extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Fintech\Bell\Bell::class;
    }
}
