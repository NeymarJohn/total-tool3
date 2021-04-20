<?php

namespace App\Http\Controllers\Admin;

use App\DiscussionCategory;
use App\Helper\Reply;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscussionCategory\StoreRequest;
use App\Http\Requests\DiscussionCategory\UpdateRequest;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class DiscussionCategoryController extends AdminBaseController
{
    use SoftDeletes;

    public function __construct()
    {
        parent::__construct();
    }
    
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
        $this->categories = DiscussionCategory::orderBy('order', 'asc')->get();
        return view('admin.discussion-categories.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        DiscussionCategory::create(
            [
                'name' => $request->name,
                'color' => $request->color
            ]
        );
        return Reply::success(__('messages.recordSaved'));
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
        $this->category = DiscussionCategory::findOrFail($id);
        return view('admin.discussion-categories.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, $id)
    {
        DiscussionCategory::where('id', $id)
            ->update(
                [
                    'name' => $request->name,
                    'color' => $request->color
                ]
            );
        return Reply::success(__('messages.recordSaved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DiscussionCategory::destroy($id);
        return Reply::success(__('messages.deleteSuccess'));
    }
}
