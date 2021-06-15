<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class LoginController extends Controller {
  
  public function index() {
    $session_id_utente = session('id_utente');

    if($session_id_utente!=null)
      return redirect("home");
 
    return view('login')
      ->with("error", array())
      ->with('csrf_token', csrf_token())
    ;
  }

  public function check(){
    $error=array();

    if (!empty(request('usernameEmail')) && !empty(request('password')))
        $loginType = filter_var(request('usernameEmail'), FILTER_VALIDATE_EMAIL) ? "email" : "username";  
    else
      $error[] = "Inserisci username e password";

    if(count($error)==0){
      $utente=User::where($loginType, request('usernameEmail'))
        ->first();

      if(!$utente || !password_verify(request('password'),$utente->password))
        $error[] = "Username e/o password errati";
    }

    if(count($error)>0){
      Request::flash();
      return view('login')
        ->with('csrf_token', csrf_token())
        ->with("error", $error)
      ;
    }

    $museo=$utente->museum;
    $museoPrivato=$museo->private_museum;

    Session::put('id_utente', $utente->id);
    Session::put('username', $utente->username);
    Session::put('email', $utente->email);
    Session::put('id_museo', $museo->id);
    Session::put('nome_museo', $museo->nome);
    Session::put('museoPubblicoPrivato', $museoPrivato ? "privato" : "pubblico");

    return redirect('personal_area');
  }

  public function logout() {
		Session::flush();
    return redirect("home");
	}
}

?>
