<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Profile;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::with('user')->get();
        return response()->json(['success'=>true,'message'=>'list profiles','payload'=>$profiles],200);
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
        $profile = Profile::find($id)->update($request->all());
        if (!$profile)
		{
            return response()->json(['success'=>false,'message'=>'Profile not update'], 500);
		}
        $profile = Profile::find($id);
        return response()->json(['success'=>true,'message'=>'update profile','payload'=>$profile],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $profile = Profile::findOrFail($id);

        $profile->delete();

        return response()->json(['success'=>true,'message'=>'Profile Deleted','id' => $profile->id], 200);
    }
}
