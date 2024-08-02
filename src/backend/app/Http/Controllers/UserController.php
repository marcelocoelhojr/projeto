<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Get User Info
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo()
    {
        try {
            $user = Auth::user();

            return response()->json([
                'status' => true,
                'message' => 'User info retrieved successfully',
                'data' => $user
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }    
    /**
    * Update User Info
    * @param Request $request
    * @return \Illuminate\Http\JsonResponse
    */
   public function updateUserInfo(Request $request)
   {
       try {
           $user = Auth::user();

           $validateUser = Validator::make($request->all(), 
           [
               'name' => 'sometimes|required',
               'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
               'password' => 'sometimes|required'
           ]);

           if ($validateUser->fails()) {
               return response()->json([
                   'status' => false,
                   'message' => 'Validation error',
                   'errors' => $validateUser->errors()
               ], 401);
           }

           if ($request->has('name')) {
               $user->name = $request->name;
           }

           if ($request->has('email')) {
               $user->email = $request->email;
           }

           if ($request->has('password')) {
               $user->password = Hash::make($request->password);
           }

           $user->save();

           return response()->json([
               'status' => true,
               'message' => 'User info updated successfully',
               'data' => $user
           ], 200);

       } catch (\Throwable $th) {
           return response()->json([
               'status' => false,
               'message' => $th->getMessage()
           ], 500);
       }
   }
   public function getAllUsers()
   {
       $users = User::all();
       return response()->json([
           'status' => true,
           'data' => $users
       ], 200);
   }

   public function getUserById($id)
   {
       $user = User::findOrFail($id);
       return response()->json([
           'status' => true,
           'data' => $user
       ], 200);
   }

   public function updateUserById(Request $request, $id)
   {
       $user = User::findOrFail($id);
       $user->update($request->only('name', 'email', 'password'));
       return response()->json([
           'status' => true,
           'message' => 'User Updated Successfully',
       ], 200);
   }
}

