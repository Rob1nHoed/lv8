@extends('layouts.app')
@php
@endphp

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
                    
                </div>
                <div class="pt-3">
                    <div>
                           <h2>This file has expired</h2>
                           <h4>Sended by: {{ $user }}</h4>
                    </div>
                </div>
                <div class="pt-5 pb-3">
                    <a href="{{ route('home') }}" class="btn btn-primary p-3"><strong>Home</strong></a>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection