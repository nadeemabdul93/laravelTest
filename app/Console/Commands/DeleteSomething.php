<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\File;
use App\Models\User;
use App\Models\ShareLink;
use App\Notifications\FileDeletedNotification;
class DeleteSomething extends Command
{
    protected $signature = 'delete:something';

    protected $description = 'Delete something specific';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Your deletion logic here
        $this->info('Deleted something successfully.');
        \Log::info("Cron is working fine!");
        $files = File::all();
        // $files = File::where('created_at', '<=', now()->subDays(30)->toDateTimeString())->get();

            foreach ($files as $file) {
                $deleteShareLink=ShareLink::where('file_id',$file->id)->delete();
                
                // Send notification to uploader
                $uploader = User::find($file->user_id);
                $uploader->notify(new FileDeletedNotification($file));
                $file->delete();
            }
    }
}
