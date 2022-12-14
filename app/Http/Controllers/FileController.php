<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\File;
use App\Models\User;

class FileController extends Controller
{

    public function toUpload()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        
        return view('file.upload');
    }

    public function store(Request $request)
    {
        // Validating the request
        $request->validate([
            'file' => 'required|max:32768'
        ]);
        
        // Giving the file a unique key
        $file_key = Str::random(250);
        
        while (File::where('file_key', $file_key)->exists()) {
            $file_key = Str::random(250);
        }

        // Check if expiration date is set
        if($request->expiration != null){
            $expiration_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + '. $request->expiration .' day'));
        }
        else{
            $expiration_date = null;
        }

        // Check if max downloads is set
        if($request->max_downloads == null || $request->max_downloads == 0){
            $max_downloads = null;
        }
        else{
            $max_downloads = $request->max_downloads;
        }

        // Storing the file information in the database
        $file = new File;
        $file->file_key = $file_key;
        $file->user_id = Auth::id();
        $file->file_name = $request->file('file')->getClientOriginalName();
        $file->description = $request->description;
        $file->max_downloads = $max_downloads;
        $file->expires_at = $expiration_date;
        $file->save();
    
        $fileName = $request->file('file')->getClientOriginalName();

        // Storing the file in the storage
        Storage::put('files/' . $file_key , request('file')->storeAs('files/' . $file_key, $fileName));

        // Sending the email to the user
        if($request['expiration'] != null){
            $expiration = 'This file will expire in ' . $request['expiration'] . ' days.';
        }
        else{
            $expiration = 'This file will not expire.';
        }

        $details = [
            'title' => 'File Upload',
            'user_name' => Auth::user()->name,
            'link' => 'localhost:8000/file/download/' . $file_key,
            'link_text' => 'Download File',
            'file_name' => $fileName,
            'description' => $request->description,
            'expiration' => $expiration,
        ];
    
        // Get every email from mail without spaces and comma
        $emails = preg_split('/[\s,]+/', $request->mail, -1, PREG_SPLIT_NO_EMPTY);

        // Sending the email(s)
        foreach($emails as $email){
            Mail::to($email)->send(new \App\Mail\FileShared($details));

            // If the recievers email is registered, add relation to the file
            if(User::where('email', $email)->exists()){
                //$file->send()->attach(User::where('email', $request->mail)->first()->id);
                User::where('email', $email)->first()->recieved()->attach($file->id);
            }
        }
        

        return view('home');
    }
    
    public function show($key)
    {
        $file = File::where('file_key', $key)->firstOrFail();

        if($file->expired == true){
            return view('file_expired'); 
        }
        return view('file.show', ['file' => $file]);

    }
   
    public function toDownload($key)
    {     
        $file = File::where('file_key', $key)->firstOrFail();

        if($file->expired == true || $file->downloads >= $file->max_downloads){
            return view('file.expired'); 
        }

        return view('file.download' , ['file' => $file]);
    }

    public function download($id)
    {
        // obtain file info
        $file = File::find($id);
        $key = $file->file_key;
        $name = $file->file_name;

        // increment downloads
        $file->downloads = $file->downloads + 1;
        $file->save();

        if($file->max_downloads != null && $file->downloads >= $file->max_downloads){
            $this->softDelete($key);
        }

        // Check if relation between user and file is already created, if not create it
        if(!\DB::table('file_user_downloads')->where('file_id', $id)->where('user_id', Auth::id())->exists()){
            auth()->user()->downloaded()->attach($file->id);
        }

        return Storage::download('files/' . $key . '/' . $name);
    }   

    public function toEdit($key)
    {
        //if file user id is not the same as the logged in user id, redirect to home
        $file = File::where('file_key', $key)->firstOrFail();
        if($file->user_id != Auth::id()){
            return redirect()->route('home');
        }

        return view('file.edit', ['file' => $file]);
    }

    public function update($key, Request $request)
    {
        $file = File::where('file_key', $key)->firstOrFail();

        if($request->expiration != null){
            $expiration_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + '. $request->expiration .' day'));
        }
        else{
            $expiration_date = null;
        }

        if($request->max_downloads == null || $request->max_downloads == 0){
            $max_downloads = null;
        }
        else{
            $max_downloads = $request->max_downloads;
        }

        $file->description = $request->description;
        $file->max_downloads = $max_downloads;

        if($request->expiration != "same"){
            $file->expires_at = $expiration_date;
        }

        $file->save();

        return redirect()->route('home');
    }
    
    public function toDelete($key)
    {
        //if file user id is not the same as the logged in user id, redirect to home
        $file = File::where('file_key', $key)->firstOrFail();
        if($file->user_id != Auth::id()){
            return redirect()->route('home');
        }

        return view('file.delete', ['file' => $file]);
    }

    public function softDelete($key)
    {
        $file = File::where('file_key', $key)->first();
        $file->delete();
    }

    public function fullDelete($key)
    {
        $file = File::where('file_key', $key)->first();
        $file->delete();

        //find user by id and detach
        $user = User::find(Auth::id());
        $user->recieved()->detach($file->id);
        $user->downloaded()->detach($file->id);


        return Storage::deleteDirectory('files/' . $key);
    }
    
    public function delete($key)
    {
        // Deleting directory without a return part doesnt seem to work so im using this
        $this->fullDelete($key);
        return redirect()->route('home');
    }
 
    public function removeFromSended($key)
    {
        Auth::user()->recieved()->detach(File::where('file_key', $key)->first()->id);
        return redirect()->route('home');
    }
}
