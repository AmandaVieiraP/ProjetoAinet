<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Validation\Rule;
use App\Account;
use App\Document;
use App\Movement;
use App\MovementCategories;
use Illuminate\Http\UploadedFile;
use Validator;
use App\Rules\DocumentDescriptionWithoutDocument;
use Debugbar;

class MovementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)  // account id
    {
        $account = Account::findOrFail($id);
        $accountOwner = $account->user;
        if (Gate::denies('view-movements', $id)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        $movements = $account->movements()->orderBy('date', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        $movementCategories = MovementCategories::all();

        $pagetitle = "Movements";
        return view('movements.listMovementsOfAccount', compact('movements', 'movementCategories', 'account', 'pagetitle'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id) // id da conta
    {
        $account = Account::findOrFail($id);

        if (Gate::denies('view-account-movements', $account)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        if (!is_null($account->deleted_at)) {
            return redirect()->route('movements.index', $account->id)->with('errorMsg', "You can't add a new movement to a closed account");
        }

        $pagetitle = "Add new movement";
        $movementsCategories = MovementCategories::all();

        $movement = new Movement();
        $document = new Document();
        return view('movements.createMovement', compact('account', 'movement', 'movementsCategories', 'pagetitle', 'document'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        if (Gate::denies('view-account-movements', $account)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }


        if ($request->has('cancel')) {
            return redirect()->route('movement.index', $account->id);
        }

        $validatedData = $this->validateData($request, $account->id);
 
        $movementCategories = MovementCategories::all();

        foreach ($movementCategories as $m) {
            if ($m->id == $validatedData['movement_category_id']) {
                $type = $m->type;
            }
        }

        $previous = $account->movements()
            ->where('date', '<=', $validatedData['date'])
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(1)
            ->first();



        if (is_null($previous)) {
            $start_balance = $account->start_balance;
        } else {
            $start_balance = $previous->end_balance;
        }

        if ($type == 'expense') {
            $endBalance = $start_balance - $validatedData['value'];
        } else {
            $endBalance = $start_balance + $validatedData['value'];
        }

        try {
            DB::beginTransaction();
            if ($request->hasFile('document_file')) {
                $document = Document::create([
                    'type' => $request->file('document_file')->getClientOriginalExtension(),
                    'original_name' => $request->file('document_file')->getClientOriginalName(),
                    'description' => $validatedData['document_description'],
                ]);

                $movement = Movement::create([
                    'movement_category_id' => $validatedData['movement_category_id'],
                    'account_id' => $account->id,
                    'date' => $validatedData['date'],
                    'value' => $validatedData['value'],
                    'description' => $validatedData['description'],
                    'start_balance' => $start_balance,
                    'end_balance' => $endBalance,
                    'type' => $type,
                    'document_id' => $document->id,
                ]);

                $path = Storage::putFileAs('documents/'.$account->id.'/', $request->file('document_file'), $movement->id.'.'.$document->type);
            } else {
                $movement = Movement::create([
                    'movement_category_id' => $validatedData['movement_category_id'],
                    'account_id' => $account->id,
                    'date' => $validatedData['date'],
                    'value' => $validatedData['value'],
                    'description' => $validatedData['description'],
                    'start_balance' => $start_balance,
                    'end_balance' => $endBalance,
                    'type' => $type,
                    'document_id' => null,
                ]);
            }

            $success = true;
        } catch (\Exception $ex) {
            if ($request->hasFile('document_file')) {
                Storage::delete($path);
            }
            DB::rollback();
            $success = false;
        }

        if ($success) {
            DB::commit();

            $movs = $account->movements()->where('id', '!=', $movement->id)->where('date', '>=', $movement->date)->orderBy('date')->orderBy('created_at')->get();
            $movementsToUpdate = array();
            foreach ($movs as $m) {
                if ($m->date > $movement->date) {
                    $movementsToUpdate[] = $m;
                } elseif ($m->date == $movement->date && $m->created_at > $movement->created_at) {
                    $movementsToUpdate[] = $m;
                }
            }

            if (count($movementsToUpdate) == 0) {
                $account->last_movement_date = $movement->date;
            } else {
                $this->updateMovements($movementsToUpdate, $movement->end_balance);
            }

            if ($type == 'expense') {
                $account->current_balance = $account->current_balance - $movement->value;
            } else {
                $account->current_balance = $account->current_balance + $movement->value;
            }

            $account->save();
            return redirect()->route('movement.index', $account->id)->with('successMsg', "Movement add successufly");
        } else {
            return redirect()->route('movement.index', $account->id)->with('errorMsg', "Couldn't add movement!");
        }
    }

    public function updateMovements($movements, $valuePropagate) {
        $start_balance_update = $valuePropagate;
                    
        foreach ($movements as $m) {
            if ($m->type == 'expense') {
                $m->start_balance = $start_balance_update;
                $m->end_balance = $m->start_balance - $m->value;
            } else {
                $m->start_balance = $start_balance_update;
                $m->end_balance = $m->start_balance + $m->value;
            }
            $m->save();
            $start_balance_update = $m->end_balance;
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $movement = Movement::findOrFail($id);
        $account = $movement->account;
        $movementsCategories = MovementCategories::all();

        if (Gate::denies('view-account-movements', $account)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        $document = Document::find($movement->document_id);

        if (is_null($document)) {
            $document = new Document();
        }

        $pagetitle = "Edit Movement";
        return view('movements.editMovement', compact('movement', 'movementsCategories', 'account', 'pagetitle', 'document'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $movement = Movement::findOrFail($id);
        $account = $movement->account;
        $accountOwner = $account->user;

        $original_mov_value = $movement->value;
        $original_type = $movement->type;
        $original_date = $movement->date;

        if (Gate::denies('view-account-movements', $account)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        if ($request->has('cancel')) {
            return redirect()->route('movement.index', $account->id);
        }

        $validatedData = $this->validateData($request, $account->id);

        $movementCategories = MovementCategories::all();
        foreach ($movementCategories as $m) {
            if ($m->id == $validatedData['movement_category_id']) {
                $type = $m->type;
            }
        }


        if ($type == 'expense') {
            $endBalance = $movement->start_balance - $validatedData['value'];
        } else {
            $endBalance = $movement->start_balance + $validatedData['value'];
        }
      
        
        DB::beginTransaction();
        try {
            // não tinha nenhum documento associado e não quer associar nenhum
            DB::table('movements')->where('id', '=', $movement->id)->update([
                    'movement_category_id' => $validatedData['movement_category_id'],
                    'date' => $validatedData['date'],
                    'value' =>$validatedData['value'],
                    'description' => $validatedData['description'],
                    'type' => $type,
                    'end_balance' => $endBalance,
                ]);
           
            if (is_null($movement->document_id) && $request->hasFile('document_file')) {
                // não tinha um documento associado mas agora quer associar
                $document = Document::create([
                    'type' => $request->file('document_file')->getClientOriginalExtension(),
                    'original_name' => $request->file('document_file')->getClientOriginalName(),
                    'description' => $validatedData['document_description'],
                ]);

                DB::table('movements')->where('id', '=', $movement->id)->update([
                    'document_id' => $document->id,
                ]);

                if (!File::exists('documents/'.$accountOwner->id)) {
                    // verifica se existe o diretorio
                    Storage::makeDirectory('documents/'.$accountOwner->id);
                }

                $path = Storage::putFileAs('documents/'.$account->id, $request->file('document_file'), $movement->id.'.'.$document->type);
            } elseif (!is_null($movement->document_id) && $request->hasFile('document_file')) {
                // tinha associado um documento e agora quer associar outro
                $document = Document::findOrFail($movement->document_id);

                $file = 'documents/'.$account->id.'/'.$movement->id.'.'.$document->type;

                Storage::delete($file);

                DB::table('documents')->where('id', '=', $document->id)->update([
                    'type' => $request->file('document_file')->getClientOriginalExtension(),
                    'original_name' => $request->file('document_file')->getClientOriginalName(),
                    'description' => $request['document_description'],
                ]);

                $document = DB::table('documents')->where('id', $document->id)->first();

                $path = Storage::putFileAs('documents/'.$account->id, $request->file('document_file'), $movement->id.'.'.$document->type);
            }

            $success = true;
        } catch (\Exception $ex) {
            if (!is_null($movement->document_id) && !$request->hasFile('document_file')) {
                Storage::delete($path);
            }
            $success = false;
        }

        if ($success) {
            DB::commit();
            $movement = Movement::findOrFail($id);


            $movs = $account->movements()->where('id', '!=', $movement->id)->where('date', '>=', $movement->date)->orderBy('date')->orderBy('created_at')->get();
            $movementsToUpdate = array();
            foreach ($movs as $m) {
                if ($m->date > $movement->date) {
                    $movementsToUpdate[] = $m;
                } elseif ($m->date == $movement->date && $m->created_at > $movement->created_at) {
                    $movementsToUpdate[] = $m;
                }
            }

            if ($original_date != $movement->date && count($movementsToUpdate) == 0) {
                $account->last_movement_date = $movement->date;
            }

            if ($original_mov_value != $movement->value || $original_type != $movement->type) {
                if (count($movementsToUpdate) == 0) {
                    // não existe nenhum com data superior
                    $account->current_balance = $movement->end_balance;
                } else {
                    $this->updateMovements($movementsToUpdate, $movement->end_balance);

                    $last = end($movementsToUpdate);
                    $account->current_balance = $last->end_balance;
                }

                $account->save();
            }

            return redirect()->route('movement.index', $account->id)->with('successMsg', "Movement edited successufly");
        } else {
            DB::rollBack();
            return redirect()->route('movement.index', $account->id)->with('errorMsg', "Couldn't edit movement!");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($movement)
    {
        $movement = Movement::findOrFail($movement);
        $account = $movement->account;

        if (Gate::denies('view-account-movements', $account)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }


        if (!is_null($movement->document_id)) {
            $document = Document::findOrFail($movement->document_id);
            $file = 'documents/'.$account->id.'/'.$movement->id.'.'.$document->type;

            Storage::delete($file);
        }

        $movementsToUpdate = $account->movements()->where('id', '!=', $movement->id)->where('date', '>=', $movement->date)->get();

        if ($movement->type == 'expense') {
            $account->current_balance = $account->current_balance + $movement->value;
            foreach ($movementsToUpdate as $m) {
                $m->start_balance = $m->start_balance + $movement->value;
                $m->end_balance = $m->end_balance + $movement->value;
                $m->save();
            }
        } else {
            $account->current_balance = $account->current_balance - $movement->value;
            foreach ($movementsToUpdate as $m) {
                $m->start_balance = $m->start_balance - $movement->value;
                $m->end_balance = $m->end_balance - $movement->value;
                $m->save();
            }
        }

        $movements = $account->movements;

        if (count($movements) == 1) {
            $account->last_movement_date = null;
            $account->current_balance = $account->start_balance;
        } elseif (count($movementsToUpdate) == 0) {
            $m = $account->movements()->where('id', '!=', $movement->id)->where('date', '<=', $movement->date)->orderBy('date', 'desc')->first();
            $account->last_movement_date = $m->date;
        }

        $account->save();
        
        Movement::destroy($movement->id);

        return redirect()->route('movement.index', $account->id)->with('successMsg', 'Movement deleted successufly');
    }

    private function validateData(Request $request, $accountId)
    {
        $validatedData = $request->validate([
            'movement_category_id' => 'required|exists:movement_categories,id',
            'date' => 'required|date',
            'value' => 'required|numeric|between:0.01,999999999.99',
            'description' => 'nullable',
            'document_file' => 'nullable|file|mimes:png,jpeg,pdf',
            'document_description' => 'nullable',
        ], [
            'movement_category_id.required' => 'Movement category must be selected',
            'date.required' => 'Date must be selected',
            'value.required' => 'Value must not be empty',
            'value.regex' => 'Value must be a positive number in the format: dd.dd',
            'document_file.mimes' => 'Document must be in one of the following formats: .png, .jpeg, .pdf',
        ]);

        if (!array_key_exists('description', $validatedData)) {
            $validatedData['description'] = null;
        }

        if (!array_key_exists('document_description', $validatedData)) {
            $validatedData['document_description'] = null;
        }

        if (!$request->hasFile('document_file') && !is_null($validatedData['document_description'])) {
            return redirect()->route('movement.create', $accountId)->withErrors(['document_file' => 'Have document description but missing document file']);
        }

        if ($request->hasFile('document_file') && ($request->file('document_file')->getClientOriginalExtension() == 'jpg' || $request->file('document_file')->getClientOriginalExtension() == 'jpe')) {
            return redirect()->route('movement.create', $account->id)->with('errorMsg', "Document can't be a .jpg or .jpe");
        }

        return $validatedData;
    }
}
