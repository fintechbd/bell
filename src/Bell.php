<?php

namespace Fintech\Bell;

use Fintech\Bell\Exceptions\BellException;
use Fintech\Bell\Services\NotificationTemplateService;
use Fintech\Bell\Services\TriggerActionService;
use Fintech\Bell\Services\TriggerRecipientService;
use Fintech\Bell\Services\TriggerService;
use Fintech\Bell\Services\TriggerVariableService;

class Bell
{
    /**
     * @throws BellException
     */
    public function push()
    {
        $active = config('fintech.bell.push.default');

        if ($active == null) {
            throw new BellException('No Push Notification driver configured as default');
        }

        $driver = config("fintech.bell.push.{$active}.driver");

        return \singleton($driver);
    }

    public function trigger($filters = null)
    {
        return \singleton(TriggerService::class, $filters);
    }

    public function triggerRecipient($filters = null)
    {
        return \singleton(TriggerRecipientService::class, $filters);
    }

    public function triggerVariable($filters = null)
    {
        return \singleton(TriggerVariableService::class, $filters);
    }

    public function notificationTemplate($filters = null)
    {
        return \singleton(NotificationTemplateService::class, $filters);
    }

    public function triggerAction($filters = null)
    {
        return \singleton(TriggerActionService::class, $filters);
    }

    //** Crud Service Method Point Do not Remove **//

}
