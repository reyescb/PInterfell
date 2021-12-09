<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       // $users = User::all();
       // $users = User::with('profile')->get();


        $users = DB::table('users')
            ->Join('profiles', 'users.id', '=', 'profiles.user_id')
            ->select('users.*', 'profiles.ima_profile')
            ->where('users.deleted_at', '=', null)
            ->get();
        return response()->json(['success'=>true,'message'=>'list users','data'=>$users],200);
    }

    public function createdAt(Request $request)
    {
        $fromDate = $request->post('fromDate');
       // $users = User::whereDate('created_at',$request->get('date'))->select('users.*', 'profiles.ima_profile')->get();
        $users = DB::table('users')
        ->Join('profiles', 'users.id', '=', 'profiles.user_id')
        ->select('users.*', 'profiles.ima_profile')
        ->whereDate('users.created_at',$fromDate)
        ->get();
        return response()->json(['success'=>true,'message'=>'list users createAt','data'=>$users],200);
    }

    public function betweenCreatedAt(Request $request)
    {
        $fromDate = $request->post('fromDate');
        $toDate = $request->post('toDate');

        //$users = User::whereDate('created_at','>=',$fromDate)->whereDate('created_at','<=',$toDate)->get();
        $users = DB::table('users')
        ->Join('profiles', 'users.id', '=', 'profiles.user_id')
        ->select('users.*', 'profiles.ima_profile')
        ->whereDate('users.created_at','>=',$fromDate)
        ->whereDate('users.created_at','<=',$toDate)
        ->get();
        return response()->json(['success'=>true,'message'=>'list users createAt '.$request->get('date'),'data'=>$users],200);
    }

    public function all()
    {
        $users = User::withTrashed()->get();
        return response()->json(['success'=>true,'message'=>'list users','data'=>$users],200);
    }
    public function buscar($id)
    {
        $users = DB::table('users')
            ->Join('profiles', 'users.id', '=', 'profiles.user_id')
            ->where('users.id', '=', $id)
            ->select('users.*', 'profiles.ima_profile')
            ->get();
        return response()->json(['success'=>true,'message'=>'list users','data'=>$users],200);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $store = [

            'first_name' => $request->post('first_name'),

            'last_name' => $request->post('last_name'),

            'description' => $request->post('description'),

        ];
        $storeProfile = [

            'ima_profile' => $request->post('ima_profile'),

        ];
        $user = User::create($store);
        $perfil = new Profile;
        $perfil->ima_profile = $request->post('ima_profile');
        $user->profile()->save($perfil);

        return response()->json(['success'=>true,'message'=>'Added User','data'=>$user], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $user=User::find($id);
        $user = DB::table('users')
        ->Join('profiles', 'users.id', '=', 'profiles.user_id')
        ->where('users.id', '=', $id)
        ->select('users.*', 'profiles.ima_profile')->first();

		// Si no existe ese fabricante devolvemos un error.
		if (!$user)
		{
            return response()->json(['success'=>false,'message'=>'User not found'], 404);
		}
        return response()->json(['success'=>true,'message'=>'User Information ','data'=>$user], 200);
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
        $user = User::find($id);
        if (!$user)
		{
            return response()->json(['success'=>false,'message'=>'User not found'], 404);
		}
        $user = User::find($id)->update($request->all());
        if (!$user)
		{
            return response()->json(['success'=>false,'message'=>'User not update'], 500);
		}
        $user = User::find($id);
        $user->profile()->update(["ima_profile" => $request->ima_profile]);
        return response()->json(['success'=>true,'message'=>'update user','data'=>$user],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['success'=>true,'message'=>'User Deleted','deleted_at' => $user->deleted_at], 200);
    }
}
