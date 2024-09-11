<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Validator;
use Illuminate\Support\Facades\Hash;


class AdminAuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'password' => 'required|confirmed|min:8',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
  
        $admin = new Admin;
        $admin->id   = \Illuminate\Support\Str::uuid(); // Generate UUID
        $admin->name = request()->name;
        $admin->email = request()->email;
        $admin->password = bcrypt(request()->password);
        $admin->save();
  
        return response()->json($admin, 201);
    }
  
  
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
  
        if (! $token = auth()->attempt($credentials)) {
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
        return response()->json(auth()->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
  
        return response()->json(['message' => 'Successfully logged out'],200);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
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
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }


    public function update_profile($id)
    {
        $validator = Validator::make(request()->all(), [
            'name'            => 'required|string|max:255',
            'email'           => 'required|email',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $admin = Admin::where('id', $id)->first();
    
        // Check if a profile picture is uploaded
        if (request()->hasFile('profile_picture')) {
            // Remove the old image if it exists
            if ($admin->profile_picture_url && file_exists(public_path($admin->profile_picture_url))) {
                unlink(public_path($admin->profile_picture_url));
            }
    
            // Save the new image
            $imageName = time() . '.' . request()->profile_picture->extension();
            request()->profile_picture->move(public_path('images/admin'), $imageName);
    
            // Update the profile picture URL
            $admin->profile_picture_url = 'images/admin/' . $imageName;
        }
    
        // Update the name
        $admin->name  = request()->name;
        $admin->email = request()->email;
        $admin->save();
    
        return response()->json($admin, 200);
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

    $admin = Admin::where('id', request()->id)->first();

    // Check if the current password is correct
    if (Hash::check(request()->current_password, $admin->password)) {
        // Update to the new password
        $admin->password = Hash::make(request()->new_password);
        $admin->save();

        return response()->json($admin, 200);
    } else {
        return response()->json(['error' => 'Current password is incorrect'], 400);
    }
}
}