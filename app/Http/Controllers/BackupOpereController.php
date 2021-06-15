<?php

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\Backup;

class BackupOpereController extends Controller {
	
	public function index() {
		if(!session('id_utente'))
			return redirect("home");
		
		$backups=Backup::where('museo', session("id_museo"))
			->orderby('created_at', 'desc')
			->get();

		return view('backup_opere')
			->with("backups", $backups);
	}

	public function backup(){
		if(!session('id_utente'))
			return;

		$result=array();
		$result["isError"]=false;
		$result["message"]="Backup effettuato con successo";
		
		try{
			DB::statement("call pBackupOpere(".session('id_museo').")");
			$newBackup=Backup::where('museo',session("id_museo"))
				->orderby('created_at', 'desc')
				->first()
			;
			$result["id"]=$newBackup->id;
			$result["date"]=$newBackup->created_at->toDateTimeString();
		}catch(Exception $e){
			$result["isError"]=true;
			$result["message"]=$e->getMessage();
		}
		return $result;	
	}
}

?>