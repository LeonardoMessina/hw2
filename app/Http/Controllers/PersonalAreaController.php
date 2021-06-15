<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class PersonalAreaController extends Controller {

    public function index() {
      if(!session('id_utente'))
			  return redirect("home");

      return view("personal_area");
    }
}
?>