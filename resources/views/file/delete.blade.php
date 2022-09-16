@extends('layouts.app')
@section('content')
<body>
    <div class="container pt-5">
        <div class="row pt-5">
            <div class="pt-5"></div>
            <div class="pt-5"></div>
            <div class="col-5 pt-5 card ct" style="width:32.2%;">
                <div class="">
                    <h1><strong>Delete file</strong></h1>
                </div>
                <div class="pt-3">
                    <h2>Notice:</h2>
                    <h4>Deleting a file is can not be recovered</h4>
                </div>
                <div class="pt-3 d-flex">
                    <div class="pb-3 pt-3">
                        <a href="{{ route('home') }}" class="btn btn-primary"><strong>Cancel</strong></a>
                    </div>
                    <div class="p-3">
                        <a href="{{ route('file.delete', $file->file_key) }}" class="btn btn-warning"><strong>Delete</strong></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection