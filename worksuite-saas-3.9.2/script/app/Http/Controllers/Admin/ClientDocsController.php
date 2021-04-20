<?php

namespace App\Http\Controllers\Admin;

use App\ClientDetails;
use App\ClientDocs;
use App\EmployeeDocs;
use App\Helper\Files;
use App\Helper\Reply;
use App\Http\Requests\EmployeeDocs\CreateRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ClientDocsController extends AdminBaseController
{

    private $mimeType = [
        'txt' => 'fa-file-text',
        'htm' => 'fa-file-code-o',
        'html' => 'fa-file-code-o',
        'php' => 'fa-file-code-o',
        'css' => 'fa-file-code-o',
        'js' => 'fa-file-code-o',
        'json' => 'fa-file-code-o',
        'xml' => 'fa-file-code-o',
        'swf' => 'fa-file-o',
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
     * ManageLeadFilesController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageIcon = 'icon-layers';
        $this->pageTitle = 'app.menu.clientDocs';
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
        //
    }

    public function quickCreate($id)
    {
        $this->clientID = $id;
        $this->upload = can_upload();
        return view('admin.clients.docs-create', $this->data);
    }


    /**
     * @param Request $request
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Throwable
     */
    public function store(CreateRequest $request)
    {
        $fileFormats = ['image/jpeg', 'image/png', 'image/gif', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/pdf', 'text/plain'];
        foreach ($request->file as $index => $fFormat) {
            if (!in_array($fFormat->getClientMimeType(), $fileFormats)) {
                return Reply::error('This file format not allowed');
            }
        }
        $limitReached = false;
        foreach ($request->name as $index => $name) {
            if (isset($request->file[$index])) {
                $value = $request->file[$index];
                if ($value != '' && $name != '' && $value != null && $name != null) {
                    $upload = can_upload($value->getSize() / (1000 * 1024));
                    if ($upload) {
                        $file = new ClientDocs();
                        $file->user_id = $request->user_id;
                        $file->hashname = Files::uploadLocalOrS3($value, 'client-docs/' . $request->user_id);

                        $file->name = $name;
                        $file->filename = $value->getClientOriginalName();
                        $file->size = $value->getSize();
                        $file->save();
                    } else {
                        $limitReached = true;
                    }
                }
            }
        }

        if ($limitReached) {
            return Reply::error(__('messages.storageLimitExceed', ['here' => '<a href=' . route('admin.billing.packages') . '>Here</a>']));
        }

        $this->ClientDocs = ClientDocs::where('user_id', $request->user_id)->get();

        $view = view('admin.clients.docs-list', $this->data)->render();

        return Reply::successWithData(__('messages.fileUploaded'), ['html' => $view]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->client       = User::findClient($id);
        $this->clientDetail = ClientDetails::where('user_id', '=', $this->client->id)->first();
        $this->clientDocs   = clientDocs::where('user_id', '=', $this->client->id)->get();
        $clientController   = new ManageClientsController();
        $this->clientStats  = $clientController->clientStats($id);

        return view('admin.clients.docs', $this->data);
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
     * @param Request $request
     * @param $id
     * @return array
     * @throws \Throwable
     */
    public function destroy(Request $request, $id)
    {
        $file = ClientDocs::findOrFail($id);
        $storage = config('filesystems.default');

        switch ($storage) {
            case 'local':
                //                File::delete('user-uploads/employee-docs/'.$file->user_id.'/'.$file->hashname);
                Files::deleteFile($file->hashname, 'client-docs/' . $file->user_id);
                break;
            case 's3':
                Storage::disk('s3')->delete('client-docs/' . $file->user_id . '/' . $file->hashname);
                Files::deleteFile($file->hashname, 'client-docs/' . $file->user_id);
                break;
        }

        ClientDocs::destroy($id);

        $this->ClientDocs = ClientDocs::where('user_id', $file->user_id)->get();

        $view = view('admin.clients.docs-list', $this->data)->render();

        return Reply::successWithData(__('messages.fileDeleted'), ['html' => $view]);
    }


    /**
     * @param $id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download($id)
    {
        $file = ClientDocs::findOrFail($id);
        return download_local_s3($file, 'client-docs/' . $file->user_id . '/' . $file->hashname);
    }
}
