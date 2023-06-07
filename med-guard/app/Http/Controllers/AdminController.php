<?php

namespace App\Http\Controllers;

use App\Console\Commands\WebSocketServer;
use App\Mail\Emails;
use App\Models\Address;
use App\Models\ContactMessage;
use App\Models\ContactRespond;
use App\Models\Employee;
use App\Models\File;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{

    public function users($id = null)
    {
        if ($id) {
            $user = User::with('profile_picture', 'address')->find($id);
            return response()->json(200, $user);
        } else {
            $users = User::with('profile_picture', 'address')->all();
            return response()->json(200, $users);
        }
    }

    public function create_user(Request $request)
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
                'lastname' => $data['lastname'],
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

        return response()->json(200);
    }

    public function delete_user($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(200);
    }

    public function update_user(Request $request, $id)
    {
        $user = User::find($id);
        $data = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'role' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'country' => 'required|max:255',
            'state' => 'required|max:255',
            'city' => 'required|max:255',
            'street' => 'required|max:255',
            'national_id' => 'required|max:255',

        ]);

        $user->update(['email' => $data['email'], 'national_id' => $data['national_id']]);

        if ($user->employee) {
            $user->employee()->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'role' => strtolower($data['role']),
            ]);
        }

        $user->address()->update([
            'country' => $data['country'],
            'state' => $data['state'],
            'city' => $data['city'],
            'street' => $data['street'],
        ]);

        return response()->json(200);
    }


    public function messages($id = null)
    {
        if ($id) {
            $message = ContactMessage::find($id);
            return response()->json(200, $message);
        } else {
            $messages = ContactMessage::all();
            return response()->json(200, $messages);
        }
    }

    public function create_message(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|max:255',
            'phone_number' => 'required',
            'message' => 'required',

        ]);

        ContactMessage::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'message' => $data['message'],
        ]);



        return response()->json(200);
    }

    public function respond(Request $request, $id)
    {
        $data = $request->validate([
            'message' => 'required',

        ]);


        $message = ContactMessage::find($id);
        $user = User::find('user_id');
        $respond = ContactRespond::create([
            'message' => $data['message'],
        ]);
        $message->respond($respond);
        $user->respond()->save($respond);

        $mail_data = [
            'email' => $message->email,
            'firstname' => $message->firstname,
            'lastname' => $message->lastname,
            'message' => $data['message'],
        ];

        dispatch(new Emails($mail_data));
    }

    public function notification_create(Request $request)
    {
        WebSocketServer::broadcastToUser($request->userId, $$request->message);
    }

    public function posts($id = null)
    {
        if ($id) {
            $post = Post::with('images')->find($id);
            return response()->json(200, $post);
        } else {
            $posts = Post::with('images')->all();
            return response()->json(200, $posts);
        }
    }

    public function create_post(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'summary' => 'required',
            'details' => 'required',
            'uploaded_images' => 'required',
        ]);


        $files = $request->file('uploaded_images');

        $post  = Post::create([
            'title' => $data['titel'],
            'summary' => $data['summary'],
            'details' => $data['details'],
        ]);

        foreach ($files as $file) {
            # code...
            $name = $file->getClientOriginalName(); // Get the original name of the file
            $content = file_get_contents($file->getRealPath()); // Get the content of the file
            $extension = $file->getClientOriginalExtension(); // Get the extension of the file

            $post->images()->save(new Image([
                'name' => $name,
                'content' => base64_encode($content),
                'extension' => $extension,
            ]));
        }



        return response()->json(200);
    }

    public function delete_post($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json(200);
    }

    public function update_post(Request $request, $id)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'summary' => 'required',
            'details' => 'required',
            'uploaded_images' => 'required',
        ]);


        $files = $request->file('uploaded_images');

        $post  = Post::find($id)->update([
            'title' => $data['title'],
            'summary' => $data['summary'],
            'details' => $data['details'],
        ]);

        $post->images()->delete();
        foreach ($files as $file) {
            # code...
            $name = $file->getClientOriginalName(); // Get the original name of the file
            $content = file_get_contents($file->getRealPath()); // Get the content of the file
            $extension = $file->getClientOriginalExtension(); // Get the extension of the file

            $post->images()->save(new Image([
                'name' => $name,
                'content' => base64_encode($content),
                'extension' => $extension,
            ]));
        }

        return response()->json(200);
    }
}
