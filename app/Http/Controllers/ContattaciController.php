<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ContattaciController extends Controller {

    public function index() {
      return view("contattaci");
    }
}
?>
