<?php

namespace App\Http\Controllers;

use App\WorkOut;
use Illuminate\Http\Request;

class WorkoutController extends Controller
{
    public function addWorkout(Request $request)
    {

        $data = $request->all();

        $add = new WorkOut();
        $add->type = $data['workout_type'];
        $add->date = $data['workout_date'];
        $add->time = $data['workout_time'];
        $add->speed = $data['workout_speed'];
        $add->location = $data['workout_location'];
        $add->save();

        return array('status' => 'Success', 'msg' => 'Workout added successfully');
    }

    public function getWorkoutData(Request $request)
    {

        $data = $request->all();
        $workout = WorkOut::find($data['id']);
        return $workout;
    }


}
