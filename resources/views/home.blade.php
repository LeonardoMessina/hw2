@extends('layouts.common')

@section('title', 'Home')

@section('css')
@parent
<link href="css/home.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script src="js/home.js" defer></script>
@endsection

@section('header_h1', 'Musei italiani')

@section('content')
<div class="carousel">
    @foreach($museums as $museum)
        <div class='carousel-item'>
            <section>
                <img src='{{$museum->immagine_museo}}'/>
                <h1>{{$museum->nome}}</h1>
                <h2>Città: {{$museum->comune}}</h2>
                <h2>Tipo: {{$museum->tipo}}</h2>
                <div><p>{{$museum->introduzione}}</p></div>
            </section>
        </div>
    @endforeach
</div>
<div id='introduction'>
    <h1>Benvenuto!</h1>
    <p>
        Gentile visitatore,<br/>
        se vuoi registrare il tuo museo, basta fare click sul pulsante "Login" e poi iscriversi.<br/>
        Il nostro sito offre agli utenti registrati la possibilità di inserire molte informazioni utii riguardanti il proprio museo, oltre che una sua immagine e
        l'elenco delle opere.<br/>
        Se sei invece un utente non registrato, nel nostro sito avrai la possibilità di visionare tutti i dati d'interesse delle centinaia (forse un po' di meno) di musei che hanno
        scelto noi!
    </p>
</div>
@endsection