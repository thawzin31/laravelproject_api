<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands=Brand::all();
        //return $brands;

        return response()->json([
            'status'=>'ok',
            'totalResult'=>count($brands),
            'brands'=>BrandResource::collection($brands)
        ]);
        //return BrandResource::collection($brands);//array
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
            "name"=>"required|min:5",
            "photo"=>"required|mimes:jepg,bmp,png",//a.jpg
        ]);

        //if include file,upload
        if ($request->file()) {
            //2334455666_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            //brandimg/2334455666_a.jpg
            $filePath=$request->file('photo')->storeAs('brandimg',$fileName,'public');

            //public/stroage/brandimg
            $path='/storage/'.$filePath;
        }

        $brand=new Brand;
        $brand->name=$request->name;
        $brand->photo=$path;
        $brand->save();
        
        //redirected
        return new BrandResource($brand);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function show(Brand $brand)
    {
        

        return new BrandResource($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            "name"=>"required|min:5",
            "photo"=>"sometimes|required|mimes:jepg,bmp,png",//a.jpg
            "oldphoto"=>"required",
        ]);

        //if include file,upload
        if ($request->file()) {
            //2334455666_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            //brandimg/2334455666_a.jpg
            $filePath=$request->file('photo')->storeAs('brandimg',$fileName,'public');

            //public/stroage/brandimg
            $path='/storage/'.$filePath;
        }else{
            $path=$request->oldphoto;
        }

        //store to brandimg folder
       
        $brand->name=$request->name;
        $brand->photo=$path;
        $brand->save();

        return new BrandResource($brand);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Brand  $brand
     * @return \Illuminate\Http\Response
     */
    public function destroy(Brand $brand)
    {
        $brand->delete();
        return new BrandResource($brand);
    }
}
