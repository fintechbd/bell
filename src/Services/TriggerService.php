<?php

namespace Fintech\Bell\Services;

use Illuminate\Support\Collection;

/**
 * Class TriggerService
 */
class TriggerService
{
    private Collection $triggers;

    /**
     * BankAccountService constructor.
     */
    public function __construct()
    {
        $this->triggers = new Collection;
    }

    public function find($code) {}

    public function list(array $filters = []) {}

    public function sync(array $options = []) {}
}
