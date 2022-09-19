@extends('layouts.app')
@section('content')
<body>
    <div class="container pt-5">
        <div class="row pt-5">
            <div class="pt-5"></div>
            <div class="pt-5"></div>
            <div class="col-5 pt-5 card ct" style="width:32.2%;">
                <div class="">
                    <h1>Download file</h1>
                </div>
                <div class="pt-3">
                    <h3>Sended by: <strong>{{ Auth::user($file->user_id)->email }}</strong></h3>
                </div>
                <div class="pt-3">
                    <div>
                        <label for='description'><h3>Added message</h3></label>
                        <div class="pt-2">
                            <textarea type="text" name="description" id="description" style="height:200px; width:100%" class="form-control" readonly>{{ $file->description }}</textarea>
                        </div>
                    </div>
                    <div class="pt-4 d-flex">
                        <div class="pt-1 w-75">
                            <h3 style="overflow:hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $file->file_name }}">{{ $file->file_name }}</h3>
                        </div>
                        <div class="">
                            <copy-to-clipboard>
                        </div>
                        <div class="">
                            <a href="{{ route('file.download', $file) }}" class="btn btn-primary"><strong>Download</strong></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection