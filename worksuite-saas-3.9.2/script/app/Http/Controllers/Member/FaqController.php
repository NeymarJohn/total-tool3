<?php

namespace App\Http\Controllers\Member;

use App\EmployeeFaq;
use App\EmployeeFaqCategory;
use App\Faq;
use App\Traits\CurrencyExchange;

class FaqController extends MemberBaseController
{
    use CurrencyExchange;

    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.employeeFaq';
        $this->pageIcon = 'icon-docs';
    }

    public function index()
    {
        $this->faqCategories = EmployeeFaqCategory::all();

        return view('member.faqs.index', $this->data);
    }

    public function details($id)
    {
        $this->faqDetails = EmployeeFaq::findOrFail($id);

        return view('member.faqs.details', $this->data);
    }
}
