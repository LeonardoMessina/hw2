<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Museum;
use App\Models\Artwork;

class GestioneOpereController extends Controller {
	
	public function index() {
		if(!session('id_utente'))
			return redirect("home");

		$artworks=Artwork::where('museo', session("id_museo"))
		->orderby('nome')
		->get();

		return view('gestione_opere')
			->with("error", array())
			->with('csrf_token', csrf_token())
			->with("artworks", $artworks);
	}

	public function delete($idOpera){
		$opera=Artwork::find($idOpera);

		try{
			unlink($opera->immagine_opera);
		}catch(Exception $var){
		}

		$opera->delete();
	}

	public function check(){
		$error=array();

		$fullCheck=request("type")=="save";

		if(($fullCheck || !empty(request("nomeOpera"))) && !preg_match("/^[a-zA-Z0-9 ']{1,30}$/", request('nomeOpera'))) {
			$error[] = "Nome dell'opera non valido";
		}
		
		if(($fullCheck || !empty(request("autoreOpera"))) && !preg_match("/^[a-zA-Z ']{2,30}$/", request('autoreOpera'))) {
			$error[] = "Nome dell'autore non valido ";
		}else if(!empty(request("autoreOpera"))){
			$artwork=Artwork::where('nome', request('nomeOpera'))
				->where('autore', request('autoreOpera'))
				->first();
			if ($artwork) {
				$error[] = "Opera già inserita";
			}
		}

		if(!empty(request("annoInizioCreazioneOpera")) && !preg_match("/^[0-9]{1,7}$/", request('annoInizioCreazioneOpera'))){
			$error[] = "L'anno di inizio della creazione dell'opera non è valido";
			$annoInizioCreazioneOpera=null;
		}else{
			$annoInizioCreazioneOpera=request("annoInizioCreazioneOpera")*request("annoInizioCreazioneOperaSegno");
		}
	
		if(!empty(request("annoUltimaturaOpera")) && !preg_match("/^[0-9]{1,7}$/", request('annoUltimaturaOpera'))){
			$error[] = "L'anno di ultimatura dell'opera non è valido";
			$annoUltimaturaOpera=null;
		}else{
			$annoUltimaturaOpera=request("annoUltimaturaOpera")*request("annoUltimaturaOperaSegno");
		}

		if ($annoInizioCreazioneOpera && $annoUltimaturaOpera && $annoInizioCreazioneOpera>$annoUltimaturaOpera){
			$error[] = "L'anno di inizio della creazione dell'opera deve essere antecedente a quello di ultimatura";
		}

		if(count($error)==0 && request("type")=="save"){
			$file=request('immagineOpera');
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
				$error[] = "Inserire un'immagine dell'opera";
			}
		
			if (count($error) == 0) {
				$artwork=new Artwork;
				$artwork->nome=request('nomeOpera');
				$artwork->autore=request('autoreOpera');
				$artwork->anno_inizio_creazione=request('annoInizioCreazioneOpera');
				$artwork->anno_ultimatura=request('annoUltimaturaOpera');
				$artwork->museo=session('id_museo');
				$artwork->immagine_opera=$filePath;
				$artwork->save();

				$id_opera=$artwork->id;

				return array('idOpera' => $id_opera, 'filePath' => $filePath);
			}
		}
		return $error;		
	}
}

?>