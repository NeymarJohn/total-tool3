<?php

namespace App\Http\Controllers\Admin;

use App\Helper\Reply;
use App\Http\Requests\Sticky\StoreStickyNote;
use App\Http\Requests\Sticky\UpdateStickyNote;
use App\StickyNote;

class ManageStickyNotesController extends AdminBaseController
{
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.stickyNotes';
        $this->pageIcon = 'icon-note';
    }

    public function index()
    {
        $this->user = auth()->user();
        $this->noteDetails = StickyNote::where('user_id', $this->user->id)->orderBy('updated_at', 'desc')->get();

        if(request()->ajax())
        {
            return   view('admin.sticky-note.note-ajax', $this->data);

        }

        return view('admin.sticky-note.index', $this->data);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        $this->noteDetail = [];

        return view('admin.sticky-note.create-edit', $this->data);
    }

    /**
     * @param UserStoreRequest $request
     * @return array
     */
    public function store(StoreStickyNote $request)
    {

        $sticky = new StickyNote();
        $sticky->note_text  = $request->get('notetext');
        $sticky->colour     = $request->get('stickyColor');
        if($sticky->colour == ''){
            $sticky->colour = 'blue';
        }
        $sticky->user_id = auth()->user()->id;

        $sticky->save();

        return Reply::success(__('messages.noteCreated'));

    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $this->iconEdit = 'pencil';
        $this->noteDetail = StickyNote::findOrFail($id);

        return view('admin.sticky-note.create-edit', $this->data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function noteList($id)
    {
        $this->iconEdit = 'pencil';
        // Call the same create view for edit
        $this->noteDetail = StickyNote::findOrFail($id);
        return view('admin.sticky-note.create-edit', $this->data);
    }

    /**
     * @param UserUpdateRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateStickyNote $request,$id)
    {
        $sticky = StickyNote::findOrFail($id);
        $sticky->note_text  = $request->get('notetext');
        $sticky->colour     = $request->get('stickyColor');

        $sticky->save();
        return Reply::success(__('messages.noteUpdated'));
    }

    /**
     * @param $id
     * @return array
     */
    public function destroy($id)
    {
        StickyNote::destroy($id);
        return Reply::success(__('messages.noteDeleted'));
    }

}
