@extends('layouts.common')

@section('title', 'Registrazione')

@section('css')
@parent
<link href="css/signup.css" rel="stylesheet">
@endsection
@section('js')
@parent
<script type="text/javascript">
      const SIGNUP_CHECK_1_ROUTE = "{{route('signup.check_page_1')}}";
	  const SIGNUP_CHECK_2_ROUTE = "{{route('signup.check_page_2')}}";
	  const SIGNUP_CITIES_LIST_ROUTE = "{{route('signup.cities_list','')}}";
</script>
<script src="js/signup.js" defer></script>
@endsection

@section('header_h1', 'Registrazione')

@section('content')
<div>
	<form autocomplete='off' method='post' enctype="multipart/form-data">
		<h1>Registrati, è gratuito!</h1>
        <input type="hidden" name="_token" value="{{$csrf_token}}"/>
		<input type="hidden" name="step" value="{{ $step }}"/>
		<div class="page{{ $step!='0' ? ' hidden' : '' }}">
			<div>
				<div><label for='username'>Nome utente</label></div>
				<div><input type='text' id='username' name='username' value="{{ old('username') }}"/></div>
			</div>
			<div>
				<div><label for='password'>Password</label></div>
				<div><input type='password' id='password' name='password' value="{{ old('password') }}" maxlength="16"/></div>
			</div>
			<div>
				<div><label for='confirm_password'>Conferma Password</label></div>
				<div><input type='password' id='confirm_password' name='confirm_password' value="{{ old('confirm_password') }}"/></div>
			</div>
			<div>
				<div><label for='email'>Email</label></div>
				<div><input type='text' id='email' name='email' value="{{ old('email') }}"/></div>
			</div>
		</div>
		<div class="page{{ $step!='1' ? ' hidden' : ''}}">
			<div class="nomeMuseo">
				<div><label for='nomeMuseo'>Nome del museo</label></div>
				<div><input type='text' id='nomeMuseo' name='nomeMuseo' value="{{ old('nomeMuseo') }}"/></div>
			</div>
			<div>
				<div><label for='museoPubblicoPrivato'>Museo pubblico/privato</label></div>
				<div><select id="museoPubblicoPrivato" name="museoPubblicoPrivato">
					<option value="">-</option>
					<option value="pubblico" {{ old('museoPubblicoPrivato')=="pubblico" ? 'selected' : '' }}>Pubblico</option>
					<option value="privato" {{ old('museoPubblicoPrivato')=="privato" ? 'selected' : '' }}>Privato</option>
				</select></div>
			</div>
			<div>
				<div><label for='provinciaMuseo'>Provincia</label></div>
				<div><select id="provinciaMuseo" name="provinciaMuseo">
					<option value="none">-</option>
					@if (isset($province))
						@foreach($province as $provincia)
							<option value='{{ $provincia->sigla }}' {{ old('provinciaMuseo')==$provincia->sigla ? 'selected' : '' }}>{{ $provincia->provincia }}</option>
						@endforeach
					@endif
				</select></div>
			</div>
			<div>
				<div><label for='cittaMuseo'>Comune</label></div>
				<div><select id="cittaMuseo" name="cittaMuseo">
					<option value="">-</option>
					@if (isset($comuni))
						@foreach($comuni as $comune)
							<option value='{{ $comune->id }}' {{ old('cittaMuseo')==$comune->id ? 'selected' : '' }}>{{ $comune->comune }}</option>
						@endforeach
					@endif
				</select></div>
			</div>
			<div>
				<div><label for='tipoMuseo'>Tipo del museo</label></div>
				<div><select id="tipoMuseo" name="tipoMuseo">
					<option value="">-</option>
					@if (isset($tipi))
						@foreach($tipi as $tipo)
							<option value='{{ $tipo->id }}' {{ old('tipoMuseo')==$tipo->id ? 'selected' : '' }}>{{ $tipo->tipo }}</option>
						@endforeach
					@endif
				</select></div>
			</div>
			<div>
				<div><label for='dataApertura'>Data di apertura del museo</label></div>
				<div><input type='date' id='dataApertura' name='dataApertura' value="{{ old('dataApertura') }}"/></div>
			</div>
			<div>
				<div><label for='telefono1'>Numero di telefono n.1</label></div>
				<div><input type='text' id='telefono1' name='telefono1' value="{{ old('telefono1') }}" maxlength="15"/></div>
			</div>
			<div>
				<div><label for='telefono2'>Numero di telefono n.2</label></div>
				<div><input type='text' id='telefono2' name='telefono2' value="{{ old('telefono2') }}" maxlength="15"/></div>
			</div>
			<div>
				<div><label for='upload_original'>Caricare un'immagine del museo</label></div>
				<div>
					<input type='file' id="upload_original" name='immagineMuseo' accept='.jpg, .jpeg, image/png'/>
					<div id="upload"><div class="file_name">Seleziona un file...</div><div class="file_size"></div></div>
					<div id="uploadError" class="error"></div>
				</div>    
			</div>
		</div>
		<div class="buttons">
			<input type='submit' id="backButton" name="submit" value="Indietro" {{ $step=='0' ? ' disabled' : '' }} />
			<input type='submit' id="nextButton" name="submit" value="Avanti" {{ !$nextEnabled ? ' disabled' : '' }} />
		</div>
	</form>
	<div class='registerTip'>Hai già un account? <a href="{{ route('login') }}">Accedi</a></div>
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
@endsection