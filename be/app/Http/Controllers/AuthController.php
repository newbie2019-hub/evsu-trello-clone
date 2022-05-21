<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserAccountRequest;
use App\Http\Requests\UserAccountUpdateRequest;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\UserLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'store']]);
    }

    public function store(UserAccountRequest $request){
        $userinfo = UserInfo::create([
            'first_name' => $request->validated(['first_name']),
            'last_name' => $request->validated(['last_name']),
        ]);

        User::create([
            'user_info_id' => $userinfo->id,
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        return $this->success('Account created successfully!');
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        UserLog::create([
            'log_name' => 'Account Login',
            'event' => 'login',
            'user_id' => auth('api')->user()->id,
            'description' => 'You\'ve logged in your account'
        ]);

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = User::with(['info'])->where('id', auth()->guard('api')->user()->id)->first();
        return response()->json($user);
    }

    public function update(UserAccountUpdateRequest $request){
        if (! Hash::check($request->validated('current_password'), $request->user()->password)) {
            return $this->error('Password entered is incorrect');
        }

        $userinfo = UserInfo::where('id', auth()->user()->id)->first();
        $userinfo->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'birthday' => $request->birthday,
            'contact_number' => $request->contact_number,
            'gender' => $request->gender,
            'address' => $request->address,
        ]);

        UserLog::create([
            'log_name' => 'Account Information Updated',
            'event' => 'update',
            'user_id' => auth('api')->user()->id,
            'description' => 'You\'ve updated your account information.'
        ]);

        return $this->success('Account updated successfully!');
    }

    public function updatePassword(Request $request){
        if (! Hash::check($request->current_password, $request->user()->password)) {
            return $this->error('Password entered is incorrect');
        }

        $user = User::where('id', auth()->user()->id)->first();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);
        
        return $this->success('Password updated successfully!');
    }

    public function logout()
    {
        UserLog::create([
            'log_name' => 'Account Logout',
            'event' => 'logout',
            'user_id' => auth('api')->user()->id,
            'description' => 'You\'ve logged out your account successfully'
        ]);

        auth()->logout();
        return $this->success('Logout successful!');
    }

    public function profileImg(Request $request){
        if($request->img){
            $fileName = time().'.'.$request->img->extension();
            $request->img->move(public_path('images'), $fileName);

            $userinfo = UserInfo::where('id', auth()->user()->id)->first();

            if($userinfo->image) {
                $this->deleteImage($fileName);
            }

            $userinfo->update([
                'image' => $fileName
            ]);

            UserLog::create([
                'log_name' => 'Profile Image Updated',
                'event' => 'update',
                'user_id' => auth('api')->user()->id,
                'description' => 'You\'ve updated your profile picture.'
            ]);

            return $this->success('Profile image updated successfully!');
        }
    }

    public function deleteProfileImg(){
        $userinfo = UserInfo::where('id', auth()->user()->id)->first();

        if($userinfo->image) {
            $this->deleteImage($userinfo->image);

            $userinfo->update([
                'image' => null
            ]);

            UserLog::create([
                'log_name' => 'Profile Image Removed',
                'event' => 'update',
                'user_id' => auth('api')->user()->id,
                'description' => 'You\'ve removed your profile picture.'
            ]);
        }

        return $this->success('Profile image has been removed successfully!');
    }

    public function deleteImage($img){
        $file = File::delete(public_path("images/".$img));
        return $file;
    }

    protected function respondWithToken($token)
    {
        $user = UserInfo::where('id', auth('api')->user()->id)->first();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user_info' => $user,
            'user' => auth('api')->user(),
        ]);
    }

}
