<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\File;

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
        $files = File::all();

        foreach ($files as $file) {
            if ($file->expires_at != null) {
                if (strtotime($file->expires_at) < strtotime(date('Y-m-d H:i:s'))) {
                    $file->delete();
                    Storage::deleteDirectory('files/' . $file->file_key);
                }
                else if (strtotime($file->expires_at) < strtotime(date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + 7 day')))) {
                    //Send reminder to user by email
                }
            }
        }

        return 0;
    }
}
