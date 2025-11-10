<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {   
        // gets all url after insertions of all url requests
    //   $urls = Url::where('user_id', $request->user()->id)->get();
    //  return response()->json($urls); // return as json response

        $urls = Url::where('user_id', $request->user()->id)->get();
        return response()->json($urls);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {   
        // show existing urls after finding or checking the db
        $url = Url::find($id);

        //return an error if no url are found
        if (!$url) {
            return response()->json(['message' => 'URL not found'], 404);
    };

        return response()->json($url); // return as a json response type
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {   
        // find exiting urls in the dbs
        $url = Url::find($id);
        if(!$url){

            return response()->json([
                'message' => 'URL not found'
            ], 404);
            
        }

        return response()->json([
            'message' => 'URL deleted successfully'
        ],201);
    }
public function incrementClickCount($shortCode)
    {   
        //check the db for existing urls of an user
        $url = Url::where('short_code', $shortCode)->first();

        // return an eeror if not found
        if (!$url) {
            return response()->json([
                'message' => 'URL not found'
            ], 404);
        }

        $url->click_count += 1;
        $url->save(); // save after checking/finding urls used by users

        return response()->json([
            'message' => 'Click count incremented', 'click_count' => $url->click_count
        ]); // return as a json response type.
    }

    public function shorten(Request $request){

        // validate incoming url
        $validated = $request->validate([

            'url' => 'max:2048|required|url'
        ]); 

        // Generate unique url code

        $shortCode = Url::generateUniqueCode();

        // Create URL record

        $url = Url::create([

            'user_id' => $request->user()->id,
            'original_url' => $validated['url'],
            'short_code' => $shortCode,
        ]);

        // return shortened urls

        return response()->json([

            'original_url' => $url->original_url,
            'short_url' => url($url->short_code),
            'short_code' => $url->short_code,
            'created_at' => $url->created_at

        ], 201);


    }

    public function redirect($short_code)
    {   
        // check for existing urls in the db
        $url = Url::where('short_code', $short_code)->first();

        // return an error if not found
        if (!$url) {
            return response()->json([
                'message' => 'URL not found'
            ], 404);
        }

        // increment click count
        $url->click_count += 1;
        $url->save();

        // redirect to original url
        return redirect($url->original_url); 
    }   

}


