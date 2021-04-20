<?php

namespace App\Http\Controllers\SuperAdmin;

use App\FooterMenu;
use App\Helper\Files;
use App\Helper\Reply;
use App\SeoDetail;
use Illuminate\Http\Request;

class SuperAdminSeoDetailController extends SuperAdminBaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'Seo Details';
        $this->pageIcon = 'icon-settings';
    }

    public function index()
    {
        $this->seoDetails = SeoDetail::all();
        $footerMenu = FooterMenu::pluck('slug')->toArray();
        $footerMenu[] = 'home';
        if ($this->global->front_design == 0) {
            $this->seoDetails = $this->seoDetails->whereIn('page_name', $footerMenu);
        }

        return view('super-admin.front-seo-detail.index', $this->data);
    }

    public function edit($id)
    {
        $this->seoDetail = SeoDetail::findOrFail($id);
        return view('super-admin.front-seo-detail.seo-detail', $this->data);
    }

    public function update(Request $request, $id)
    {
        $seoDetail = SeoDetail::findOrFail($id);
        $seoDetail->update($request->except('og_image'));

        if ($request->hasFile('og_image')) {
            Files::deleteFile($seoDetail->image, 'front/seo-detail');
            $seoDetail->og_image = Files::upload($request->og_image, 'front/seo-detail');
            $seoDetail->save();
        }

        return Reply::redirect(route('super-admin.seo-detail.index'));
    }
}
