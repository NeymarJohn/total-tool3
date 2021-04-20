<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FrontWidget;
use App\Helper\Reply;
use App\Http\Requests\SuperAdmin\FrontWidget\StoreRequest;
use Illuminate\Http\Request;

class FrontWidgetsController extends SuperAdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.frontWidgets';
        $this->pageIcon = 'icon-settings';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->frontWidgets = FrontWidget::all();

        return view('super-admin.front-widgets.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request)
    {
        $widgetName = $request->name;
        $widgetCode = $request->widget_code;

        FrontWidget::create(
            [
                'name' => $widgetName,
                'widget_code' => $widgetCode
            ]
        );

        return Reply::redirect(route('super-admin.front-widgets.index'));
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
        $this->widget = FrontWidget::find($id);
        return view('super-admin.front-widgets.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRequest $request, $id)
    {
        $widget = FrontWidget::findOrFail($id);
        $widget->name = $request->name;
        $widget->widget_code = $request->widget_code;
        $widget->save();

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
        FrontWidget::destroy($id);

        return Reply::success(__('messages.deleteSuccess'));
    }
}
