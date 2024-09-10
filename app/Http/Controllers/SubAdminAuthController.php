<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SubAdmin;
use Validator;
use Illuminate\Support\Facades\Hash;


class SubAdminAuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:sub_admins',
            'password' => 'required|confirmed|min:8',
        ]);


        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
  
        $subAdmin = new SubAdmin;
        $subAdmin->id   = \Illuminate\Support\Str::uuid(); // Generate UUID
        $subAdmin->name = request()->name;
        $subAdmin->email = request()->email;
        $subAdmin->password = bcrypt(request()->password);
        $subAdmin->save();
  
        return response()->json($subAdmin, 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('subadmin')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->guard('subadmin')->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('subadmin')->logout();
  
        return response()->json(['message' => 'Successfully logged out']);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('subadmin')->refresh());
    }
  
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->guard('subadmin')->factory()->getTTL() * 60
        ]);
    }


    public function update_profile()
    {
        $validator = Validator::make(request()->all(), [
            'id'              => 'required|exists:admins,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name'            => 'required|string|max:255',
            'status'          => 'nullable'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $subadmin = SubAdmin::where('id', request()->id)->first();
    
        // Check if a profile picture is uploaded
        if (request()->hasFile('profile_picture')) {
            // Remove the old image if it exists
            if ($subadmin->profile_picture_url && file_exists(public_path($subadmin->profile_picture_url))) {
                unlink(public_path($subadmin->profile_picture_url));
            }
    
            // Save the new image
            $imageName = time() . '.' . request()->profile_picture->extension();
            request()->profile_picture->move(public_path('images/subadmin'), $imageName);
    
            // Update the profile picture URL
            $subadmin->profile_picture_url = 'images/admin/' . $imageName;
        }
    
        // Update the name
        $subadmin->name = request()->name;
        $subadmin->save();
    
        return response()->json($subadmin, 200);
    }


    public function update_password(){
        $validator = Validator::make(request()->all(), [
            'id'               => 'required|exists:admins,id',
            'current_password' => 'required',
            'new_password'     => 'required|confirmed|min:8',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $subadmin = SubAdmin::where('id', request()->id)->first();
    
        // Check if the current password is correct
        if (Hash::check(request()->current_password, $subadmin->password)) {
            // Update to the new password
            $subadmin->password = Hash::make(request()->new_password);
            $subadmin->save();
    
            return response()->json($subadmin, 200);
        } else {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }
    }
}
