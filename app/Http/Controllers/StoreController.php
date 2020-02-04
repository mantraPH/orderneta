<?php

namespace App\Http\Controllers;

use App\Store;
use Illuminate\Http\Request;
use App\Http\Resources\StoreResource;

class StoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return StoreResource::collection(Store::with('products')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $this->validate($request, [
        'user_id' => 'required',
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
        ]);
        $store = new Store;
        $store->user_id = $request->user_id;
        $store->name = $request->name;
        $store->address = $request->address;
        $store->phone = $request->phone;
        $store->save();

        return new StoreResource($store);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Store $store)
    {
        return new StoreResource($store);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Store $store)
    {
        // if ($request->user()->id !== $store->user_id && $request->user()->category !== 1) {
        //   return response()->json(['error' => 'You can only edit your own Store.'], 403);
        // }
        $store->update($request->only(['name','address', 'phone']));
        return new StoreResource($store);    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,Store $store)
    {
      // if($request->user()->category !== 1){
      //   return response()->json(['error' => 'Please contact admin.'], 403);
      // }
      $store ->delete();
      return response()->json(null,204);
    }
}
