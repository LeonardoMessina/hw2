@extends('layouts.common')

@section('title', 'Chi siamo')

@section('css')
@parent
<link href="css/chi_siamo.css" rel="stylesheet">
@endsection
@section('js')
@parent
@endsection

@section('header_h1', 'Chi siamo')

@section('content')
<h1>La nostra storia</h1>
<p>La nostra azienda, che in realtà non esiste, è stata fondata nel 1997 con lo scopo di fornire una piattaforma unificata che rendesse
	agevole per il pubblico reperire informazioni sui musei da visitare, mettendo a disposizione dei musei una strumentazione semplice ed
	efficace per inserire ed aggiornare i dati d'interesse. 
</p>
<h1>Servizi</h1>
<p>
	Il nostro sito permette ai visitatori di visualizzare molte informazioni utili riguardanti i musei, tra cui il nome, la città, il tipo, 
	le condizioni meteo attuali della località ed una mappa della zona con indicata la posizione del museo ed anche eventuali problemi del
	traffico.<br/>
	I visitatori possono anche ottenere informazioni sulle opere conservate nei musei.<br/>
	Gli utenti registrati hanno invece la possiblità di amministrare tutto ciò che riguarda il proprio museo e che è visibile ai visitatori
	del sito.
</p>
<div id='moreInfo'>
	<h1>Vorresti altre informazioni?</h1>
	<div id='contactPanel'>
		<a href="{{ route('contattaci') }}" class='button'>Contattaci!</a>
	</div>
</div>
@endsection