<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Employee;
use App\Models\File;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $user  = User::find(auth()->user()->id);
            return response()->json([
                'user' => $user,
                'token' => $user->createToken('Api Token')->accessToken
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }
    public function register(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'street' => 'required|max:255',
            'national_id' => 'required|max:255',

        ]);

        $user = User::create([
            'email' => $data['email'],
            'natioanl_id' => $data['natioanl_id'],
            'password' => bcrypt($data['password']),
        ]);

        if ($data['role']) {
            $employee = Employee::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['firstname'],
                'role' => strtolower($data['role']),
            ]);

            $user->employee()->save($employee);
        } 

        $address = Address::create([
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'street' => $data['street'],
        ]);

        $user->address()->save($address);

        $content = Storage::get('public/default-profile-picture.jpeg');
        $extension = 'jpeg';
        $name = "profile picture";

        $user->profile_picture()->save(new File([
            'name' => $name,
            'content' => base64_encode($content),
            'extension' => $extension,
        ]));

        $accessToken = $user->createToken('authToken')->accessToken;

        return response(['user' => $user, 'token' => $accessToken]);
    }

    public function profile($id)
    {

        $user = User::find($id);
        $user['profile_picture'] = $user->profile_picture;
        return response()->json(200, $user);
    }

    public function change_password(Request $request, $id)
    {
        $user = User::find($id);
        $data = $request->validate([
            'old_password' => 'required|max:255',
            'password' => 'required|confirmed',
        ]);
        if (Hash::check($data['old_password'], $user->password)) {
            $user->password = Hash::make($data['password']);
            $message = 'password changed successfuly';
        } else {
            $message = 'the old password you had entered is wrong';
        }

        return response()->json(200,$message);
    }
}
