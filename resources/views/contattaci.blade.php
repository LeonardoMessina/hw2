@extends('layouts.common')

@section('title', 'Contattaci')

@section('css')
@parent
<link href="css/contattaci.css" rel="stylesheet">
@endsection
@section('js')
@parent
@endsection

@section('header_h1', 'Contattaci')

@section('content')
<div>
    <div>
        <h1>Recapito telefonico:</h1>
        <p>+391234567890</p>
        <h1 id='contactEmail'>E-mail:</h2>
        <p>museiitaliani@gmail.it</p>
    </div>
</div>
@endsection