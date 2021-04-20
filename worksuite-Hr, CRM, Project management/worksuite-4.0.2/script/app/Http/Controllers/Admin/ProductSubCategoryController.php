<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductSubCategory;
use App\Http\Requests\Project\StoreProjectCategory;
use App\ProductCategory;
use App\ProductSubCategory;
use Illuminate\Http\Request;

class ProductSubCategoryController extends AdminBaseController
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
    public function create(Request $request)
    {
        $this->categoryID = $request->catID;
        $this->subCategories = ProductSubCategory::all();
        $this->categories = ProductCategory::all();
        return view('admin.products.create-sub-category', $this->data);
    }


    /**
     * @param StoreProductSubCategory $request
     * @return array
     */
    public function store(StoreProductSubCategory $request)
    {
        $category = new ProductSubCategory();
        $category->category_id = $request->category;
        $category->category_name = $request->sub_category_name;
        $category->save();
        $categoryData = ProductSubCategory::all();
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
        ProductSubCategory::destroy($id);
        $categoryData = ProductSubCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data' => $categoryData]);
    }
}
