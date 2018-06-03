<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public function showTotalExpensesAndRevenues(Request $request, $id)
    {
        $this->validateRequest($request);

        $init= $this->calculateDateForQuery($request->input('initial'));
        $final=$this->calculateDateForQuery($request->input('final'));

        $pagetitle="Total revenues and expenses between ".date('d/m/Y', strtotime($request->input('initial')))." and ".date('d/m/Y', strtotime($request->input('final')));

        $totalExpenseRevenue = DB::table('movements')
            ->join('movement_categories', 'movements.movement_category_id', '=', 'movement_categories.id')
            ->join('accounts', 'movements.account_id', '=', 'accounts.id')
            ->select(DB::raw('sum(movements.value) as total, movement_categories.name,movements.type'))
            ->where('movements.date', '>=', $init)
            ->where('movements.date', '<=', $final)
            ->where('accounts.owner_id', '=', $id)
            ->groupBy('movement_categories.name', 'movements.type')
            ->get();

        //Consulta no adminer para confirmar --> depois apagar
        /*SELECT sum(m.`value`) as total,mov.`name`, m.`type`
        FROM `movements` m
        inner join `accounts` a
        on m.`account_id`=a.`id`
        inner join `movement_categories` mov
        on m.`movement_category_id`=mov.`id`
        where a.`owner_id`=44 and DATE_ADD(m.`date`,INTERVAL -1 MONTH)>='2016-12' and DATE_ADD(m.`date`,INTERVAL -1 MONTH)<='2018-05'
        group by mov.`name`, m.`type`;
        */
        return view('accounts.partials.expensesRevenuesByDate', compact('totalExpenseRevenue', 'pagetitle', 'id'));
    }

    public function showMonthlyEvolution(Request $request, $id)
    {
        $this->validateRequest($request);

        $init= $this->calculateDateForQuery($request->input('initial'));
        $final=$this->calculateDateForQuery($request->input('final'));

        $pagetitle="Evolution of expenses and revenues by category between ".date('M Y', strtotime($request->input('initial')))." and ".date('M Y');

        $evolutionExpenseRevenue = DB::table('movements')
            ->join('movement_categories', 'movements.movement_category_id', '=', 'movement_categories.id')
            ->join('accounts', 'movements.account_id', '=', 'accounts.id')
            ->select(DB::raw('sum(movements.value) as total, movement_categories.name,movements.type, date_format(movements.date,"%Y-%m") as date'))
            ->where('movements.date', '>=', $init)
            ->where('movements.date', '<=', $final)
            ->where('accounts.owner_id', '=', $id)
            ->groupBy('movement_categories.name', 'movements.type', DB::raw('date'))
            ->orderBy(DB::raw('date'))
            ->get();

        //Consulta no adminer para confirmar --> depois apagar
        /*SELECT sum(m.`value`) as total,mov.`name`, m.`type`,date_format(m.`date`,'%M %Y') as date
        FROM `movements` m
        inner join `accounts` a
        on m.`account_id`=a.`id`
        inner join `movement_categories` mov
        on m.`movement_category_id`=mov.`id`
        where a.`owner_id`=44 and DATE_ADD(m.`date`,INTERVAL -1 MONTH)>='2016-12' and DATE_ADD(m.`date`,INTERVAL -1 MONTH)<='2018-05'
        group by mov.`name`, m.`type`,date_format(m.`date`,'%M %Y');
        */

        return view('accounts.partials.expensesRevenuesEvolution', compact('evolutionExpenseRevenue', 'pagetitle', 'id'));
    }

    private function validateRequest(Request $request)
    {
        $validatedData=$request->validate([
            'initial'=>'required|date|before_or_equal:final',
            'final'=>'required|date|after_or_equal:initial',
        ], [
        'initial.required' => 'You must enter initial date',
        'final.required' => 'You must enter final date',
        ]);
    }

    private function calculateDateForQuery($date)
    {
        return date('Y-m-d', strtotime("+0 months", strtotime($date)));
    }
}
