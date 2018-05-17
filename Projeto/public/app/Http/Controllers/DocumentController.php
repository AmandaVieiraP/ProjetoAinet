<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Movement;
use App\Account;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $movement=Movement::findOrFail($id);
        $account=Account::findOrFail($movement->account_id);

        if (Auth::user()->id != $account->owner_id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        if($request->has('cancel'))
            return redirect()->route('movements.index',['account' => $account->id]);

        $validatedData=$request->validate([
            'document_description'=>'nullable',
            'document_file'=>'required|mimes:png,jpeg,pdf'

        ], [ 
            'document_file.required'=>'The document must have a file associated',
            'document_file.mimes' =>'The document must be a file pdf, png, jpeg.',
        ]);

        //Depois de validar o documento criar o documento
        $documentArray['type']=$request->file('document_file')->getClientOriginalExtension();
        $documentArray['original_name']=$request->file('document_file')->getClientOriginalName();
        $documentArray['description']=$request->input('document_description');

        $mimes=['.png','.jpeg','.pdf'];
        if(in_array($documentArray['type'],$mimes)){
            return redirect()->route('movements.index',['account' => $account->id])->withErrors(['type' => 'The type must be jpeg, png, pdf']);
        }

        if($movement->document_id==null){
            $document=Document::create($documentArray);
        }
        else{
            $doc_id=$movement->document_id;
            //procura o movimento
            $document=Document::findOrFail($doc_id);

            $movement->document()->dissociate();
            $movement->save();
            Document::destroy($doc_id);

            $documentArray['id']=$doc_id;
            $document->fill($documentArray);
            $document->save();

            //Document::create($documentArray);
        }
        
        //armazenar o documento na Strorage
        Storage::putFileAs('documents/'.$account->id.'/', $request->file('document_file'),$movement->id.'.'.$document->type);

        //associar o documento ao movimento
        $movement->document()->associate($document);
        $movement->save();
        
        return redirect()->route('movements.index',['account' => $account->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function getDoc($id){

    } 

    public function showUploadForm($id){
        $movement=Movement::findOrFail($id);
        $pagetitle="Upload Document";

        return view('users.uploadDocument', compact('movement','pagetitle'));
    }
}
