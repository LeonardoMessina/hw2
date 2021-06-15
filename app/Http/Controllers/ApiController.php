<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Museum;
use App\Models\City;

class ApiController extends Controller {
	
	public function weather($id){
		$city=Museum::find($id)->city;
		
		$url="https://api.weatherbit.io/v2.0/current?key=".env('WEATHERBIT_APIKEY')."&lang=it&city=".urlencode("$city->comune");
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$result=curl_exec($curl);		
		if(!str_starts_with($result,"{")) //Se il servizio non risponde in tempo, restituisce una risposta html, non un JSON, in tal caso ignoriamo la risposta
			return null;	

		return $result;
	}

	public function map(){
		$id=request("id");
		$width=request("width");
		$height=request("height");
		$type=request("type");
	
		$coordinates=Museum::select('lat', 'lon')
			->find($id);
		if(!$coordinates)
			return;
	
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,"https://dev.virtualearth.net/REST/v1/Imagery/Map/Road/$coordinates->lat,$coordinates->lon/15?ml=OrdnanceSurvey&mapSize=$width,$height&format=jpeg&key=".env('BINGMAPS_APIKEY')."&dcl=1&mapMetadata=1");
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$info=json_decode(curl_exec($curl));
			$bbox=$info->resourceSets[0]->resources[0]->bbox;
		}
	
		{
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,"http://dev.virtualearth.net/REST/v1/Traffic/Incidents/$bbox[0],$bbox[1],$bbox[2],$bbox[3]/false?severity=1,2,3,4&c=it&type=1,2,3,4,5,6,7,8,9,10,11&key=".env('BINGMAPS_APIKEY'));
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			$trafficMap=json_decode(curl_exec($curl));
		}
	
		if($type=="text"){
			$result=array();
			for($i=0;$i<count($trafficMap->resourceSets);$i++){
				$traffic=$trafficMap->resourceSets[$i];
				for($j=0;$j<count($traffic->resources);$j++){
					$resource=$traffic->resources[$j];
					$result[]=$resource->description;
				}
			}
			echo json_encode($result);
		}else{
			$url="https://dev.virtualearth.net/REST/v1/Imagery/Map/Road/$coordinates->lat,$coordinates->lon/15?ml=OrdnanceSurvey&pushpin=$coordinates->lat,$coordinates->lon;46&mapSize=$width,$height&format=jpeg&key=".env('BINGMAPS_APIKEY')."&dcl=1";
			for($i=0;$i<count($trafficMap->resourceSets);$i++){
				$traffic=$trafficMap->resourceSets[$i];
				for($j=0;$j<count($traffic->resources);$j++){
					$resource=$traffic->resources[$j];
					$v1=$resource->point->coordinates[0];
					$v2=$resource->point->coordinates[1];
					$url=$url."&pushpin=$v1,$v2;17";
				}
			}
	
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL,$url);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
			header("Content-Type: image/jpg");
			
			return curl_exec($curl);
		}
	}
}	
?>