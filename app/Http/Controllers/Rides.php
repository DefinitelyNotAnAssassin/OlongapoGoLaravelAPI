<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ride;
use App\Models\DriverProfile;
use Carbon\Carbon;

class Rides extends Controller
{
    public function getRides(Request $request)
    {
        $currentUser = $request->input('currentUser');
        $user = User::findOrFail($currentUser);

        if (DriverProfile::where('user_id', $user->id)->exists()) {
            $is_driver = true;
        } else {
            $is_driver = false;
        }
       
        if ($is_driver) {
            $driverRides = Ride::where('driver_id', $user->id)
                ->orderBy('id', 'desc')
                ->with('owner')
                ->get([
                    'id',
                    'owner_first_name',
                    'owner_last_name',
                    'driver_first_name',
                    'driver_last_name',
                    'destination',
                    'required_arrival_time',
                    'passenger_number_from_owner',
                    'passenger_number_in_total',
                    'ride_status',
                    'requested_vehicle_type',
                    'special_request',
                ]);

            $rideList = $driverRides->map(function ($ride) {
                return [
                    'id' => $ride->id,
                    'owner_id' => $ride->owner_first_name . ' ' . $ride->owner_last_name,
                    'driver_id' => $ride->driver_first_name . ' ' . $ride->driver_last_name,
                    'destination' => $ride->destination,
                    'required_arrival_time' => $ride->required_arrival_time,
                    'passenger_number_from_owner' => $ride->passenger_number_from_owner,
                    'passenger_number_in_total' => $ride->passenger_number_in_total,
                    'ride_status' => $ride->ride_status,
                    'requested_vehicle_type' => $ride->requested_vehicle_type,
                    'special_request' => $ride->special_request,
                ];
            });

            return response()->json(['driver_rides' => $rideList]);
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
    }

public function createRide(Request $request)
{

    if ($request->isMethod('post')) {
        $user = User::findOrFail($request->input('currentUser'));

        $destination = $request->input('destination');
        $required_arrival_time = Carbon::createFromFormat('m/d/Y\TH:i', $request->input('arrival_time'));
        $passenger_number_from_owner = $request->input('number_of_passengers');
        $requested_vehicle_type = $request->input('vehicle_type');
        $special_request = $request->input('special_request');

        Ride::create([
            'owner_id' => $user->id,
            'destination' => $destination,
            'required_arrival_time' => $required_arrival_time,
            'passenger_number_from_owner' => $passenger_number_from_owner,
            'passenger_number_in_total' => $passenger_number_from_owner,
            'can_be_shared' => false,
            'requested_vehicle_type' => $requested_vehicle_type,
            'special_request' => $special_request,
        ]);

        return response()->json(['message' => 'You have successfully made a request.'], 200);
    }
}




public function search(Request $request)
{
    // GET
    if ($request->isMethod('get')) {
        return view('rides.search');
    }

    // POST
    if ($request->isMethod('post')) {
        $search_as = $request->input('search_as');
        if (!in_array($search_as, ['driver', 'sharer'])) {
            return Response::make('', 404);
        }

        if ($search_as == 'driver') {
            $driverRides = Ride::orderBy('id', 'desc')
                ->where('ride_status', 'open')->with('owner')->get();
                
            $rideList = [];
            foreach ($driverRides as $ride) {
                $rideMap = [];
                $rideMap["id"] = $ride->id;
                $rideMap["owner_id"] = $ride->owner->first_name . " " . $ride->owner->last_name;
                $rideMap["destination"] = $ride->destination;
                $rideMap["required_arrival_time"] = $ride->required_arrival_time;
                $rideMap["passenger_number_from_owner"] = $ride->passenger_number_from_owner;
                $rideMap["passenger_number_in_total"] = $ride->passenger_number_in_total;
                $rideMap["ride_status"] = $ride->ride_status;
                $rideMap["requested_vehicle_type"] = $ride->requested_vehicle_type;
                $rideMap["special_request"] = $ride->special_request;
                $rideList[] = $rideMap;
            }

            return response()->json(['driver_rides' => $rideList]);
        }
    }


    
}
public function acceptRide(Request $request)
{
    if ($request->isMethod('post')) {
        $currentUser = $request->input('currentUser');
        $rideId = $request->input('ride_id');

        $user = User::find($currentUser);
        $ride = Ride::find($rideId);

        if (!$user || !$ride) {
            return Response::make('', 404);
        }

        $ride->driver_id = $user->id;
        $ride->ride_status = 'confirm';
        $ride->save();

        return response()->json(['message' => 'Ride accepted']);
    }
}
public function updateRideStatus(Request $request)
{
    if ($request->isMethod('post')) {
        $rideId = $request->input('ride_id');

        $ride = Ride::find($rideId);

        if (!$ride) {
            return Response::make('', 404);
        }

        $ride->ride_status = 'complete';
        $ride->save();

        return response()->json(['message' => 'Ride status updated']);
    }
}
    
}

