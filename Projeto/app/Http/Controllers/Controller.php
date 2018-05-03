<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\User;
use App\Movement;
use App\Account;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    

    public function initialPage() {
    	$users = User::all();
		$movements = Movement::all();
		$accounts = Account::all();
		$pagetitle = "Página inicial";
    	return view('initialPage', compact('users', 'movements', 'accounts', 'pagetitle'));
    }
}
