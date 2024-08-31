<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Validator;
use Illuminate\Support\Facades\Hash;


class MemberAuthController extends Controller
{
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register() {
        $validator = Validator::make(request()->all(), [
            'first_name'  => 'required',
            'last_name'   => 'required',
            'email'       => 'required|email|unique:members',
            'password'    => 'required|confirmed|min:8',
            'phone_number'=> 'required|unique:members',
            'city'        => 'required',
            'address'     => 'nullable',
            'code_postal' => 'nullable',
            'note'        => 'nullable',
        ]);

        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $member = new Member;
        $member->id            = \Illuminate\Support\Str::uuid(); // Generate UUID
        $member->first_name    = request()->first_name;
        $member->last_name     = request()->last_name;
        $member->email         = request()->email;
        $member->password      = bcrypt(request()->password);
        $member->phone_number  = request()->phone_number;
        $member->city          = request()->city;
        $member->save();

        return response()->json($member, 201);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->guard('member')->attempt($credentials)) {
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
        return response()->json(auth()->guard('member')->user());
    }
  
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->guard('member')->logout();
  
        return response()->json(['message' => 'Successfully logged out']);
    }
  
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->guard('member')->refresh());
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
            'expires_in' => auth()->guard('member')->factory()->getTTL() * 60
        ]);
    }


    public function update_profile()
    {
        $validator = Validator::make(request()->all(), [
            'id'              => 'required|exists:admins,id',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'first_name'      => 'required|string|max:255',
            'last_name'       => 'required|string|max:255',
            'phone_number'    => 'required|string|max:255',
            'address'         => 'required|string|max:255',
            'city'            => 'required|string|max:255',
            'code_postal'     => 'required|string|max:255',
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        $member = Member::where('id', request()->id)->first();
    
        // Check if a profile picture is uploaded
        if (request()->hasFile('profile_picture')) {
            // Remove the old image if it exists
            if ($member->profile_picture_url && file_exists(public_path($member->profile_picture_url))) {
                unlink(public_path($member->profile_picture_url));
            }
    
            // Save the new image
            $imageName = time() . '.' . request()->profile_picture->extension();
            request()->profile_picture->move(public_path('images/member'), $imageName);
    
            // Update the profile picture URL
            $member->profile_picture_url = 'images/member/' . $imageName;
        }
    
        // Update the name
        $member->first_name   = request()->first_name;
        $member->last_name    = request()->last_name;
        $member->address      = request()->address;
        $member->city         = request()->city;
        $member->phone_number = request()->phone_number;
        $member->code_postal  = request()->code_postal;
        $member->save();
    
        return response()->json($member, 200);
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
    
        $member = Member::where('id', request()->id)->first();
    
        // Check if the current password is correct
        if (Hash::check(request()->current_password, $member->password)) {
            // Update to the new password
            $member->password = Hash::make(request()->new_password);
            $member->save();
    
            return response()->json($member, 200);
        } else {
            return response()->json(['error' => 'Current password is incorrect'], 400);
        }
    }
}