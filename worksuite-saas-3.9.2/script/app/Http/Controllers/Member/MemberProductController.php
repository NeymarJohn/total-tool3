<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\InvoiceSetting;
use App\Product;
use App\ProductCategory;
use App\ProductSubCategory;
use App\Tax;
use Yajra\DataTables\Facades\DataTables;

class MemberProductController extends MemberBaseController
{
    /**
     * MemberProductController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->pageTitle = 'app.menu.products';
        $this->pageIcon = 'icon-basket';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // check user permission for this action
       abort_if(!$this->user->cans('view_product'),403);

        $this->totalProducts = Product::count();
        $this->categories = ProductCategory::all();
        $this->subCategories = ProductSubCategory::all();
        $this->invoiceSetting = InvoiceSetting::first();
        return view('member.products.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // check user permission for this action
       abort_if(!$this->user->cans('add_product'),403);
        $this->categories = ProductCategory::all();
        $this->subCategories = ProductSubCategory::all();
        $this->invoiceSetting = InvoiceSetting::first();
        $this->taxes = Tax::all();
        return view('member.products.create', $this->data);
    }

    /**
     * @param StoreProductRequest $request
     * @return array
     */
    public function store(StoreProductRequest $request)
    {
        // check user permission for this action
       abort_if(!$this->user->cans('add_product'),403);

        $products = new Product();
        $products->name = $request->name;
        $products->price = $request->price;
        $products->hsn_sac_code = $request->hsn_sac_code;
        $products->taxes = json_encode($request->tax);
        $products->category_id = ($request->category_id) ? $request->category_id : null;
        $products->sub_category_id = ($request->sub_category_id) ? $request->sub_category_id : null;
        $products->allow_purchase = ($request->purchase_allow == 'no') ? true : false;
        $products->description = $request->description;
        $products->save();

        return Reply::redirect(route('member.products.index'), __('messages.productAdded'));
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
        // check user permission for this action
       abort_if(!$this->user->cans('edit_product'),403);

        $this->product = Product::find($id);
        $this->taxes = Tax::all();
        $this->categories = ProductCategory::all();
        $this->subCategories = ProductSubCategory::all();
        $this->invoiceSetting = InvoiceSetting::first();

        return view('member.products.edit', $this->data);
    }

    /**
     * @param UpdateProductRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateProductRequest $request, $id)
    {
        // check user permission for this action
       abort_if(!$this->user->cans('edit_product'),403);

        $products = Product::find($id);
        $products->name = $request->name;
        $products->price = $request->price;
        $products->hsn_sac_code = $request->hsn_sac_code;
        $products->taxes = json_encode($request->tax);
        $products->allow_purchase = ($request->purchase_allow == 'no') ? true : false;
        $products->category_id = ($request->category_id) ? $request->category_id : null;
        $products->sub_category_id = ($request->sub_category_id) ? $request->sub_category_id : null;
        $products->description = $request->description;
        $products->save();

        return Reply::redirect(route('member.products.index'), __('messages.productUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check user permission for this action
       abort_if(!$this->user->cans('delete_product'),403);

        Product::destroy($id);
        return Reply::success(__('messages.productDeleted'));
    }

    /**
     * @return mixed
     */
    public function data()
    {
        // check user permission for this action
       abort_if(!$this->user->cans('view_product'),403);

        $products = Product::select('id', 'name','hsn_sac_code', 'price', 'taxes', 'allow_purchase');

        return DataTables::of($products)
            ->addColumn('action', function ($row) {
                $button = '';
                if ($this->user->cans('edit_product')) {
                    $button .= '<a href="' . route('member.products.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                          data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                if ($this->user->cans('delete_product')) {
                    $button .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $button;
            })
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->editColumn('hsn_sac_code', function ($row) {
                return ($row->hsn_sac_code) ? $row->hsn_sac_code : '--';
            })
            ->editColumn('allow_purchase', function ($row) {
                if ($row->allow_purchase == 1) {
                    return '<label class="label label-success">' . __('app.allowed') . '</label>';
                } else {
                    return '<label class="label label-danger">' . __('app.notAllowed') . '</label>';
                }
            })
            ->editColumn('price', function ($row) {
                return $row->price;
            })
            ->rawColumns(['action', 'allow_purchase'])
            ->make(true);
    }
}
