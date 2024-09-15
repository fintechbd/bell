<?php

namespace Fintech\Bell\Commands;

use Fintech\Core\Traits\HasCoreSettingTrait;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
    use HasCoreSettingTrait;

    public $signature = 'bell:install';

    public $description = 'Configure the system for the `fintech/bell` module';

    private string $module = 'Bell';

    public function handle(): int
    {
        $this->infoMessage("Module Installation", 'RUNNING');

        $this->task("Module Installation", function () {

        }, 'COMPLETED');

        return self::SUCCESS;
    }
}
