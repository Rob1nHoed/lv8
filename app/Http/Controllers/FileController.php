<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\File;

use Illuminate\Support\Str;

class FileController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|max:32768'
        ]);
        
        $file_key = Str::random(250);
        
        while (File::where('file_key', $file_key)->exists()) {
            $file_key = Str::random(250);
        }

        if($request->expiration != null)
        {
            $expiration_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' + '. $request->expiration .' day'));
        }
        else
        {
            $expiration_date = null;
        }

        if($request->max_downloads == null || $request->max_downloads == 0)
        {
            $max_downloads = null;
        }
        else
        {
            $max_downloads = $request->max_downloads;
        }

        $file = new File;
        $file->file_key = $file_key;
        $file->user_id = Auth::id();
        $file->file_name = $request->file('file')->getClientOriginalName();
        $file->description = $request->description;
        $file->downloads = 0;
        $file->max_downloads = $max_downloads;
        $file->expires_at = $expiration_date;
        $file->save();
    
        $fileName = $request->file('file')->getClientOriginalName();

        Storage::put('files/' . $file_key , request('file')->storeAs('files/' . $file_key, $fileName));

        $expiration = null;

        if($request['expiration'] != null)
        {
            $expiration .= 'This file will expire in ' . $request['expiration'] . ' days.';
        }
        else
        {
            $expiration .= 'This file will not expire.';
        }
        
        //send mail
        $details = [
            'title' => 'File Upload',
            'user_name' => Auth::user()->name,
            'link' => 'localhost:8000/file/download/' . $file_key,
            'link_text' => 'Download File',
            'file_name' => $fileName,
            'description' => $request->description,
            'expiration' => $expiration,
        ];
    
        
        //send FileShared mail
        Mail::to($request->mail)->send(new \App\Mail\FileShared($details));
        
        return view('home');
    }
    
    public function download($id)
    {
        $file = File::find($id);
        $file_key = $file->file_key;
        $file_name = $file->file_name;

        $file->downloads = $file->downloads + 1;
        $file->save();
    
        if($file->max_downloads != null)
        {
            if($file->downloads >= $file->max_downloads)
            {
                $file = File::where('file_key', $file_key)->first();
                $file->expired = true;
                $file->save();
            }
        }
        return Storage::download('files/' . $file_key . '/' . $file_name);
    }   
    
    public function deleteFile($file_key)
    {
        Storage::deleteDirectory('files/' . $file_key);
        return redirect()->route('home');
    }

    public function deleteFromDatabase($file_key)
    {
        $file = File::where('file_key', $file_key)->first();
        $file->delete();
        return redirect()->route('home');
    }
    
    public function show($key)
    {
        $file = File::where('file_key', $key)->firstOrFail();

        if($file->expired == true)
        {
            return view('file_expired'); 
        }
        else
        {
            return view('file.show', ['file' => $file]);
        }
    }
    
    public function toUpload()
    {
        if(!Auth::check()){
            return redirect()->route('login');
        }
        
        return view('file.upload');
    }
    
    public function toDownload($key)
    {     
        $file = File::where('file_key', $key)->firstOrFail();
        
        if($file->expired == true)
        {
            return view('file.expired' , ['user' => Auth::user($file->user_id)->email]);
        }
        return view('file.download' , ['file' => $file]);
    }
}
