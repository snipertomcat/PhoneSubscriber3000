@extends('layouts.certificade_pdf')
@section('content')
    <div class="image" style="width: 1024px; margin:0 auto;display:block;">
        <img width="1024" src="{{$certificate->bg_url}}" style="position: absolute;z-index: -1" />
        <div class="user" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:250px;font-weight: bold;color:#39AD63">
            <h1 style="font-family: 'Roboto', sans-serif;">{{ $user->full_name }}</h1>
        </div>
        <div style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:335px;font-size: 20px;color:#0051AA">
            <span style="font-family: 'Roboto', sans-serif;">ha completado y aprobado de forma exitosa el curso</span>
        </div>
        <div class="experience" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:370px;font-size: 20px;font-weight: bold;color:#0051AA">
            <span style="font-family: 'Roboto', sans-serif;">{{ $experience->title }}</span>
        </div>
        <div class="score" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:395px;font-size: 18px;color:#0051AA">
            <span style="font-family: 'Roboto', sans-serif;">con una nota aprobatoria de <strong>{{ (int) $enrollment->score }}</strong></span>
        </div>
        <div class="date" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:425px;font-size: 20px;font-weight: bold;;color:#0051AA">
            <span style="font-family: 'Roboto', sans-serif;">{{ $finished_at }}</span>
        </div>
        <div class="date" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:520px;font-size: 20px;font-weight: bold;;color:#0051AA">
            <span style="font-family: 'Roboto', sans-serif;">Certifica</span>
        </div>
        <div class="cert_by" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:525px;font-weight: bold;color:#39AD63">
            <h2 style="font-family: 'Roboto', sans-serif;">Ing. Juan Manuel Fernandez Valencia</h2>
        </div> <div class="date" style="width: 1024px; margin:0 auto;display:block;text-align:center;position: absolute;top:575px;font-size: 20px;font-weight: bold;;color:#0051AA">
            <span style="font-family: 'Roboto', sans-serif;">Director de manufactura</span>
        </div>
    </div>
@endsection
