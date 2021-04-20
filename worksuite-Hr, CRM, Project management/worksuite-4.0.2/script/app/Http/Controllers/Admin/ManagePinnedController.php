<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Project\StoreProjectCategory;
use App\Pinned;
use App\ProjectCategory;
use Illuminate\Http\Request;

class ManagePinnedController extends AdminBaseController
{

    public function __construct() {
        parent::__construct();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $pinned = new Pinned();
        $pinned->task_id = $request->task_id;
        $pinned->project_id = $request->project_id;
        $pinned->save();

        return Reply::success(__('messages.pinnedSuccess'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if($request->type == 'task'){
            Pinned::where('task_id', $id)->where('user_id', user()->id)->delete();
        }
        else{
            Pinned::where('project_id', $id)->where('user_id', user()->id)->delete();
        }



        return Reply::success(__('messages.pinnedRemovedSuccess'));
    }
}
