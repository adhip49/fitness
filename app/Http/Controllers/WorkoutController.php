<?php

namespace App\Http\Controllers;

use App\WorkOut;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WorkoutController extends Controller
{
    public function addWorkout(Request $request)
    {

        $data = $request->all();

        if (isset($data['id'])) {
            $add = WorkOut::find($data['id']);
            $msg = 'Workout Updated Successfully';
        } else {
            $add = new WorkOut();
            $msg = 'Workout Added Successfully';
        }

        $add->type = $data['workout_type'];
        $add->date = $data['workout_date'];
        $add->time = $data['workout_time'];
        $add->speed = $data['workout_speed'];
        $add->location = $data['workout_location'];
        $add->user_id = Auth::id();
        $add->save();

        return array('status' => 'Success', 'msg' => $msg);
    }

    public function getWorkoutData(Request $request)
    {
        return WorkOut::where('user_id', Auth::id())->where('id', $request->id)->first();
    }

    public function removeWorkoutData($id)
    {

        $workout = WorkOut::find($id);
        $workout->delete();
        return back()->with('status', 'Workout Deleted Successfully');
    }


}
