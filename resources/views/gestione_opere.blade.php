@extends('layouts.common')

@section('title', 'Gestione opere')

@section('css')
@parent
<link href="css/gestione_opere.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script type="text/javascript">
      const GESTIONE_OPERE_DELETE_ROUTE = "{{route('gestione_opere.delete','')}}";
      const GESTIONE_OPERE_CHECK_ROUTE = "{{route('gestione_opere.check')}}";
</script>
<script src="js/gestione_opere.js" defer></script>
@endsection

@section('header_h1', 'Gestione opere')

@section('content')
<div>
    <table>
        <tr>
            <th>Nome opera</th>
            <th>Autore opera</th>
            <th>Anno inizio della creazione</th>
            <th>Anno ultimatura</th>
            <th>Immagine</th>
            <th>Elimina</th>
        </tr>
        @foreach($artworks as $artwork)
            <tr data-id='{{ $artwork->id }}'>
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
                <td><img class='iconaOpera' src='{{ $artwork->immagine_opera }}'/></td>
                <td><img class='iconaCancellaOpera' src='images/assets/cancella.png'/></td>
            </tr>
        @endforeach
    </table>
    <div class='button'>Inserisci un'opera</div>
</div>
<a href="{{ route('personal_area') }}" class="button" >Indietro</a>
@endsection

@section('modal')
<article id=artworkModal class="modal hidden">
    <div class="container">
        <form autocomplete='off' enctype="multipart/form-data" method='post'>
            <h1>Inserisci dati</h1>
            <div>
                <div><label for='nomeOpera'>Nome dell'opera</label></div>
                <div><input type='text' id='nomeOpera' name='nomeOpera'/></div>
            </div>
            <div>
                <div><label for='autoreOpera'>Autore</label></div>
                <div><input type='text' id='autoreOpera' name='autoreOpera'/></div>
            </div>
            <div>
                <div><label for='annoInizioCreazioneOpera'>Anno di inizio della creazione dell'opera</label></div>
                <div class='year'>
                    <input type='text' id='annoInizioCreazioneOpera' name='annoInizioCreazioneOpera'/>
                    <span>a.C.</span>
                    <input type='radio' id='annoInizioCreazioneOperaAC' name='annoInizioCreazioneOperaRadio'/>
                    <span>d.C.</span>
                    <input type='radio' id='annoInizioCreazioneOperaDC' name='annoInizioCreazioneOperaRadio' checked/>
                </div>
            </div>
            <div>
                <div><label for='annoUltimaturaOpera'>Anno di ultimatura dell'opera</label></div>
                <div class='year'>
                    <input type='text' id='annoUltimaturaOpera' name='annoUltimaturaOpera'/>
                    <span>a.C.</span>
                    <input type='radio' id='annoUltimaturaOperaAC' name='annoUltimaturaOperaRadio'/>
                    <span>d.C.</span>
                    <input type='radio' id='annoUltimaturaOperaDC' name='annoUltimaturaOperaRadio' checked/>
                </div>
            </div>
            <div>
                <div><label for='upload_original'>Caricare un'immagine del museo</label></div>
                <div>
                    <input type='file' id="upload_original" name='immagineMuseo' accept='.jpg, .jpeg, image/png'/>
                    <div id="upload"><div class="file_name">Seleziona un file...</div><div class="file_size"></div></div>
                    <div id="uploadError" class="error"></div>
                </div>    
            </div>
        </form>
        <img src=""/>
        <div class="exitButton"></div>
        <input type='button' id="saveArtwork" name="submit" value="Salva" class="button" disabled/>
        <div id="errors">
                <div class="{{count($error)>0 ? '' : 'hidden'}}">
                    <h1>Errori:</h1>
                    <p>
                        @foreach($error as $error)
                            {{$error}}<br/>
                        @endforeach
                    </p>
                </div>
        </div>
    </div>
</article>
@endsection