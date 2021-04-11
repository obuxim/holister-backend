<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use Illuminate\Support\Str;

class CitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();
        $response = new \stdClass();
        if(count($cities)>0) {
            $response->error = false;
            $response->message = "All cities loaded!";
            $response->data = $cities;
        }else{
            $response->error = true;
            $response->message = "No city found!";
            $response->data = [];
        }
        return response()->json($response, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $slug = Str::slug($fields['name']);
        $fields['slug'] = $slug;

        $response = new \stdClass();

        try{
            $city = City::create($fields);
            $response->error = false;
            $response->message = "City created!";
            $response->data = [$city];
            return response()->json($response, 201);
        }catch (\Exception $error){
            $response->error = true;
            $response->message = "Could not create city!";
            $response->data = $error->getMessage();
            return response()->json($response, 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $city = City::findOrFail($id);
        $response = new \stdClass();
        $response->error = false;
        $response->message = "City with id:(".$id.") found!";
        $response->data = [$city];
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $city = City::findOrFail($id);
        $fields = $request->validate([
            'name' => 'string',
            'latitude' => 'numeric',
            'longitude' => 'numeric',
        ]);
        $fields['slug'] = Str::slug($fields['name']);
        $response = new \stdClass();
        try {
            $city->update($fields);
            $response->error = false;
            $response->message = "City with id:(".$id.") updated";
            $response->data = [$city];
            return response()->json($response, 201);
        }catch (\Exception $error){
            $response->error = true;
            $response->message = "Could not update city!";
            $response->data = $error->getMessage();
            return response()->json($response, 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $city = City::findOrFail($id);
        $response = new \stdClass();
        try {
            $city->delete();
            $response->error = false;
            $response->message = "City with id:(".$id.") deleted!";
            $response->data = [$city];
            return response()->json($response, 410);
        }catch (\Exception $error){
            $response->error = true;
            $response->message = "Could not delete city!";
            $response->data = $error->getMessage();
            return response()->json($response, 500);
        }
    }
}
