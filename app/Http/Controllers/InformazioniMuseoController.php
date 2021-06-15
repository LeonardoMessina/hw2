<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Museum;
use App\Models\MuseumType;
use App\Models\PrivateMuseum;
use App\Models\City;
use App\Models\Artwork;

class InformazioniMuseoController extends Controller {
	
	public function index() {
		$idMuseo=request('id');

		if(empty($idMuseo))
			return redirect("home");
		
		$museumInfo=Museum::join(City::getTableName(), Museum::getTableName().'.citta', '=', City::getTableName().'.id')
		->join(MuseumType::getTableName(), Museum::getTableName().'.tipo', '=', MuseumType::getTableName().'.id')
		->leftJoin(PrivateMuseum::getTableName(), Museum::getTableName().'.id', '=', PrivateMuseum::getTableName().'.museo')
		->select(Museum::getTableName().'.id as id_museo', Museum::getTableName().'.nome as nome_museo', Museum::getTableName().'.lat as latitudine_museo',
			Museum::getTableName().'.lon as longitudine_museo', MuseumType::getTableName().'.tipo as tipo_museo',
			Museum::getTableName().'.costo_biglietto as costo_biglietto', Museum::getTableName().'.data_apertura as data_apertura',
			Museum::getTableName().'.immagine_museo as immagine_museo', Museum::getTableName().'.introduzione as introduzione',
			PrivateMuseum::getTableName().'.museo as id_museo_privato', City::getTableName().'.comune as comune', City::getTableName().'.provincia as provincia',
			City::getTableName().'.regione as regione')				
		->where(Museum::getTableName().'.id',$idMuseo)	
		->first()
		;
		
		$artworks=Artwork::where('museo',$idMuseo)
			->orderby('nome')
			->get();

		return view('informazioni_museo')
			->with("idMuseo", $museumInfo->id_museo)
			->with("nomeMuseo", $museumInfo->nome_museo)
			->with("latitudineMuseo", $museumInfo->latitudine_museo)
			->with("longitudineMuseo", $museumInfo->longitudine_museo)
			->with("tipoMuseo", $museumInfo->tipo_museo)
			->with("costoBiglietto", $museumInfo->costo_biglietto)
			->with("dataApertura", $museumInfo->data_apertura)
			->with("immagineMuseo", $museumInfo->immagine_museo)
			->with("introduzione", $museumInfo->introduzione)
			->with("idMuseoPrivato", $museumInfo->id_museo_privato)
			->with("comune", $museumInfo->comune)
			->with("provincia", $museumInfo->provincia)
			->with("regione", $museumInfo->regione)
			->with("artworks", $artworks)
		;
	}

	public function artworks(){
		$error = array();
		$result=array();

		$idMuseo=request('idMuseo');
		$anno_inizio=request("annoInizio") ? request("annoInizio") : "";
		$anno_fine=request("annoFine") ? request("annoFine") : "";
	
		$hasErrors=false;
		if(!empty($anno_inizio) && !empty($anno_fine)){
			if($anno_inizio>$anno_fine || !filter_var($anno_inizio, FILTER_VALIDATE_INT) || !filter_var($anno_fine, FILTER_VALIDATE_INT))
				$hasErrors=true;
		}else if(!empty($anno_inizio)){
			$anno_fine='null';
			if(!filter_var($anno_inizio, FILTER_VALIDATE_INT))
				$hasErrors=true;
		}else if(!empty($anno_fine)){
			$anno_inizio='null';
			if(!filter_var($anno_fine, FILTER_VALIDATE_INT))
				$hasErrors=true;
		}else{
			$anno_inizio='null';
			$anno_fine='null';
		}
		if(!$hasErrors){
			DB::statement("call pFiltraOpereAnno($idMuseo, $anno_inizio, $anno_fine)");
			$artworks=DB::select("select * from temp");
			foreach($artworks as $artwork){
				$opera=array();
				$opera["nome"]=$artwork->nome;
				$opera["autore"]=$artwork->autore;
				$opera["anno_inizio_creazione"]=$artwork->anno_inizio_creazione;
				$opera["anno_ultimatura"]=$artwork->anno_ultimatura;
				$opera["immagine_opera"]=$artwork->immagine_opera;
	
				$result[]=$opera;
			}
		}
		return $result;		
	}
}

?>