<?php

namespace App\Http\Controllers\Admin;

use App\EmployeeFaq;
use App\EmployeeFaqCategory;
use App\EmployeeFaqFile;
use App\Helper\Reply;
use App\Helper\Files;
use App\Http\Requests\SuperAdmin\Faq\StoreRequest;
use App\Http\Requests\SuperAdmin\Faq\UpdateRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;


class AdminEmployeeFaqController extends AdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.employeeFaq';
        $this->pageIcon = 'icon-basket';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->categories = EmployeeFaqCategory::all();
        return view('admin.employee-faq.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->categories = EmployeeFaqCategory::all();
        return view('admin.employee-faq.create', $this->data);
    }

    /**
     * @param StoreRequest $request
     * @return mixed
     */
    public function store(StoreRequest $request)
    {
        $faq = new EmployeeFaq();

        $faq->title                         = $request->title;
        $faq->description                   = $request->description;
        $faq->employee_faq_category_id      = $request->category_id;
        $faq->save();

        return Reply::dataOnly(['faqID' => $faq->id]);
    }

    /**
     * @param Request $request
     * @return array|string[]
     * @throws \Froiden\RestAPI\Exceptions\RelatedResourceNotFoundException
     */
    public function fileStore(Request $request)
    {
        if ($request->hasFile('file')) {
            foreach ($request->file as $fileData) {
                $file = new EmployeeFaqFile();
                $file->user_id = $this->user->id;
                $file->employee_faq_id = $request->faq_id;

                $filename = Files::uploadLocalOrS3($fileData, 'employee-faq-files/' . $request->faq_id);

                $file->filename = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size = $fileData->getSize();
                $file->save();
            }
        }

        return Reply::redirect(route('admin.employee-faq.index'), __('messages.createSuccess'));
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->faq = EmployeeFaq::find($id);
        $this->categories = EmployeeFaqCategory::all();
      
        return view('admin.employee-faq.edit', $this->data);
    }

    /**
     * @param UpdateRequest $request
     * @param $id
     * @return mixed
     */
    public function update(UpdateRequest $request, $id)
    {
        $faq = EmployeeFaq::find($id);

        $faq->title             = $request->title;
        $faq->description       = $request->description;
        $faq->employee_faq_category_id   = $request->category_id;
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
        $faqFiles = EmployeeFaqFile::where('employee_faq_id', $id)->get();

        foreach ($faqFiles as $file) {
            Files::deleteFile($file->hashname, 'employee-faq-files/' . $file->employee_faq_id);
            $file->delete();
        }
        EmployeeFaq::destroy($id);

        return Reply::success('messages.deleteSuccess');
    }

    /**
     * @return mixed
     */
    public function data(Request $request)
    {
        $faqCategories = EmployeeFaq::select('employee_faqs.id', 'employee_faqs.title', 'employee_faqs.description', 'employee_faq_categories.name')
        ->join('employee_faq_categories', 'employee_faq_categories.id', 'employee_faqs.employee_faq_category_id');

        if($request->category != 'all' && $request->category !== ''){
            $faqCategories = $faqCategories->where('employee_faqs.employee_faq_category_id', $request->category);
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

                $action .= ' <a href="'.route('admin.employee-faq.edit', $row->id).'" class="btn btn-info btn-circle"
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
        $file = EmployeeFaqFile::findOrFail($id);

        Files::deleteFile($file->hashname,'employee-faq-files/'.$file->employee_faq_id);

        EmployeeFaqFile::destroy($id);

        $this->taskFiles = EmployeeFaqFile::where('employee_faq_id', $file->employee_faq_id)->get();

        return Reply::success(__('messages.fileDeleted'));
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id) {
        $file = EmployeeFaqFile::findOrFail($id);
        return download_local_s3($file, 'employee-faq-files/' . $file->employee_faq_id.'/'.$file->hashname);
    }
}
