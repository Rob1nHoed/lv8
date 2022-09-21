<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\File;
use App\Models\User;

class ProcessFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileData;
    public $emails;
    public $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($fileData, $emails, $details)
    {
        $this->fileData = $fileData;
        $this->emails = $emails;
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        $fileData = $this->fileData;

        //add file in database
        $fileData = File::create($fileData);

        //send email to receivers
        foreach($this->emails as $email){
            Mail::to($email)->send(new \App\Mail\FileShared($this->details));

            if (User::where('email', $email)->exists()){
                User::where('email', $email)->first()->recieved()->attach($fileData->id);
            }
        }
    }
}
