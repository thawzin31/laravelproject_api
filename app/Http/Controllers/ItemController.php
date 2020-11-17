<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;
class ItemController extends Controller
{
    public function __construct($value=''){
        //$this->middleware('auth:api')->only('store');
        $this->middleware('auth:api')->except('index');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items=Item::all();
        //return $items;

        return response()->json([
            'status'=>'ok',
            'totalResult'=>count($items),
            'items'=>ItemResource::collection($items)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd
        //validation
        $request->validate([
            "name" => "required|min:5",
            "photo" => "required", // a.jpg
            "price" => "required",
            "discount" => "sometimes|required",
            "description" => "required",
            "brand" => "required",
            "subcategory" => "required"

        ]);
        //file upload
        if($request->file()) {
            // 624872374523_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            // brandimg/624872374523_a.jpg
            $filePath = $request->file('photo')->storeAs('itemimg', $fileName, 'public');

            $path = '/storage/'.$filePath;

        }
        //data store
        $item = new Item;
        $item->codeno = uniqid();
        $item->name = $request->name;
        $item->photo = $path;
        $item->price = $request->price;
        $item->discount = $request->discount;
        $item->description = $request->description;
        $item->brand_id = $request->brand;
        $item->subcategory_id = $request->subcategory;
        $item->save();

        //redirected
        return new ItemResource($item);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        // dd($request);

        // Validation
        $request-> validate([
            "name" => "required|min:5",
            "photo" => "sometimes|required|mimes:jpeg,bmp,png", // a.jpg
            "price" => "required",
            "discount" => "sometimes|required",
            "description" => "required",
            "brand" => "required",
            "subcategory" => "required"
        ]);

        // If include file, upload
        if($request->file()) {
            // 624872374523_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            // brandimg/624872374523_a.jpg
            $filePath = $request->file('photo')->storeAs('itemimg', $fileName, 'public');

            $path = '/storage/'.$filePath;
        }else{
            $path = $request->oldphoto;
        }

        // store
        $item->name = $request->name;
        $item->photo = $path;
        $item->price = $request->price;
        $item->discount = $request->discount;
        $item->description = $request->description;
        $item->brand_id = $request->brand;
        $item->subcategory_id = $request->subcategory;
        $item->save();

        // redirect
        return new ItemResource($item);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        $item->delete();
        return new ItemResource($item);
    }
}
