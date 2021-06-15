@extends('layouts.common')

@section('title', 'Musei')

@section('css')
@parent
<link href="css/musei.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script type="text/javascript">
      const MUSEI_GENERATION_ROUTE = "{{route('musei.generation')}}";
	  const API_WEATHER_ROUTE = "{{route('api.weather','')}}";
	  const API_MAP_ROUTE = "{{route('api.map')}}";
	  const INFORMAZIONI_MUSEO_ROUTE = "{{route('informazioni_museo', '')}}";
</script>
<script src="js/musei.js" defer></script>
<script src="js/map.js" defer></script>
<script src="js/weather.js" defer></script>
@endsection

@section('header_h1', 'Musei italiani')

@section('content')
<div id='loading'>
	<h1>Caricamento in corso, attendere prego...</h1>
</div>
<div id='favourites' class='hidden'>
	<h1>Preferiti:</h1>
	<div class="museumsContainer">
	</div>
</div>
<div id='results' class='hidden'>
	<div id='museumsList'>
		<h1>Elenco musei:</h1>
		<input type="text" placeholder="Cerca il nome di un museo"></input>
		<div class='museumsContainer'>
		</div>
	</div>
</div>
@endsection

@section('modal')
<article id="mapModal" class="modal hidden">
	<div class="container">
		<h1>Caricamento in corso, attendere prego...</h1>
		<img src="" />
		<p></p>
		<div class="exitButton"></div>
	</div>
</article>
<article id="mapModalError" class="modal error hidden">
	<div class="container">
		<h1>Errore: impossibile caricare la mappa.</h1>
		<h2></h2>
	</div>
</article>
@endsection
