<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Museum;
use App\Models\MuseumType;
use App\Models\PrivateMuseum;
use App\Models\City;

class MuseiController extends Controller {
	
	public function index() {
		return view('musei');
	}

	public function generation(){
		$search=request("search");

		$museums=Museum::join(City::getTableName(), Museum::getTableName().'.citta', '=', City::getTableName().'.id')
			->join(MuseumType::getTableName(), Museum::getTableName().'.tipo', '=', MuseumType::getTableName().'.id')
			->leftJoin(PrivateMuseum::getTableName(), Museum::getTableName().'.id', '=', PrivateMuseum::getTableName().'.museo')
			->select(Museum::getTableName().'.id as id_museo', Museum::getTableName().'.nome as nome_museo', Museum::getTableName().'.lat as latitudine_museo',
			Museum::getTableName().'.lon as longitudine_museo', MuseumType::getTableName().'.tipo as tipo_museo',
			Museum::getTableName().'.immagine_museo as immagine_museo', Museum::getTableName().'.introduzione as introduzione',
			PrivateMuseum::getTableName().'.museo as id_museo_privato', City::getTableName().'.comune as comune', City::getTableName().'.provincia as provincia')
		;
		if(!empty($search))
			$museums=$museums->where("nome","like","%$search%");
		$museums=$museums->orderby(Museum::getTableName().'.nome')
			->get()
		;

		$result=array();
		foreach($museums as $museum){
			$result[]=array(
				"id"=>$museum->id_museo,
				"name"=>$museum->nome_museo,
				"type"=>$museum->tipo_museo,
				"idMuseoPrivato"=>$museum->id_museo_privato,
				"city"=>$museum->comune,
				"provincia"=>$museum->provincia,
				"description"=>$museum->introduzione,
				"image"=>$museum->immagine_museo,
				"coordinate"=>array("lat"=>$museum->latitudine_museo,"lon"=>$museum->longitudine_museo)
			);
		}
		return $result;
	}
}

?>