@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (Auth::check())
                        <div class="pt-3" style="position: relative; text-align:center;">
                            <div class="">
                                <h1>Send a file</h1>
                            </div>
                            <div class="p-3 pt-5">
                                <a href="/file/upload" class="btn btn-primary"><strong>Send file</strong></a>
                            </div>
                        </div>
                    @else
                        <div class="pt-3" style="position: relative; text-align:center;">
                            <div class="">
                                <h1>Login required</h1>
                            </div>
                            <div class="p-3 pt-5">
                                <a href="{{ route('login') }}" class="btn btn-primary"><strong>Login</strong></a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

@if (Auth::check())

<!-- Files recieved -->
    @php
        $recieved_files = Auth::user()->recieved;
    @endphp

    @if($recieved_files->count() > 0)
        <div class="row pt-2">
            <div class="row justify-content-center pt-5">
                <div class="col-md-16">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="pt-2" style="text-align: center"><strong>Files sended to you</strong></h1>
                        </div>
                    </div>
                </div>
            </div>

        @foreach ($recieved_files as $file)
            <div class="col-3 pb-3 pt-2">
                <div class="card" style="max-width:282px">
                    <div class="card-header pt-3">
                        <h4 style="overflow:hidden; text-overflow: ellipsis; white-space: nowrap;"><strong>{{ App\Models\User::find($file->user_id)->email }}</strong></h4>                    
                        <h4>sended you a file.</h4>
                    </div>
                    <div class="pt-2">  
                            <div class="p-2">
                                <h4 style="overflow:hidden; text-overflow: ellipsis; white-space: nowrap; max-width:100%;">{{ $file->file_name }}</h4>
                            </div>
                            <div class="d-flex justify-content-center">
                                <div class="p-1">
                                    <a href="{{ route('file.toDownload', $file->file_key) }}" class="btn btn-primary"><strong>Download</strong></a>    
                                </div>       
                                <div class="p-1">
                                    <a href="{{ route('file.removeFrom.sended', $file->file_key) }}" class="btn btn-primary"><strong>Remove</strong></a>    
                                </div>    
                            </div>                           
                    </div>
                </div>
            </div>   
        @endforeach
        </div>
    @endif

<!-- Files uploaded -->
    @php
        $uploaded_files = Auth::user()->files;    
    @endphp
    <div class="row justify-content-center pt-5">
        <div class="col-md-16">
            <div class="card">
                <div class="card-header">
                    <h1 class="pt-2" style="text-align: center"><strong>Uploaded files: {{ $uploaded_files->count() }} files</strong></h1>
                </div>
            </div>
        </div>
    </div>

        <div class="row pt-2">

            @foreach ($uploaded_files as $file)  
                @php
                    if($file->max_downloads == null){
                        $downloads = $file->downloads;   
                    }
                    else{
                        $downloads = $file->downloads . '/' . $file->max_downloads;   
                    }
                @endphp

                <div class="col-3 pb-3">
                    <div class="card" style="max-width:305px">
                        <div class="card-header pt-3">
                            <h2 style="overflow:hidden; text-overflow: ellipsis; white-space: nowrap;"><strong>{{ $file->file_name }}</strong></h2>                    
                        </div>
                        <div class="card-body">
                            <h2><strong>Downloads:</strong></h2>
                            <h2>{{ $downloads }}</h2>
                        </div>
                        <div class="">
                            <div class="p-2">
                                <p><strong>
                                    expires at: <br>
                                    <?php
                                        if($file->expires_at == null){
                                            echo 'never';
                                        }
                                        else{
                                            if($file->expired == 1){
                                                echo 'expired';
                                            }
                                            else{
                                                echo $file->expires_at;
                                            }
                                        }
                                    ?>
                                </strong></p>
                            </div>
                            <div class="d-flex justify-content-center">   
                                <div class="p-1">
                                    <a href="{{ route('file.toDownload', $file->file_key) }}" class="btn btn-primary"><strong>Download</strong></a>    
                                </div>     
                                <div class="p-1">
                                    <a href="{{ route('file.toEdit', $file->file_key) }}" class="btn btn-primary"><strong>Edit</strong></a>    
                                </div>     
                                <div class="p-1">
                                    <a href="{{ route('file.toDelete', $file->file_key) }}" class="btn btn-primary"><strong>Delete</strong></a>    
                                </div>     
                            </div>                         
                        </div>
                    </div>
                </div>   
            @endforeach
        </div>
        
<!-- Files downloaded -->
        @php
            $downloaded_files = Auth::user()->downloaded;
        @endphp

        <div class="row pt-2">
            <div class="row justify-content-center pt-5">
                <div class="col-md-16">
                    <div class="card">
                        <div class="card-header">
                            <h1 class="pt-2" style="text-align: center"><strong>Downloaded files: {{ $downloaded_files->count() }} files</strong></h1>
                        </div>
                    </div>
                </div>
            </div>
        
        @foreach ($downloaded_files as $file)

                <div class="col-3 pb-3 pt-2">
                    <div class="card" style="max-width:282px">
                        <div class="card-header pt-3">
                            <h2 style="overflow:hidden; text-overflow: ellipsis; white-space: nowrap;"><strong></strong>{{ $file->file_name }}</h2>                    
                        </div>
                        <div class="pt-2 ">
                            <div class="justify-content-center">   
                                <div class="p-2">
                                    <h4><strong>Downloaded from:</strong> {{ Auth::user($file->user_id)->email }}</h4>
                                </div> 
                                <div class="d-flex justify-content-center">  
                                    <div class="p-1">
                                        <a href="{{ route('file.toDownload', $file->file_key) }}" class="btn btn-primary"><strong>Download</strong></a>    
                                    </div>       
                                </div>     
                            </div>                         
                        </div>
                    </div>
                </div>   
            @endforeach
        </div>
    
@endif
</div>

@endsection
