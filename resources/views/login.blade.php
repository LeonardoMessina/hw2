@extends('layouts.common')

@section('title', 'Login')

@section('css')
@parent
<link href="css/login.css" rel="stylesheet">
@endsection
@section('js')
@parent
@endsection

@section('header_h1', 'Accesso')

@section('content')
<div id="input">
    <form method='post'>
        <input type="hidden" name="_token" value="{{$csrf_token}}"/>
        <h1>Accedi</h1>
        <div class="usernameEmail">
            <div><label for='usernameEmail'>Nome utente o email</label></div>
            <div><input type='text' name='usernameEmail' value="{{ old('usernameEmail') }}"/></div>
        </div>
        <div class="password">
            <div><label for='password'>Password</label></div>
            <div><input type='password' name='password' value="{{ old('password') }}"/></div>
        </div>
        <div class='buttons'>
            <input type='submit' value="Accedi"/>
        </div>
        <div class="errors {{count($error)>0 ? '' : 'hidden'}}">
            <h1>Errori:</h1>
            <p>
                @foreach($error as $error)
                    {{$error}}
                @endforeach
            </p>
        </div>
    </form>
    <div class="registerTip">Non hai ancora un account? <a href="{{ route('signup') }}">Iscriviti</a></div>
</div>
@endsection