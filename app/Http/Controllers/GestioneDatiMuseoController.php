<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Museum;
use App\Models\City;
use App\Models\MuseumType;
use App\Models\PrivateMuseum;

class GestioneDatiMuseoController extends Controller {
	
	public function index() {
		if(!session('id_utente'))
			return redirect("home");

		$museum=Museum::find(session("id_museo"));

		$user=User::find(session("id_utente"));

		if (session("museoPubblicoPrivato")=='privato') {
			$privateMuseum=PrivateMuseum::where('museo', session("id_museo"))
				->first('nome_societa')
			;
			if($privateMuseum){
				$nomeSocieta=$privateMuseum->nome_societa;
			}
		}else{
			$nomeSocieta='';
		}

		return view('gestione_dati_museo')
			->with("error", array())
			->with('csrf_token', csrf_token())
			->with("nomeMuseo", $museum->nome)
			->with("latitudineMuseo", $museum->lat)
			->with("longitudineMuseo", $museum->lon)
			->with("costoBiglietto", $museum->costo_biglietto)
			->with("dataApertura", $museum->data_apertura)
			->with("immagineMuseo", $museum->immagine_museo)
			->with("introMuseo", $museum->introduzione)
			->with("tipoMuseo", $museum->tipo)
			->with("museum_types", MuseumType::all())
			->with("telefono1", $user->telefono1)
			->with("telefono2", $user->telefono2)
			->with("nomeSocieta", $nomeSocieta)
			->with("museoPubblicoPrivato", session('museoPubblicoPrivato'))
		;
	}

	private function must_be_numbers($number) { return preg_replace('/[^0-9]/', '', $number); }

	public function check(){
		if(!session('id_utente')) exit;

		$error=array();

		$postType=request("type");
		$fullCheck=$postType=="save";

		$filePath='';

		if(($fullCheck || !empty(request("nomeMuseo"))) && (strlen(request('nomeMuseo'))<4 || strlen(request('nomeMuseo'))>30)) {
			$error[] = "Nome del museo non valido";
		}else{
			$museum=Museum::find(session('id_museo'));
			$query=Museum::join(City::getTableName(), Museum::getTableName().'.citta', '=', City::getTableName().'.id')
				->where(Museum::getTableName().'.id', '<>', session('id_museo'))
				->where(Museum::getTableName().'.nome', request("nomeMuseo"))
				->where(City::getTableName().'.id', $museum->city->id)
				->first()
			;
			
			if ($query) {
				$error[] = "Esiste già un museo con questo nome nella città specificata";
			}
		}

		if (!empty(request('latitudineMuseo')) && !is_numeric(request('latitudineMuseo'))) {
			$error[] = "La latitudine deve essere un numero";
		}

		if (!empty(request('longitudineMuseo')) && !is_numeric(request('longitudineMuseo'))) {
			$error[] = "La longitudine deve essere un numero";
		}

		if ($fullCheck && empty(request('tipoMuseo'))) {
			$error[] = "Si deve inserire il tipo del museo";
		}

		if (!empty(request('costoBiglietto')) && !is_numeric(request('costoBiglietto'))) {
			$error[] = "Il costo del biglietto deve essere un numero";
		}
		
		if (session("museoPubblicoPrivato")=='privato') {
			if (!empty(request("nomeSocieta")) && (strlen(request("nomeSocieta")) > 100 || strlen(request("nomeSocieta")) < 3)) {
				$error[] = "Inserire un nome della società compreso tra 3 e 100 caratteri";
			}
		}

		if($fullCheck || !empty(request("telefono1"))){
			$telefono1=$this->must_be_numbers(request("telefono1"));
			if (strlen($telefono1) > 15 || strlen($telefono1) < 8) {
				$error[] = "Il primo numero di telefono non è valido";
			}else{
				$telefono1 = strtolower($telefono1);
				$query=User::where('id', '<>', session('id_utente'))
					->where(function($query) use ($telefono1){
						$query->where('telefono1', $telefono1)
						->orWhere('telefono2', $telefono1);
					})
					->first()
				;
				if ($query) {
					$error[] = "Il primo numero di telefono è già registrato";
				}
			}
		}

		if(!empty(request("telefono2"))){
			$telefono2=$this->must_be_numbers(request("telefono2"));
			if (strlen($telefono2) > 15 || strlen($telefono2) < 8){
				$error[] = "Il secondo numero di telefono non è valido";
			}else{
				$telefono2 = strtolower($telefono2);
				$query=User::where('id', '<>', session('id_utente'))
					->where(function($query) use($telefono2){
						$query->where('telefono1', $telefono2)
						->orWhere('telefono2', $telefono2);
					})
					->first()
				;
				if ($query) {
					$error[] = "Il secondo numero di telefono è già registrato";
				}
			}
		} 

		if (!empty(request("introMuseo")) && (strlen(request("introMuseo")) > 2000 || strlen(request("introMuseo")) < 100)) {
			$error[] = "L'introduzione non è della lunghezza adeguata";
		}

		if(count($error)==0 && $postType=="save"){
			$file=request('immagineMuseo');
			if ($file!=null && $file->isValid()){
				$type = exif_imagetype($file->getRealPath());
				$allowedExt = array(IMAGETYPE_PNG => 'png', IMAGETYPE_JPEG => 'jpg');
				if (isset($allowedExt[$type])) {
					if ($file->getSize()<= 10000000) {
						$museum=Museum::find(session('id_museo'));
						
						try{
							unlink($museum->immagine_museo);
						}catch(Exception $var){
						}

						$fileName = uniqid('', true).".".$allowedExt[$type];
						$filePath = 'images/images/userImages/'.$fileName;
						move_uploaded_file($file->getRealPath(),$filePath);
					} else {
						$error[] = "L'immagine non deve avere dimensioni maggiori di 10MB";
					}
				} else {
					$error[] = "I formati consentiti sono .png, .jpeg e .jpg";
				}
			}

			if (count($error)==0) {
				$museum=Museum::find(session('id_museo'));
				$museum->nome=request('nomeMuseo');
				$museum->lat=request("latitudineMuseo");
				$museum->lon=request("longitudineMuseo");
				$museum->costo_biglietto=request("costoBiglietto");
				$museum->introduzione=request("introMuseo");
				$museum->tipo=request('tipoMuseo');
				$museum->data_apertura=request('dataApertura');
				if(request('immagineMuseo'))
					$museum->immagine_museo=$filePath;
				$museum->save();
				Session::put('nome_museo', $museum->nome);

				$nomeSocieta=request("nomeSocieta");
				if($nomeSocieta){
					$privateMuseum=$museum->private_museum;
					$privateMuseum->nome_societa=$nomeSocieta;
					$privateMuseum->save();
				}

				$user=User::find(session('id_utente'));
				$user->telefono1=request("telefono1");
				$user->telefono2=request("telefono2");
				$user->save();
			}
		}

		if($postType=="save" && count($error)==0)
			return array('filePath' => $filePath);

		return $error;
	}
}

?>
