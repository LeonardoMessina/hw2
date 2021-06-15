@extends('layouts.common')

@section('title', 'Area Personale')

@section('css')
@parent
<link href="css/personal_area.css" rel="stylesheet">
@endsection
@section('js')
@parent
@endsection

@section('header_h1', 'Area Personale')

@section('content')
<div id='l'>
	<a id='lt' href="{{ route('gestione_opere') }}">
		<h1>Gestione opere</h1>
	</a>
	<a id='lb' href="{{ route('backup_opere') }}">
		<h1>Backup delle opere</h1>
	</a>
</div>
<a id='r' href="{{ route('gestione_dati_museo') }}">
	<h1>Gestione dati del museo</h1>
</a>
@endsection