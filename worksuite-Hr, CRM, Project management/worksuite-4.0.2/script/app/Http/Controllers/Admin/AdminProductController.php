<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\ProductsDataTable;
use App\Helper\Reply;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Product;
use App\ProductCategory;
use App\ProductSubCategory;
use App\Tax;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class AdminProductController extends AdminBaseController
{
    /**
     * AdminProductController constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->pageTitle = 'app.menu.products';
        $this->pageIcon = 'icon-basket';
    }

    public function index(ProductsDataTable $dataTable)
    {
        $this->totalProducts = Product::count();
        $this->categories = ProductCategory::all();
        $this->subCategories = ProductSubCategory::all();
        return $dataTable->render('admin.products.index', $this->data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->taxes = Tax::all();
        $this->categories = ProductCategory::all();
        $this->subCategories = ProductSubCategory::all();
        return view('admin.products.create', $this->data);
    }

    /**
     * @param StoreProductRequest $request
     * @return array
     */
    public function store(StoreProductRequest $request)
    {
        $products = new Product();
        $products->name = $request->name;
        $products->price = $request->price;
        $products->taxes = $request->tax ? json_encode($request->tax) : null;
        $products->hsn_sac_code = $request->hsn_sac_code;
        $products->description = $request->description;
        $products->allow_purchase = ($request->purchase_allow == 'no') ? true : false;
        $products->category_id = ($request->category_id) ? $request->category_id : null;
        $products->sub_category_id = ($request->sub_category_id) ? $request->sub_category_id : null;
        $products->save();

        return Reply::redirect(route('admin.products.index'), __('messages.productAdded'));
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
        $this->product = Product::find($id);
        $this->taxes = Tax::all();
        $this->categories = ProductCategory::all();
        $this->subCategories = ProductSubCategory::all();

        return view('admin.products.edit', $this->data);
    }

    /**
     * @param UpdateProductRequest $request
     * @param $id
     * @return array
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $products = Product::find($id);
        $products->name = $request->name;
        $products->price = $request->price;
        $products->taxes = $request->tax ? json_encode($request->tax) : null;
        $products->hsn_sac_code = $request->hsn_sac_code;
        $products->description = $request->description;
        $products->allow_purchase = ($request->purchase_allow == 'no') ? true : false;
        $products->category_id = ($request->category_id) ? $request->category_id : null;
        $products->sub_category_id = ($request->sub_category_id) ? $request->sub_category_id : null;

        $products->save();

        return Reply::redirect(route('admin.products.index'), __('messages.productUpdated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return Reply::success(__('messages.productDeleted'));
    }

    public function export() {
        $attributes =  ['tax', 'taxes', 'price'];
        $products = Product::select('id', 'name', 'price')
            ->get()->makeHidden($attributes);;

        // Initialize the array which will be passed into the Excel
        // generator.
        $exportArray = [];

        // Define the Excel spreadsheet headers
        $exportArray[] = ['ID', 'Name', 'Price'];

        // Convert each member of the returned collection into an array,
        // and append it to the payments array.
        foreach ($products as $row) {
            $dataArray = $row->toArray();
            $dataArray['total_amount'] = $this->global->currency->currency_symbol . '' . $dataArray['total_amount'];
            $exportArray[] = $dataArray;
        }

        // Generate and return the spreadsheet
        Excel::create('Product', function ($excel) use ($exportArray) {

            // Set the spreadsheet title, creator, and description
            $excel->setTitle('Product');
            $excel->setCreator('Worksuite')->setCompany($this->companyName);
            $excel->setDescription('Product file');

            // Build the spreadsheet, passing in the payments array
            $excel->sheet('sheet1', function ($sheet) use ($exportArray) {
                $sheet->fromArray($exportArray, null, 'A1', false, false);

                $sheet->row(1, function ($row) {

                    // call row manipulation methods
                    $row->setFont(array(
                        'bold'       =>  true
                    ));
                });
            });
        })->download('xlsx');
    }
}
