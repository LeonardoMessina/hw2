<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Museum;
use App\Models\MuseumType;
use App\Models\City;

class HomeController extends Controller {

    public function index() {
      $museums=Museum::join(City::getTableName(), Museum::getTableName().'.citta', '=', City::getTableName().'.id')
        ->join(MuseumType::getTableName(), Museum::getTableName().'.tipo', '=', MuseumType::getTableName().'.id')
        ->whereNotNull('introduzione')
        ->orderby(Museum::getTableName().'.updated_at', 'desc')
        ->take(5)
        ->get()
      ;

      return view("home")
        ->with("museums", $museums);
    }
}
?>
