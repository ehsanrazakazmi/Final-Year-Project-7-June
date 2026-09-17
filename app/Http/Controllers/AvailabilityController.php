<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $availabilities = Availability::all();

        return view('laravel-examples/availibility.index', ['availabilities' => $availabilities]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:availabilities|max:255',
            'available_from' => 'required',
            'available_to' => 'required',
        ]);

        $availability = new Availability();
        $availability->name = $request->name;
        $availability->available_from = $request->available_from;
        $availability->available_to = $request->available_to;
        $availability->save();

        return back()->with('success', 'Availability saved');
    }

    public function destroy($id)
    {
        Availability::findOrFail($id)->delete();

        return back()->with('success', 'Availability deleted');
    }
}
