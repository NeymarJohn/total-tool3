<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Company;
use App\Faq;
use App\FaqCategory;
use App\FaqFile;
use App\Helper\Reply;
use App\Helper\Files;
use App\Http\Requests\SuperAdmin\Faq\StoreRequest;
use App\Http\Requests\SuperAdmin\Faq\UpdateRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class SuperAdminFaqController extends SuperAdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.faq';
        $this->pageIcon = 'icon-basket';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->categories = FaqCategory::all();
        return view('super-admin.faq.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = FaqCategory::all();
        return view('super-admin.faq.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $faq = new Faq();

        $faq->title             = $request->title;
        $faq->description       = $request->description;
        $faq->faq_category_id   = $request->category_id;
        $faq->save();

        return Reply::dataOnly(['faqID' => $faq->id]);
    }

    public function fileStore(Request $request)
    {
        if ($request->hasFile('file')) {
            foreach ($request->file as $fileData) {
                $file = new FaqFile();
                $file->user_id = $this->user->id;
                $file->faq_id = $request->faq_id;

                $filename = Files::uploadLocalOrS3($fileData, 'faq-files/' . $request->faq_id);

                $file->filename = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size = $fileData->getSize();
                $file->save();
            }
        }

        return Reply::redirect(route('super-admin.faq.index'), __('messages.createSuccess'));
    }


    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->faq = Faq::find($id);
        $this->categories = FaqCategory::all();
      
        return view('super-admin.faq.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function update(UpdateRequest $request, $id)
    {
        $faq = Faq::find($id);

        $faq->title             = $request->title;
        $faq->description       = $request->description;
        $faq->faq_category_id   = $request->category_id;
        $faq->save();

        return Reply::dataOnly(['faqID' => $faq->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faqFiles = FaqFile::where('faq_id', $id)->get();

        foreach ($faqFiles as $file) {
            Files::deleteFile($file->hashname, 'faq-files/' . $file->faq_id);
            $file->delete();
        }
        Faq::destroy($id);

        return Reply::success('messages.deleteSuccess');
    }

    /**
     * @return mixed
     */
    public function data(Request $request)
    {
        $faqCategories = Faq::select('faqs.id', 'faqs.title', 'faqs.description', 'faq_categories.name')
        ->join('faq_categories', 'faq_categories.id', 'faqs.faq_category_id');

        if($request->category != 'all' && $request->category !== ''){
            $faqCategories = $faqCategories->where('faqs.faq_category_id', $request->category);
        }

        return Datatables::of($faqCategories)
            ->addColumn('faq', function($row) {
                return ucwords($row->title);
            })
            ->addColumn('description', function($row) {
                return ucwords($row->description);
            })
            ->addColumn('action', function($row){
                $action = '';

                $action .= ' <a href="'.route('super-admin.faq.edit', $row->id).'" class="btn btn-info btn-circle"
                      data-toggle="tooltip" data-original-title="'.trans('app.edit').'"><i class="fa fa-pencil" aria-hidden="true"></i></a>';

                $action .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                  data-toggle="tooltip" data-faq-id="'.$row->id.'" data-original-title="'.trans('app.delete').'"><i class="fa fa-times" aria-hidden="true"></i></a>';

                return $action;

            })

            ->rawColumns(['action', 'description'])
            ->make(true);
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function fileDelete(Request $request, $id)
    {
        $file = faqFile::findOrFail($id);

        Files::deleteFile($file->hashname,'faq-files/'.$file->faq_id);

        faqFile::destroy($id);

        $this->taskFiles = faqFile::where('faq_id', $file->faq_id)->get();

        return Reply::success(__('messages.fileDeleted'));
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id) {
        $file = FaqFile::findOrFail($id);
        return download_local_s3($file, 'faq-files/' . $file->faq_id.'/'.$file->hashname);
    }
}
