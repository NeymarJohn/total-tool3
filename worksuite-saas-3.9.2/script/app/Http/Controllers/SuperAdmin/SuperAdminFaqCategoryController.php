<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\Faq;
use App\FaqCategory;
use App\Helper\Reply;

use App\Http\Requests\SuperAdmin\FaqCategory\StoreRequest;
use App\Http\Requests\SuperAdmin\FaqCategory\UpdateRequest;
use Yajra\DataTables\Facades\DataTables;


class SuperAdminFaqCategoryController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.faqCategory';
        $this->pageIcon = 'icon-docs';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('super-admin.faq-category.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = FaqCategory::all();
        return view('super-admin.faq-category.create-category', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $faqCategory = new FaqCategory();
        $faqCategory->name = $request->name;
        $faqCategory->save();
        $categoryData = FaqCategory::all();
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
        $this->faqCategory = FaqCategory::find($id);

        return view('super-admin.faq-category.add-edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $faqCategory = FaqCategory::find($id);
        $faqCategory->name = $request->name;
        $faqCategory->save();

        return Reply::success(__('messages.updatedSuccessfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FaqCategory::destroy($id);
        $categoryData = FaqCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data' => $categoryData]);
    }
}
