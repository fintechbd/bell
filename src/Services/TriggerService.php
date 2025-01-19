<?php

namespace Fintech\Bell\Services;

use Fintech\Core\Attributes\ListenByTrigger;
use Fintech\Core\Listeners\TriggerNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use ReflectionClass;

/**
 * Class TriggerService
 */
class TriggerService
{
    private Collection $triggers;

    /**
     * BankAccountService constructor.
     *
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $this->loadTriggers();
    }

    public function find($code)
    {
        return $this->triggers->firstWhere('id', $code);
    }

    public function list(array $filters = [])
    {
        return $this->triggers;
    }

    /**
     * @throws \ReflectionException
     */
    public function sync()
    {
        Cache::forget('system-triggers');

        $this->loadTriggers();
    }

    /**
     * @throws \ReflectionException
     */
    private function loadTriggers(): void
    {
        $eventDispatcher = App::make('events');

        $this->triggers = Cache::remember('system-triggers', HOUR, function () use ($eventDispatcher) {
            return collect($eventDispatcher->getRawListeners())
                ->filter(function ($listeners) {
                    return in_array(TriggerNotification::class, $listeners);
                })
                ->keys()
                ->map(function ($event) {
                    $reflector = new ReflectionClass($event);

                    return (empty($reflector->getAttributes(ListenByTrigger::class)))
                        ? $this->handleSystemEvent($event)
                        : $this->handlePackageEvent($event, $reflector);
                });
        });
    }

    /**
     * @throws \ReflectionException
     */
    private function handlePackageEvent(string $event, ReflectionClass $reflector): array
    {
        $triggerInfo = $reflector->getAttributes(ListenByTrigger::class)[0]->newInstance();
        return [
            'id' => Str::uuid()->toString(),
            'name' => $triggerInfo->name(),
            'code' => $event,
            'description' => $triggerInfo->description(),
            'enabled' => $triggerInfo->enabled(),
            'variables' => array_map(fn($variable) => ['name' => $variable->name(), 'description' => $variable->description()], $triggerInfo->variables())
        ];
    }

    private function handleSystemEvent($event): array
    {
        return match ($event) {
            Lockout::class => [
                'id' => Str::uuid()->toString(),
                'name' => 'Login Attempt Lockout',
                'code' => $event,
                'description' => 'Trigger fires when user tries to logging to system over multiple times in short duration',
                'enabled' => true,
                'variables' => [
                    ['name' => '__' . config('fintech.auth.auth_field', 'login_id') . '__', 'description' => 'Email, phone number used to log in'],
                    ['name' => '__ip__', 'description' => 'IP address used to log in'],
                    ['name' => '__platform__', 'description' => 'User agent platform used to attempt'],
                ],
            ],
            Attempting::class => [
                'id' => Str::uuid()->toString(),
                'name' => 'Login Attempting',
                'code' => $event,
                'description' => 'Trigger fires when user tries to logging to system',
                'enabled' => true,
                'variables' => [
                    ['name' => '__' . config('fintech.auth.auth_field', 'login_id') . '__', 'description' => 'Email, phone number used to log in'],
                    ['name' => '__ip__', 'description' => 'IP address used to log in'],
                    ['name' => '__platform__', 'description' => 'User agent platform used to attempt'],
                ],
            ],
            Failed::class => [
                'id' => Str::uuid()->toString(),
                'name' => 'Login Attempt Failed',
                'code' => $event,
                'description' => 'Trigger fires when user failed to logging to system',
                'enabled' => true,
                'variables' => [
                    ['name' => '__account_name__', 'description' => 'Name of the user tried login.'],
                    ['name' => '__account_mobile__', 'description' => 'Mobile number associate with requested user.'],
                    ['name' => '__account_email__', 'description' => 'Email address associate with requested user.'],
                    ['name' => '__password_attempt_count__', 'description' => 'Number of times wrong password attempted.'],
                    ['name' => '__account_status__', 'description' => 'User account before frozen/suspended status.'],
                    ['name' => '__password_attempt_limit__', 'description' => 'The maximum number of times a user may try to customize my system'],
                    ['name' => '__ip__', 'description' => 'IP address used to log in'],
                    ['name' => '__platform__', 'description' => 'User agent platform used to attempt'],
                ],
            ],
            default => [
                'id' => Str::uuid()->toString(),
                'name' => $event,
                'code' => $event,
                'description' => 'System Event',
                'enabled' => true,
                'variables' => [],
            ]
        };
    }
}
