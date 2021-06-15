@extends('layouts.common')

@section('title', 'Gestione dati del museo')

@section('css')
@parent
<link href="css/gestione_dati_museo.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script type="text/javascript">
      const GESTIONE_DATI_MUSEO_CHECK_ROUTE = "{{route('gestione_dati_museo.check')}}";
</script>
<script src="js/gestione_dati_museo.js" defer></script>
@endsection

@section('header_h1', 'Gestione dati del museo')

@section('content')
<form autocomplete='off' enctype="multipart/form-data" method='post'>
    <div id='input'>
        <div>
            <div>
                <div><label for='nomeMuseo'>Nome del museo</label></div>
                <div><input type='text' id='nomeMuseo' name='nomeMuseo' value="{{ $nomeMuseo }}"/></div>
            </div>
            <div>
                <div><label for='latitudineMuseo'>Latitudine del museo</label></div>
                <div><input type='text' id='latitudineMuseo' name='latitudineMuseo' value="{{ $latitudineMuseo }}"/></div>
            </div>
            <div>
                <div><label for='longitudineMuseo'>Longitudine del museo</label></div>
                <div><input type='text' id='longitudineMuseo' name='longitudineMuseo' value="{{ $longitudineMuseo }}"/></div>
            </div>
            <div>
                <div><label for='tipoMuseo'>Tipo del museo</label></div>
                <div><select id="tipoMuseo" name="tipoMuseo">
                    <option value="">-</option>
                    @foreach($museum_types as $museum_type)
                        <option value='{{ $museum_type->id }}' {{ $tipoMuseo==$museum_type->id ? 'selected' : '' }}>{{ $museum_type->tipo }}</option>
                    @endforeach
                </select></div>
            </div>
            <div>
                <div><label for='costoBiglietto'>Costo del biglietto</label></div>
                <div><input type='text' id='costoBiglietto' name='costoBiglietto' value="{{ $costoBiglietto }}"/></div>
            </div>
            <div>
                <div><label for='dataApertura'>Data di apertura del museo</label></div>
                <div><input type='date' id='dataApertura' name='dataApertura' value="{{ $dataApertura }}"/></div>
            </div>
            <div>
                <div><label for='telefono1'>Numero di telefono n.1</label></div>
                <div><input type='text' id='telefono1' name='telefono1' value="{{ $telefono1 }}" maxlength='15'/></div>
            </div>
            <div>
                <div><label for='telefono2'>Numero di telefono n.2</label></div>
                <div><input type='text' id='telefono2' name='telefono2' value="{{ $telefono2 }}" maxlength='15'/></div>
            </div>
            <div class='{{ $museoPubblicoPrivato=="pubblico" ? "hidden" : "" }}'>
                <div><label for='nomeSocieta'>Nome della società del museo privato</label></div>
                <div><input type='text' id='nomeSocieta' name='nomeSocieta' value="{{ $nomeSocieta }}"/></div>
            </div>
            <div>
                <div><label for='introMuseo'>Introduzione al museo</label></div>
                <div><textarea id='introMuseo' name='introMuseo' maxlength='2000'>{{ $introMuseo }}</textarea></div>
            </div>
        </div>
        <div>
            <div>
                <img id='image' src='{{ $immagineMuseo }}'/>
                <div><label for='upload_original'>Cambiare l'immagine del museo</label></div>
                <div>
                    <input type='file' id='upload_original' name='immagineMuseo' accept='.jpg, .jpeg, image/png'/>
                    <div id="upload"><div class="file_name">Seleziona un file...</div><div class="file_size"></div></div>
                    <div id="uploadError" class="error"></div>
                </div>    
            </div>
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
    </div>
    <div class="buttons">
        <a href="{{ route('personal_area') }}" class='button left'>Indietro</a>
        <input type='button' id="save" value="Salva" class="button right" disabled/>
    </div>
</form>
@endsection