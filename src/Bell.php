<?php

namespace Fintech\Bell;

use Fintech\Bell\Exceptions\BellException;

class Bell
{
    /**
     * @throws BellException
     */
    public function sms()
    {
        $active = config('fintech.bell.sms.default');

        if ($active == null) {
            throw new BellException('No SMS driver configured as default');
        }

        $driver = config("fintech.bell.sms.{$active}.driver");

        return app($driver);
    }

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

        return app($driver);
    }

    /**
     * @return \Fintech\Bell\Services\TriggerService
     */
    public function trigger()
    {
        return app(\Fintech\Bell\Services\TriggerService::class);
    }

    /**
     * @return \Fintech\Bell\Services\TriggerRecipientService
     */
    public function triggerRecipient()
    {
        return app(\Fintech\Bell\Services\TriggerRecipientService::class);
    }

    /**
     * @return \Fintech\Bell\Services\TriggerVariableService
     */
    public function triggerVariable()
    {
        return app(\Fintech\Bell\Services\TriggerVariableService::class);
    }

    /**
     * @return \Fintech\Bell\Services\NotificationTemplateService
     */
    public function notificationTemplate()
    {
        return app(\Fintech\Bell\Services\NotificationTemplateService::class);
    }

    /**
     * @return \Fintech\Bell\Services\TriggerActionService
     */
    public function triggerAction()
    {
        return app(\Fintech\Bell\Services\TriggerActionService::class);
    }

    //** Crud Service Method Point Do not Remove **//

}
