<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class ChiSiamoController extends Controller {

    public function index() {
      return view("chi_siamo");
    }
}
?>
