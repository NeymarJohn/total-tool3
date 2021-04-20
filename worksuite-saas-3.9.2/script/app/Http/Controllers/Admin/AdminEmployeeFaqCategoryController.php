<?php

namespace App\Http\Controllers\Admin;

use App\EmployeeFaqCategory;
use App\FaqCategory;
use App\Helper\Reply;

use App\Http\Requests\SuperAdmin\FaqCategory\StoreRequest;
use App\Http\Requests\SuperAdmin\FaqCategory\UpdateRequest;


class AdminEmployeeFaqCategoryController extends AdminBaseController
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
        return view('admin.employee-faq-category.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = EmployeeFaqCategory::all();
        return view('admin.employee-faq-category.create-category', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return array
     */
    public function store(StoreRequest $request)
    {
        $faqCategory = new EmployeeFaqCategory();
        $faqCategory->name = $request->name;
        $faqCategory->save();
        $categoryData = EmployeeFaqCategory::all();
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
        $this->faqCategory = EmployeeFaqCategory::find($id);

        return view('admin.employee-faq-category.add-edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateRequest $request, $id)
    {
        $faqCategory = EmployeeFaqCategory::find($id);
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
        EmployeeFaqCategory::destroy($id);
        $categoryData = EmployeeFaqCategory::all();
        return Reply::successWithData(__('messages.categoryDeleted'),['data' => $categoryData]);
    }
}
