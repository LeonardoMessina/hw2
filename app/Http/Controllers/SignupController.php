<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Museum;
use App\Models\Province;
use App\Models\City;
use App\Models\MuseumType;
use App\Models\PrivateMuseum;

class SignupController extends Controller {

	public function index() {
		$session_id_utente = session('id_utente');

		if($session_id_utente!=null)
			return redirect("home");

		return view('signup')
			->with('csrf_token', csrf_token())
			->with("step", '0')
			->with("error", array())
			->with("nextEnabled",false)
		;
	}

	public function checkPage1($fullCheck=null){
		$error=array();
		$username = request('username');
		$password = request('password');
		$confirm_password = request('confirm_password');
		$email = request('email');

        if($fullCheck || !empty($username)){
            if(!preg_match('/^[a-zA-Z0-9_$.]{4,16}$/', $username)){
                $error[] = "Nome utente non valido";
            } else {
				if(User::where('username', $username)->count()>0)
                    $error[] = "Nome utente già utilizzato";
            }
        }

        if(($fullCheck || !empty($password)) && !preg_match('/^[a-zA-Z0-9_$!.%]{8,16}$/', $password))
            $error[] = "Password non valida";

        if (($fullCheck || !empty($confirm_password)) && strcmp($password, $confirm_password) != 0)
            $error[] = "Le password non coincidono";

        if ($fullCheck || !empty($email)){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error[] = "Email non valida";
            } else {
				if(User::where('email', strtolower($email))->count()>0)
                    $error[] = "Email già utilizzata";
            }
        }

		return $error;
	}

	public function citiesList($sigla){
		$result='<option value="">-</option>';
		$cities=City::where('provincia', $sigla)
			->orderby('comune')
			->get()
		;
		foreach($cities as $city){
			$result.='<option value="'.$city->id.'">'.$city->comune.'</option>';
		}
		return $result;
	}

	private function must_be_numbers($number) { return preg_replace('/[^0-9]/', '', $number); }

	public function checkPage2($fullCheck=null){
		$error=array();
		$nomeMuseo=request('nomeMuseo');
		$museoPubblicoPrivato=request('museoPubblicoPrivato');
		$cittaMuseo=request('cittaMuseo');
		$tipoMuseo=request('tipoMuseo');
		$dataApertura=request('dataApertura');
		$telefono1=request('telefono1');
		$telefono2=request('telefono2');

		if(($fullCheck || !empty($nomeMuseo)) && (strlen($nomeMuseo)<4 || strlen($nomeMuseo)>30)){
			$error[] = "Nome del museo non valido";
		}

		if($fullCheck && empty($museoPubblicoPrivato)){
			$error[] = "Specificare se il museo è pubblico o privato";
		}

		if($fullCheck && empty($cittaMuseo)){
			$error[] = "Inserire la città del museo";
		}
		if($fullCheck && empty($tipoMuseo)){
			$error[] = "Inserire il tipo del museo";
		}

		if($fullCheck && empty($dataApertura))
			$error[] = "Inserire una data";

		$museum=Museum::where('nome', $nomeMuseo)
			->where('citta', $cittaMuseo)->first();	
		if ($museum) {
			$error[] = "Museo già registrato";
		}

		if($fullCheck || !empty($telefono1)){
			if($telefono1!=$this->must_be_numbers($telefono1) || strlen($telefono1) > 15 || strlen($telefono1) < 8) {
				$error[] = "Il primo numero di telefono non è valido";
			} else {
				$telefono1 = User::where('telefono1', $telefono1)
					->orWhere('telefono2', $telefono1)
					->first()
				;
				if ($telefono1) {
					$error[] = "Il primo numero di telefono è già registrato";
				}
			}
		}
		
		if(!empty($telefono2)){
			if($telefono2!=$this->must_be_numbers($telefono2) || strlen($telefono2) > 15 || strlen($telefono2) < 8) {
				$error[] = "Il secondo numero di telefono non è valido";
			}else {
				$telefono2 = User::where('telefono1', $telefono2)
					->orWhere('telefono2', $telefono2)
					->first()
				;
				if ($telefono2) {
					$error[] = "Il secondo numero di telefono è già registrato";
				}
			}
		}
		return $error;
	}

	private function postResult($step,$error,$nextEnabled){
		$province= Province::orderBy('provincia')
			->get()
		;
		$comuni=City::where('provincia', request('provinciaMuseo'))
			->orderBy('comune')
			->get()
		;
		$tipi=MuseumType::all();

		Request::flash();
		return view('signup')
			->with('csrf_token', csrf_token())
			->with("step", $step)
			->with("error", $error)
			->with("nextEnabled",$nextEnabled)
			->with("province", $province)
			->with("comuni", $comuni)
			->with("tipi", $tipi)
		;
	}

	public function signup(){
		$step=request('step');

		if($step=="0"){
			$error1=$this->checkPage1(true);
			if(count($error1)>0)
				return $this->postResult("0",$error1,false);
			else{
				$error2=$this->checkPage2(true);
				return $this->postResult("1",$error1,count($error2)==0);
			}
		}else if($step=="1"){
			if(request("submit")=="Indietro")
				return $this->postResult("0",array(),true);
			
			$error1=$this->checkPage1(true); //Per evitare che un utente malintenzionato modfichi inopportunamente i campi della prima pagina quando si trova nella seconda
			$error2=$this->checkPage2(true);
			$error=array_merge($error1,$error2);
			if(count($error)>0)
				return $this->postResult("1",$error,false);

			$file=request('immagineMuseo');
			if ($file!=null && $file->isValid()){
				$type = exif_imagetype($file->getRealPath());
				$allowedExt = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg');
				if (isset($allowedExt[$type])) {
					if ($file->getSize()<= 10000000) {
						$fileName = uniqid('', true).".".$allowedExt[$type];
						$filePath = 'images/images/userImages/'.$fileName;
						move_uploaded_file($file->getRealPath(),$filePath);
					} else {
						$error[] = "L'immagine non deve avere dimensioni maggiori di 10MB";
					}
				} else {
					$error[] = "I formati consentiti sono .png, .jpeg e .jpg";
				}
			}else{
				$error[] = "Inserire un'immagine del museo";
			}

			if (count($error) == 0) {
				$museum=new Museum;
				$museum->nome=request('nomeMuseo');
				$museum->citta=request('cittaMuseo');
				$museum->tipo=request('tipoMuseo');
				$museum->data_apertura=request('dataApertura');
				$museum->immagine_museo=$filePath;
				$museum->save();
				Session::put('nome_museo', $museum->nome);
				Session::put('id_museo', $museum->id);

				$user=new User;
				$user->museo=$museum->id;
				$user->username=request('username');
				$user->password=password_hash(request('password'), PASSWORD_BCRYPT);
				$user->email=request('email');
				$user->telefono1=$this->must_be_numbers(request('telefono1'));
				$user->telefono2=empty(request('telefono2')) ? null : $this->must_be_numbers(request('telefono2'));
				$user->save();
				Session::put('id_utente', $user->id);
				Session::put('username', request('username'));
				Session::put('email', request('email'));

				if(request('museoPubblicoPrivato')=='privato'){
					$private_museum=new PrivateMuseum;
					$private_museum->museo=$museum->id;
					$private_museum->save();
				}
				Session::put('museoPubblicoPrivato',request('museoPubblicoPrivato'));
				return redirect("personal_area");
			}

			return $this->postResult($step,$error,true);
		}
	}
	
}

?>
