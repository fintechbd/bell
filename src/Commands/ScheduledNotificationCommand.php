<?php

namespace Fintech\Bell\Commands;

use Fintech\Bell\Events\ScheduledTrigger;
use Illuminate\Console\Command;

class ScheduledNotificationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bell:scheduled-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scheduled notification trigger command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //        \Fintech\Bell\Facades\Bell::template()->list([
        //            'trigger_code' => \Fintech\Bell\Events\ScheduledTrigger::class,
        //            'triggered' => false,
        //            'scheduled' => true,
        //        ])->each(function ($trigger) {
        //            event(new ScheduledTrigger($trigger));
        //        });
    }
}
