<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use App\Models\DriverProfile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class Accounts extends Controller
{
    public function register(Request $request)
{

        $user = new User();
        $user->name = $request->input('fname') . ' ' . $request->input('lname');
        $user->first_name = $request->input('fname');
        $user->last_name = $request->input('lname');
        $user->username = $request->input('username');
        $user->password = Hash::make($request->input('password'));
        $user->email = $request->input('gmail');
        $user->save();
      
      
        return response()->json([
            'message' => 'Login successful',
            'currentUser' => $user -> id,
            
        ]);
    }


    public function login (Request $request)
    {
        $user = User::where('username', $request->input('username'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'message' => 'Invalid username or password',

            ]);
        }
        Session::put('user', $user);


        if (DriverProfile::where('user_id', $user->id)->exists()) {
            $is_driver = true;
        } else {
            $is_driver = false;
        }

        if ($is_driver) {
                $driverRides = Ride::where('driver_id', $user->id)->get();
                return response()->json([
                    'currentUser' => $user -> id,
                    'message' => 'Login successful',
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'is_driver' => $is_driver,
                    'driver_rides' => $driverRides
                ]);
            } else {
           
                $userRides = Ride::where('owner_id', $user->id)
                    ->with('owner')
                    ->get()
                    ->map(function ($ride) {
                        $ride->owner_first_name = $ride->owner->first_name;
                        return $ride;
                    });

            
                return response()->json([
                    'currentUser' => $user -> id,   
                    'message' => 'Login successful',
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'is_driver' => $is_driver,
                    'user_rides' => $userRides
                ]);
            }


        return response()->json([
            'message' => 'Login successful',
            'currentUser' => $user -> id,
            
        ]);
    }
}