<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RedirectResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\UploadedFile;
use Validator;
use Illuminate\Support\Facades\Gate;
use App\AssociateMember;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::findOrFail($id);

        if (Gate::denies('view-accounts', $id)) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        $pagetitle="Dashboard";

        $accounts=$user->allAccounts;

        $summary=[];

        foreach ($accounts as $acc) {
            $summary[]=$acc->current_balance;
        }

        $total=0;
        
        foreach ($summary as $s) {
            $total+=$s;
        }

        $percentage=[];

        foreach ($accounts as $a) {
            if ($a->current_balance!=0) {
                $percentage[]=number_format(($a->current_balance/$total * 100), 2);
            } else {
                $percentage[]=0;
            }
        }

        $total=number_format($total, 2);

        return view('accounts.dashboard', compact('pagetitle', 'user', 'accounts', 'summary', 'total', 'percentage', 'id'));
    }


    public function listAllUsersToAdmin(Request $request)
    {
        $pagetitle = "List of Users";

        $users=UserController::filter($request); 

        return view('users.listUsersToAdmin', compact('users', 'pagetitle'));
    }

    private static function filter(Request $request)
    {
        //se nada estiver prenchido
        if (!$request->filled('name') && !$request->filled('type') && !$request->filled('status')) {
            return User::paginate(10);
        }

        //se type ou status invalido
        if (($request->filled('type') && $request->query('type')!='admin' && $request->query('type')!='normal') || ($request->filled('status') && $request->query('status')!='blocked' && $request->query('status')!='unblocked')) {
            return User::paginate(10);
        }

        //só type normal
        if (!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && !$request->filled('status')) {
            return User::where('admin', '=', false)->paginate(10);    
        }
        //só type admin
        if (!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && !$request->filled('status')) {
            return User::where('admin', '=', true)->paginate(10);
        }

        //só status blocked
        if (!$request->filled('name')&& !$request->filled('type') && $request->filled('status') && $request->query('status')=='blocked') {
            return User::where('blocked', '=', true)->paginate(10);
        }

        //só status unblocked
        if (!$request->filled('name')&& !$request->filled('type') && $request->filled('status') && $request->query('status')=='unblocked') {
            return User::where('blocked', '=', false)->paginate(10);
        }

        //type+status
        if (!$request->filled('name')&& $request->filled('type') && $request->query('type')=='normal' && $request->filled('status')) {
            //'normal'+'blocked'
            if ($request->query('status')=='blocked') {
                return User::where('admin', '=', false)->where('blocked', '=', true)->paginate(10);
            }
            //'normal'+'unblocked'
            else {
                return User::where('admin', '=', false)->where('blocked', '=', false)->paginate(10);
            }
        }

        if (!$request->filled('name')&& $request->filled('type') && $request->query('type')=='admin' && $request->filled('status')) {
            //'admin'+'blocked'
            if ($request->query('status')=='blocked') {
                return User::where('admin', '=', true)->where('blocked', '=', true)->paginate(10);
            }
            //'admin'+'unblocked'
            else {
                return User::where('admin', '=', true)->where('blocked', '=', false)->paginate(10);
            }
        }

        //só nome
        if ($request->filled('name') && !$request->filled('type') && !$request->filled('status')) {
            return User::where('name', 'like', '%'.$request->query('name').'%')->paginate(10);
        }

        //nome+type
        if ($request->filled('name') && $request->filled('type') && !$request->filled('status')) {
            //'normal'
            if ($request->query('type')=='normal') {
                return User::where('name', 'like', '%'.$request->query('name').'%')->where('admin', '=', false)->paginate(10);
            } else {
                //'admin'
                return User::where('name', 'like', '%'.$request->query('name').'%')->where('admin', '=', true)->paginate(10);
            }
        }
        //nome+status
        if ($request->filled('name') && !$request->filled('type') && $request->filled('status')) {
            //blocked
            if ($request->query('status')=='blocked') {
                return User::where('name', 'like', '%'.$request->query('name').'%')->where('blocked', '=', true)->paginate(10);
            } else {
                //unblocked
                return User::where('name', 'like', '%'.$request->query('name').'%')->where('blocked', '=', false)->paginate(10);
            }
        }
        //nome+type+status
        if ($request->filled('name') && $request->filled('type') && $request->filled('status')) {
            //'nome'+'normal'+'blocked'
            if ($request->query('type')=='normal') {
                if ($request->query('status')=='blocked') {
                    return User::where('name', 'like', '%'.$request->query('name').'%')->where('admin', '=', false)->where('blocked', '=', true)->paginate(10);
                }
                //'nome'+'normal'+'unblocked'
                else {
                    return User::where('name', 'like', '%'.$request->query('name').'%')->where('admin', '=', false)->where('blocked', '=', false)->paginate(10);
                }
            }
            //'nome'+'admin'+'blocked'
            else {
                if ($request->query('status')=='blocked') {
                    var_dump(5.111);
                    return User::where('name', 'like', '%'.$request->query('name').'%')->where('admin', '=', true)->where('blocked', '=', true)->paginate(10);
                }
                //'nome'+'admin'+'unblocked'
                else {
                    return User::where('name', 'like', '%'.$request->query('name').'%')->where('admin', '=', true)->where('blocked', '=', false)->paginate(10);
                }
            }
        }
    }

    public function blockUser(Request $request, $user)
    {
        $userToBlock = User::findOrFail($user);
        if (Auth::id() == $userToBlock->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToBlock->blocked == 1) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already blocked");
        }
        $userToBlock->blocked = 1;
        $userToBlock->save();

        return redirect()->route('list.of.all.users')->with('successMsg', "User was blocked succesfully");
    }

    public function unblockUser(Request $request, $user)
    {
        $userToUnblock = User::findOrFail($user);
        
        if (Auth::id() == $userToUnblock->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }
        if ($userToUnblock->blocked == 0) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already not blocked");
        };
        $userToUnblock->blocked = 0;
        $userToUnblock->save();


        return redirect()->route('list.of.all.users')->with('successMsg', "User was unblocked succesfully");
    }

    public function promoteUser(Request $request, $user)
    {
        $userToPromote = User::findOrFail($user);

        if (Auth::id() == $userToPromote->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        if ($userToPromote->admin == 1) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already admin");
        };

        $userToPromote->admin = 1;
        $userToPromote->save();

        return redirect()->route('list.of.all.users')->with('successMsg', "User was promoted succesfully");
    }

    public function demoteUser(Request $request, $user)
    {
        $userToDemote = User::findOrFail($user);

        if (Auth::id() == $userToDemote->id) {
            $pagetitle = "Unauthorized";
            return Response::make(view('errors.403', compact('pagetitle')), 403);
        }

        if ($userToDemote->admin == 0) {
            return redirect()->route('list.of.all.users')->with('errorMsg', "User is already not admin");
        };

        $userToDemote->admin = 0;

        $userToDemote->save();
        return redirect()->route('list.of.all.users')->with('successMsg', "User was demoted succesfully");
    }

    public function showChangePasswordForm()
    {
        $pagetitle = "Change My Password";
        return view('users.changePassword', compact('pagetitle'));
    }

    public function changePassword(Request $request)
    {
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }

        $validatedData=$request->validate([
            'old_password'=>'required',
            'password'=>'required|confirmed|min:3|different:old_password',
            'password_confirmation'=>'required|same:password',
        ], [
        'old_password.required' => 'You must enter your current password',
        'password.required' => 'You must enter a new password',
        'password.different' => 'The new password must be different from the current password',
        'password.min' => 'The new password must have at least 3 characters',
        'password_confirmation.required' => 'You must enter the confirmation password',
        'password_confirmation.same' => 'The confirmation password must be equal to new password',
        ]);

        if (!(Hash::check($request->input('old_password'), Auth::user()->password))) {
            return redirect()->route('me.password')->withErrors(['old_password' => 'Please enter the correct current password']);
        }
            
        $user_id=Auth::id();
        $user=User::findOrFail($user_id);
        $user->password=Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('home')->with('success', 'Your password has been updated');
    }

    public function showEditMyProfileForm()
    {
        $pagetitle = "Update My Profile";
        return view('users.editMyProfile', compact('pagetitle'));
    }

    public function updateMyProfile(Request $request)
    {
        if ($request->has('cancel')) {
            return redirect()->route('home');
        }

        $validatedData=$request->validate([
            'name'=>'required|regex:/(^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÊÍÏÓÒÖÚÇÑ ]+$)+/',
            'phone'=>'nullable|regex:/(^[0-9\+ ]+$)+/',
            'email' => 'email|required|'.Rule::unique('users')->ignore(Auth::id()),
            'profile_photo'=>'nullable|image|mimes:png,jpeg,jpg',
        ], [
        'name.required' => 'Name must not be empty',
        'name.regex' => 'Name must only have letters and spaces',
        'email.required' => 'The email must not be empty',
        'email.unique' => 'The email has already in use',
        'email.email' => 'Please introduce an email in correct format',
        'phone.regex' => 'Please enter only numbers in phone, spaces and symbol + on the phone',
        'profile_photo.mimes' =>'The profile photo must be a file png, jpg, jpeg.',
        ]);

        $user = $request->user();


        $user->fill($validatedData);

        if (!$request->has('phone')) {
            $user->phone=null;
        }

        if ($request->hasFile('profile_photo')) {
            $image=$request->file('profile_photo');
            $path = basename($image->store('profiles', 'public'));
            $user->profile_photo=basename($path);
        }

        $user->save();

        return redirect()->route('home')->with('success', 'Your profile has been updated');
    }

    public function getProfiles(Request $request)
    {
        $pagetitle = "List of Users";

        $users = $this->filterProfilesByName($request);
        
        $associateds = Auth::user()->associateds;
        $associated_of = Auth::user()->associated_of;

        return view('users.profiles', compact('users', 'associateds', 'associated_of', 'pagetitle'));
    }

    public function filterProfilesByName(Request $request)
    {
        $name = $request->query('name');
        if (empty($name)) {
            return User::paginate(10);
        } else {
            return User::where('name', 'like', '%'.$name.'%')->paginate(10);
        }
    }

    public function getAssociates()
    {
        $pagetitle = "List of Associated users";
        $users = Auth::user()->associateds()->paginate(6);
        return view('users.listofAssociateMembers', compact('users', 'pagetitle'));
    }

    public function getAssociateOfMe()
    {
        $pagetitle = "Users that I'm associated";
        $users = Auth::user()->associated_of()->paginate(6);
        return view('users.listofAssociateMembersOf', compact('users', 'pagetitle'));
    }
    public function createAssociate(Request $request)
    {
        if ($request->has('cancel')) {
            return redirect()->route('users.associates');
        }

        $associateds=Auth::user()->associateds;
        $associatedsIds=[];
        foreach ($associateds as $a) {
            $associatedsIds[]=$a->id;
        }

        $validatedData=$request->validate([
            'associated_user' => 'required|exists:users,id',
        ], [
            'associated_user.exists'=>'You must pick a valid user',
        ]);

        if ($request->input('associated_user')==Auth::id()) {
            return redirect()->route('users.associates')->withErrors(['associated_user'=>'Can not associate to yourself']);
        }

        if (in_array($request->input('associated_user'), $associatedsIds)) {
            return redirect()->route('users.associates')->withErrors(['associated_user'=>'This user is already associated to you']);
        }
        
        AssociateMember::create(['main_user_id' => Auth::id(), 'associated_user_id'=>$request->input('associated_user'),]);
        
        return redirect()->route('users.associates')->with('successMsg', 'User associated with success!');
    }

    public function destroyAssociate($id)
    {
        $pagetitle= '404';
        $user= User::findOrFail($id);
      
        if (Gate::allows('admin', Auth::id())) {
            DB::table('associate_members')->where('associated_user_id', $user->id)->delete();
        } else {
            $association = DB::table('associate_members')->where('associated_user_id', $id)->get();
            if (!isset($association['0'])) {
                $pagetitle= '404';
                return Response::make(view('errors.notfound', compact('pagetitle')), 404);
            }
            DB::table('associate_members')->where('associated_user_id', $id)->delete();
        }
        return redirect()->route('users.associates')->with('success', 'Associated deleted');
    }

    public function getCreateAssociate()
    {
        $pagetitle="Add New Associated";
        $users = User::all();
        $associateds=Auth::user()->associateds;
        $associatedsIds=[];
        foreach ($associateds as $a) {
            $associatedsIds[]=$a->id;
        }
        return view('users.createAssociate', compact('users', 'pagetitle', 'associatedsIds'));
    }
}
