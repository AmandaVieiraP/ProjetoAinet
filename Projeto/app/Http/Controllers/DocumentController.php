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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $movement=Movement::findOrFail($id);
        $pagetitle="Upload Document";

        return view('movements.documents.uploadDocument', compact('movement', 'pagetitle'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $document=Document::findOrFail($id);

        $movement=Movement::where('document_id', $id)->first();

        if (Gate::forUser(Auth::user())->denies('download-document', $id)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }


        if ($request->has('view')) {
            return $this->showInBrowser($id);
        }

        return Storage::download('documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type, $document->original_name, []);
    }

    public function showInBrowser($id)
    {
        $document=Document::findOrFail($id);


        $movement=Movement::where('document_id', $id)->first();

        if (Gate::forUser(Auth::user())->denies('download-document', $id)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        $path = storage_path('app/documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type);
        return response()->file($path);
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

        if (Auth::id() != $account->owner_id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        if ($request->has('cancel')) {
            return redirect()->route('movement.index', ['account' => $account->id]);
        }

        $validatedData=$request->validate([
            'document_description'=>'nullable',
            'document_file'=>'required|mimes:png,jpeg,pdf'

        ], [
            'document_file.required'=>'The document must have a file associated',
            'document_file.mimes' =>'The document must be a file pdf, png, jpeg.',
        ]);

        $documentArray['type']=$request->file('document_file')->getClientOriginalExtension();
        $documentArray['original_name']=$request->file('document_file')->getClientOriginalName();
        $documentArray['description']=$request->input('document_description');

        $mimes=['.png','.jpeg','.pdf'];
        if (in_array($documentArray['type'], $mimes)) {
            return redirect()->route('movement.index', ['account' => $account->id])->withErrors(['type' => 'The type must be jpeg, png, pdf']);
        }

        if ($movement->document_id==null) {
            $document=Document::create($documentArray);
            $movement->document()->associate($document);
            $movement->save();
        } else {
            $doc_id=$movement->document_id;
            $document=Document::findOrFail($doc_id);

            Storage::delete('documents/'.$account->id.'/'.$movement->id.'.'.$document->type);

            Document::where('id', $doc_id)->update(
                array(
                 'type' => $documentArray['type'],
                 'original_name' => $documentArray['original_name'],
                 'description' => $documentArray['description'],
              )
            );
        }
        Storage::putFileAs('documents/'.$account->id.'/', $request->file('document_file'), $movement->id.'.'.$documentArray['type']);
        
        return redirect()->route('movement.index', ['account' => $account->id])->with('successMsg', 'Your document has been uploaded');
        ;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $document=Document::findOrFail($id);

        $movement=Movement::where('document_id', $id)->first();

        $account=Account::findOrFail($movement->account_id);

        if (Auth::id() != $account->owner_id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        Movement::where('document_id', $id)->update(
                array(
                 'document_id'=>null,
              )
            );

        $document->delete();

        Storage::delete('documents/'.$movement->account_id.'/'.$movement->id.'.'.$document->type);

        return redirect()->route('movement.index', ['account' => $movement->account_id])->with('successMsg', 'Your document has been deleted');
    }
}
