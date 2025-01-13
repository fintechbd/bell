<?php

namespace Fintech\Bell\Facades;

use Fintech\Bell\Services\TriggerService;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Contracts\Pagination\Paginator|\Illuminate\Support\Collection|TriggerService trigger(array $filters = null)*                                                                                                                                                    @method static \Fintech\Bell\Services\NotificationService notification()
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
