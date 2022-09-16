@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <!-- check if logged in -->
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
    @php
        $uploaded_files = Auth::user()->files;    
    @endphp
    <div class="row justify-content-center pt-5">
        <div class="col-md-16">
            <div class="card">
                <div class="card-header">
                    <h1 class="pt-2" style="text-align: center"><strong>Uploads available: {{ Auth::user()->files->count() }} files</strong></h1>
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
                    <div class="card" style="max-width:300px">
                        <div class="card-header pt-3">
                            <h2 style="overflow:hidden; text-overflow: ellipsis; white-space: nowrap;"><strong>aawdwaaa.txt</strong></h2>                    
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
    @endif
</div>

@endsection
