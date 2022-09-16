@extends('layouts.app')
@php
    $expireText = 'never';
    if($file->expires_at != null){
        $expireText = $file->expires_at;
    }
@endphp

@section('content')
<body>
    <div class="container pt-5">
        <div class="row pt-5">
            <div class="pt-5"></div>
            <div class="col-3 pt-3 card ct" style="width:32.2%;">
                <div class="">
                    <h1>Send a file</h1>
                </div>
                <div class="pt-3">
                    <form action="{{ route('file.update', $file->file_key) }}" method="post">
                        @csrf

                        <div class="pt-3"></div>
                        <div class="">
                            <div class="">
                                <label for='expiration'><h4>Expiration date</h4></label>
                                <div>
                                    <select name="expiration" id="expiration" class="form-control">
                                        <option value="same">Current expiration date: {{ $expireText }}</option>
                                        <option value="">No expiration</option>
                                        <option value="1">One day</option>
                                        <option value="3">Three days</option>
                                        <option value="7">One week</option>
                                        <option value="14">Two weeks</option>
                                        <option value="30">One month</option>
                                        <option value="182">Half a year</option>
                                        <option value="365">One year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="p-2"></div>
                            <div class="">
                                <label for='max_downloads'><h4>Download limit</h4><p>Current downloads: {{ $file->downloads }}</p></label>
                                <div>
                                    <input type="number" name="max_downloads" id="max_downloads" placeholder="Leave blank if no limit" class="form-control" value="{{ $file->max_downloads }}" min="{{ $file->downloads }}" max="999">
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <label for='description'><h3>Added message</h3></label>
                            <div class="pt-2">
                                <textarea type="text" name="description" id="description" style="height:200px; width:100%" class="form-control" value="{{ $file->description }}"> </textarea>
                            </div>
                        </div>

                        <div class="pt-5">
                            <button type="submit" class="btn btn-primary"><strong>Update</strong></button>
                        </div>
                    </form>
                </div>
                <div class="pb-3"></div>
            </div>
        </div>

    </div>
</body>
@endsection