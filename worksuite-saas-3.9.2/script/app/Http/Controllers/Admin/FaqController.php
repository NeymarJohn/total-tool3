<?php

namespace App\Http\Controllers\Admin;

use App\Faq;
use App\FaqCategory;
use App\Traits\CurrencyExchange;

class FaqController extends AdminBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.myFaq';
        $this->pageIcon = 'icon-docs';
    }

    public function index()
    {
        $this->faqCategories = FaqCategory::all();

        return view('admin.faqs.index', $this->data);
    }

    public function details($id)
    {
        $this->faqDetails = Faq::findOrFail($id);

        return view('admin.faqs.details', $this->data);
    }
}
