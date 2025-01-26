<?php

namespace Fintech\Bell;

use Fintech\Bell\Exceptions\BellException;
use Fintech\Bell\Services\TriggerService;

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

    /**
     * @return \Fintech\Bell\Services\TemplateService
     */
    public function template()
    {
        return app(\Fintech\Bell\Services\TemplateService::class);
    }

    /**
     * @return \Fintech\Bell\Services\NotificationService
     */
    public function notification()
    {
        return app(\Fintech\Bell\Services\NotificationService::class);
    }

    // ** Crud Service Method Point Do not Remove **//

}
