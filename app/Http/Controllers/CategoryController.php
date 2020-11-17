<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
         $categories=Category::all();
        //return $categories;

        return response()->json([
            'status'=>'ok',
            'totalResult'=>count($categories),
            'categories'=>CategoryResource::collection($categories)
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
       //validation
        $request->validate([
            "name"=>"required|min:5",
            "photo"=>"required",//a.jpg
        ]);

        //if include file,upload
        if ($request->file()) {
            //2334455666_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            //brandimg/2334455666_a.jpg
            $filePath=$request->file('photo')->storeAs('categoryimg',$fileName,'public');

            //public/stroage/brandimg
            $path='/storage/'.$filePath;
        }

        $category=new Category;
        $category->name=$request->name;
        $category->photo=$path;
        $category->save();
        
        //redirected
        return new CategoryResource($category);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return new BrandResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            "name"=>"required|min:5",
            "photo"=>"sometimes|required",
            "oldphoto"=>"required",
        ]);

        if ($request->file()) {
            //2334455666_a.jpg
            $fileName = time().'_'.$request->photo->getClientOriginalName();

            //brandimg/2334455666_a.jpg
            $filePath=$request->file('photo')->storeAs('categoryimg',$fileName,'public');

            //public/stroage/brandimg
            $path='/storage/'.$filePath;
        }else{
            $path=$request->oldphoto;
        }

        //store to brandimg folder
        //$category= Category::find($id);
        $category->name=$request->name;
        $category->photo=$path;
        $category->save();

        //redirect
        return new CategoryResource($category);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return new CategoryResource($category);
    }
}
