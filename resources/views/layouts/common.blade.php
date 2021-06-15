<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="icon" type="image/png" href="favicon.png">
    <title>Musei italiani - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?&family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Alegreya+Sans+SC&family=Noto+Sans+JP&family=Work+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lexend&display=swap" rel="stylesheet">
    <link href="css/common.css" rel="stylesheet">
    <script type="text/javascript">
      const LOGOUT_ROUTE = "{{route('logout')}}";
      const CSFR_TOKEN = '{{ csrf_token() }}';
    </script>
	@section('css')
	@show
	@section('js')
    <script src="js/common.js" defer></script>
	@show
  </head>
  <body>
    <header>
		<h1>@yield('header_h1')</h1>
			<a id='login' href="{{ route('login') }}" class="{{!session('id_utente') ? '' : 'hidden'}}">Login</a>
			<a id='logout' href="{{ route('logout') }}"  class="{{session('id_utente') ? '' : 'hidden'}}">Logout</a>
    </header>
    <nav>
		<a id='firstChildNav' href="{{ route('home') }}">Home</a>
		<a href="{{ route('chi_siamo') }}">Chi siamo</a>
		<a href="{{ route('musei') }}">Musei</a>
		@if (session('id_utente'))
			<a href="{{ route('personal_area') }}">Area personale</a>
		@endif
		<a id='lastChildNav' href="{{ route('contattaci') }}">Contattaci</a>
    </nav>
    <div id='content'>
		@section('content')
		@show
    </div>
    @section('modal')
    @show
    <footer>
      <p>Powered by <strong>Leonardo Messina</strong> O46002290 <br/>
        Viale della Libertà 3, 00118 Roma
      </p>
    </footer>
  </body>
</html>