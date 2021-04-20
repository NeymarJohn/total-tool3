<?php

namespace App\Http\Controllers\Member;

use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Product;
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
        if (!$this->user->can('view_product')) {
            abort(403);
        }

        $this->totalProducts = Product::count();
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
        if (!$this->user->can('add_product')) {
            abort(403);
        }

        $this->taxes = Tax::all();
        return view('member.products.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        // check user permission for this action
        if (!$this->user->can('add_product')) {
            abort(403);
        }

        $products = new Product();
        $products->name = $request->name;
        $products->hsn_sac_code = $request->hsn_sac_code;
        $products->price = $request->price;
        $products->taxes = $request->tax ? json_encode($request->tax) : null;
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
        if (!$this->user->can('edit_product')) {
            abort(403);
        }

        $this->product = Product::find($id);
        $this->taxes = Tax::all();

        return view('member.products.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProductRequest $request, $id)
    {
        // check user permission for this action
        if (!$this->user->can('edit_product')) {
            abort(403);
        }

        $products = Product::find($id);
        $products->name = $request->name;
        $products->price = $request->price;
        $products->hsn_sac_code = $request->hsn_sac_code;
        $products->description = $request->description;
        $products->taxes = $request->tax ? json_encode($request->tax) : null;
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
        if (!$this->user->can('delete_product')) {
            abort(403);
        }

        Product::destroy($id);
        return Reply::success(__('messages.productDeleted'));
    }

    /**
     * @return mixed
     */
    public function data()
    {
        // check user permission for this action
        if (!$this->user->can('view_product')) {
            abort(403);
        }

        $products = Product::select('id','hsn_sac_code', 'name', 'price', 'taxes')
            ->get();

        return DataTables::of($products)
            ->addColumn('action', function ($row) {
                $button = '';
                if ($this->user->can('edit_product')) {
                    $button .= '<a href="' . route('member.products.edit', [$row->id]) . '" class="btn btn-info btn-circle"
                          data-toggle="tooltip" data-original-title="Edit"><i class="fa fa-pencil" aria-hidden="true"></i></a>';
                }
                if ($this->user->can('delete_product')) {
                    $button .= ' <a href="javascript:;" class="btn btn-danger btn-circle sa-params"
                      data-toggle="tooltip" data-user-id="' . $row->id . '" data-original-title="Delete"><i class="fa fa-times" aria-hidden="true"></i></a>';
                }
                return $button;
            })
            ->editColumn('name', function ($row) {
                return ucfirst($row->name);
            })
            ->editColumn('price', function ($row) {
                if (!is_null($row->taxes)) {
                    $totalTax = 0;
                    foreach (json_decode($row->taxes) as $tax) {
                        $this->tax = Product::taxbyid($tax)->first();
                        $totalTax = $totalTax + ($row->price * ($this->tax->rate_percent / 100));
                    }
                    return $this->global->currency->currency_symbol . ($row->price + $totalTax);
                } else {
                    return $this->global->currency->currency_symbol . $row->price;
                }
            })
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
    }
}
