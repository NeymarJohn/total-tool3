<?php

namespace App\Http\Controllers\Admin;

use App\Contract;
use App\ContractFile;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\StoreFileLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ManageContractFilesController extends AdminBaseController
{

    private $mimeType = [
        'txt' => 'fa-file-text',
        'htm' => 'fa-file-code-o',
        'html' => 'fa-file-code-o',
        // 'php' => 'fa-file-code-o',
        'css' => 'fa-file-code-o',
        'js' => 'fa-file-code-o',
        'json' => 'fa-file-code-o',
        'xml' => 'fa-file-code-o',
        'swf' => 'fa-file-o',
        'CR2' => 'fa-file-o',
        'flv' => 'fa-file-video-o',

        // images
        'png' => 'fa-file-image-o',
        'jpe' => 'fa-file-image-o',
        'jpeg' => 'fa-file-image-o',
        'jpg' => 'fa-file-image-o',
        'gif' => 'fa-file-image-o',
        'bmp' => 'fa-file-image-o',
        'ico' => 'fa-file-image-o',
        'tiff' => 'fa-file-image-o',
        'tif' => 'fa-file-image-o',
        'svg' => 'fa-file-image-o',
        'svgz' => 'fa-file-image-o',

        // archives
        'zip' => 'fa-file-o',
        'rar' => 'fa-file-o',
        'exe' => 'fa-file-o',
        'msi' => 'fa-file-o',
        'cab' => 'fa-file-o',

        // audio/video
        'mp3' => 'fa-file-audio-o',
        'qt' => 'fa-file-video-o',
        'mov' => 'fa-file-video-o',
        'mp4' => 'fa-file-video-o',
        'mkv' => 'fa-file-video-o',
        'avi' => 'fa-file-video-o',
        'wmv' => 'fa-file-video-o',
        'mpg' => 'fa-file-video-o',
        'mp2' => 'fa-file-video-o',
        'mpeg' => 'fa-file-video-o',
        'mpe' => 'fa-file-video-o',
        'mpv' => 'fa-file-video-o',
        '3gp' => 'fa-file-video-o',
        'm4v' => 'fa-file-video-o',

        // adobe
        'pdf' => 'fa-file-pdf-o',
        'psd' => 'fa-file-image-o',
        'ai' => 'fa-file-o',
        'eps' => 'fa-file-o',
        'ps' => 'fa-file-o',

        // ms office
        'doc' => 'fa-file-text',
        'rtf' => 'fa-file-text',
        'xls' => 'fa-file-excel-o',
        'ppt' => 'fa-file-powerpoint-o',
        'docx' => 'fa-file-text',
        'xlsx' => 'fa-file-excel-o',
        'pptx' => 'fa-file-powerpoint-o',


        // open office
        'odt' => 'fa-file-text',
        'ods' => 'fa-file-text',
    ];

    /**
     * ManageProjectFilesController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.contracts';

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->contract = Contract::findOrFail($request->contract_id);

        if ($request->hasFile('file')) {
            $file = new ContractFile();
            $file->user_id     = $this->user->id;
            $file->contract_id = $request->contract_id;
            $hashname = Files::uploadLocalOrS3($request->file,'contract-files/'.$request->contract_id);

            $file->filename = $request->file->getClientOriginalName();
            $file->hashname = $hashname;
            $file->size     = $request->file->getSize();
            $file->save();
//            $this->logProjectActivity($request->contract_id, __('messages.newFileUploadedToTheProject'));
        }


        if ($request->view == 'list') {
            $view = view('admin.contracts.contract-files.ajax-list', $this->data)->render();
        } else {
            $view = view('admin.contracts.contract-files.thumbnail-list', $this->data)->render();
        }
        return Reply::successWithData(__('messages.fileUploaded'), ['html' => $view]);
    }

    public function storeMultiple(Request $request)
    {
        if ($request->hasFile('file')) {
            foreach ($request->file as $fileData){

                $file = new ContractFile();
                $file->user_id     = $this->user->id;
                $file->contract_id = $request->contract_id;

                $filename = Files::uploadLocalOrS3($fileData,'contract-files/'.$request->contract_id);

                $file->filename = $fileData->getClientOriginalName();
                $file->hashname = $filename;
                $file->size     = $fileData->getSize();
                $file->save();
//                $this->logProjectActivity($request->contract_id, __('messages.newFileUploadedToTheProject'));
            }

        }
        return Reply::redirect(route('admin.contracts.index'), __('modules.contracts.projectUpdated'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->contract = Contract::findOrFail($id);
        return view('admin.contracts.contract-files.show', $this->data);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $storage = config('filesystems.default');
        $file = ContractFile::findOrFail($id);
        switch($storage) {
            case 'local':
                File::delete('user-uploads/contract-files/'.$file->contract_id.'/'.$file->hashname);
                break;
            case 's3':
                Storage::disk('s3')->delete('contract-files/'.$file->contract_id.'/'.$file->filename);
                break;
        }
        ContractFile::destroy($id);
        $this->contract = Contract::findOrFail($file->contract_id);

        if($request->view == 'list') {
            $view = view('admin.contracts.contract-files.ajax-list', $this->data)->render();
        } else {
            $view = view('admin.contracts.contract-files.thumbnail-list', $this->data)->render();
        }
        return Reply::successWithData(__('messages.fileDeleted'), ['html' => $view]);
    }

    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id) {
        $file = ContractFile::findOrFail($id);
        return download_local_s3($file,'contract-files/'.$file->contract_id.'/'.$file->hashname);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function thumbnailShow(Request $request)
    {
        $this->contract = Contract::with('files')->findOrFail($request->id);

        $view = view('admin.contracts.contract-files.thumbnail-list', $this->data)->render();
        return Reply::dataOnly(['status' => 'success', 'view' => $view]);
    }

    public function storeLink(StoreFileLink $request)
    {
        $file = new ContractFile();
        $file->user_id       = $this->user->id;
        $file->contract_id   = $request->contract_id;
        $file->external_link = $request->external_link;
        $file->filename      = $request->filename;
        $file->save();
//        $this->logProjectActivity($request->contract_id, __('messages.newFileUploadedToTheProject'));

        return Reply::success(__('messages.fileUploaded'));
    }
}
