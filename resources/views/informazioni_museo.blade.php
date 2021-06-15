@extends('layouts.common')

@section('title', 'Informazioni museo')

@section('css')
@parent
<link href="css/informazioni_museo.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script type="text/javascript">
      const INFORMAZIONI_MUSEO_ARTWORKS_ROUTE = "{{route('informazioni_museo.artworks')}}";
</script>
<script src="js/informazioni_museo.js" defer></script>
@endsection

@section('header_h1', 'Informazioni museo')

@section('content')
<div id='museo'>
    <div id='fields'>
        <h1>Nome: <span>{{ $nomeMuseo }}</span></h1>
        <h1>Tipo: <span>{{ $tipoMuseo }}</span></h1>
        <h1 class='{{  $costoBiglietto ? "" : "hidden" }}'>Costo del biglietto: <span>{{ $costoBiglietto }} &euro;</span></h1>
        <h1>Data di apertura: <span>{{ $dataApertura }}</span></h1>
        <h1>Tipo di gestione: <span>{{ $idMuseoPrivato ? "Privata" : "Pubblica" }}</span></h1>
        <h1>Città: <span>{{ $comune }}</span></h1>
        <h1>Provincia: <span>{{ $provincia }}</span></h1>
    </div>
    <div id='image'>
        <img src='{{ $immagineMuseo }}'/>
    </div>
</div>
<p id='introduction'>{{ $introduzione }}</p>
<div id='opere'>
    <div>
        <h1>Opere:</h1>
        <div>
            Ricerca per un periodo storico
            <span>da: <input id="annoIniziale" type="text" placeholder="Numeri negativi per a.C" name="annoIniziale"/></span>
            <span>a: <input id="annoFinale" type="text" placeholder="Numeri negativi per a.C" name="annoFinale"/></span>
        </div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Nome opera</th>
                <th>Autore opera</th>
                <th>Anno inizio della creazione</th>
                <th>Anno ultimatura</th>
                <th>Immagine</th>
            </tr>
        </thead>
        <tbody>
            @foreach($artworks as $artwork)
                <tr data-id='$artwork->id'>
                    <td>{{ $artwork->nome }}</td>
                    <td>{{ $artwork->autore }}</td>
                    @if($artwork->anno_inizio_creazione)
                        <td>{{ abs($artwork->anno_inizio_creazione) }} {{ $artwork->anno_inizio_creazione>0 ? "d.C." : "a.C." }}</td>
                    @else
                        <td>N.D.</td>
                    @endif
                    @if($artwork->anno_ultimatura)
                        <td>{{ abs($artwork->anno_ultimatura) }} {{ $artwork->anno_ultimatura>0 ? "d.C." : "a.C." }}</td>
                    @else
                        <td>N.D.</td>
                    @endif    
                    <td><img class='iconaOpera' src={{ $artwork->immagine_opera }}/></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection