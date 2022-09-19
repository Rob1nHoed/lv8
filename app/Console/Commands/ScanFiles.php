<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;
use App\Models\File;
use App\Models\User;

class ScanFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'files:scan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scan files for expiration';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Getting all files that are not trash
        $files = File::where('deleted_at', null)->get();

        foreach ($files as $file) {
            if ($file->expires_at != null) {
                if (strtotime($file->expires_at) < strtotime(date('Y-m-d H:i:s'))) {
                    $file->delete();
                    Storage::deleteDirectory('files/' . $file->file_key);
                }
                else if (strtotime($file->expires_at) < strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + 7 day')))) {
                    //Send reminder to user by email
                    $details = [
                        'title' => 'File Expiration Reminder',
                        'body' => 'Your file ' . $file->file_name . ' will expire in 7 days.',
                    ];
                    Mail::to(User::find($file->user_id)->email)->send(new \App\Mail\FileExpirationReminder($details));
                }
            }
        }
        
        return 0;
    }
}
