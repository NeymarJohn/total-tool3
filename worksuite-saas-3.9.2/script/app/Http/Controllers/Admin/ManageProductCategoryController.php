<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductCategory;
use App\ProductCategory;
use Illuminate\Http\Request;

class ManageProductCategoryController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = ProductCategory::all();
        return view('admin.products.create-category', $this->data);
    }


    /**
     * @param StoreProductCategory $request
     * @return array
     */
    public function store(StoreProductCategory $request)
    {
        $category = new ProductCategory();
        $category->category_name = $request->category_name;
        $category->save();
        $categoryData = ProductCategory::all();
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
        ProductCategory::destroy($id);
        $categoryData = ProductCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data' => $categoryData]);
    }
}
