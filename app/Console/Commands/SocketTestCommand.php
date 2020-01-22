<?php

namespace App\Console\Commands;

use App\Events\NotificationEvent;
use Illuminate\Console\Command;

class SocketTestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocket:push';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Just a websocket test message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $title = 'Title';
        $message = 'Test message.';
        $channel = 'notifications-channel';
        $broadcast = 'notification';

        $message_info = "Sending {$title} {$message} event to {$channel}:{$broadcast}";

        event(new NotificationEvent($channel, $broadcast, $message_info, $title, 'success'));
    }
}
