<?php

namespace App\Console\Commands;

use App\Http\Controllers\Web\NotificationController;
use Illuminate\Console\Command;

class SendTaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminders for upcoming and overdue tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to send task reminders...');
        
        // Call the static method to create notifications
        NotificationController::createTaskDeadlineReminders();
        
        $this->info('Task reminders sent successfully.');
        
        return Command::SUCCESS;
    }
}
