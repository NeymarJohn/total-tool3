<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\ClientCategory;
use App\ClientSubCategory;
use App\Helper\Reply;
use App\Http\Requests\Admin\Client\StoreClientCategory;

class ClientCategoryController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = ClientCategory::all();
        return view('admin.clients.create_category', $this->data);
    }   
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreClientCategory $request)
    {
        $category = new ClientCategory();
        $category->category_name = $request->category_name;
        $category->save();
        $categoryData = ClientCategory::all();
        return Reply::successWithData(__('messages.categoryAdded'),['data' => $categoryData]);
    }
   
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        ClientCategory::destroy($id);
        $categoryData = ClientCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data'=> $categoryData]);
    }
}
