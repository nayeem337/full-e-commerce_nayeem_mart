<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\OtherImage;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;


    public function index()
    {
        // Pass data to the view categories, sub_categories, brands, units
        return view('admin.product.index', [

            // Fetch all categories, sub_categories, brands, units
            'categories'      =>  Category::all(),
            'sub_categories'  =>  SubCategory::all(),
            'brands'          =>  Brand::all(),
            'units'           =>  Unit::all(),
        ]);
    }


    //for when select category then auto select sub_category like Men's cloths-T-Shirt
    public function getSubCategoryByCategory()
    {
        return response()->json(SubCategory::where('category_id', $_GET['id'] )->get());
    }

    public function create(Request $request)
    {
        $this->product = Product::newProduct($request);
        OtherImage::newOtherImage($request->other_image, $this->product->id);
        return back()->with('message', 'Product info create successfully.');
    }

    public function manage()
    {
        return view('admin.product.manage' , ['products' => Product::all()]);
    }

    public function detail($id)
    {
        return view('admin.product.detail' , ['product' => Product::find($id)]);
    }

    public function edit($id)
    {
        return view('admin.product.edit', [

            //for value like : category-name
            'categories'      =>  Category::all(),
            'sub_categories'  =>  SubCategory::all(),
            'brands'          =>  Brand::all(),
            'units'           =>  Unit::all(),

            // for edit
            'product' => Product::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
         Product::updateProduct($request, $id);
        //for OtherImage Update
        if ($request->other_image)
        {
            OtherImage::updateOtherImage($request->other_image, $id);
        }

        return redirect('/product/manage')->with('message', 'Product info update successfully.');
    }


    public function delete($id)
    {
        Product::deletePtroduct($id);

        //for delete product OtherImage
        OtherImage::deleteOtherImage($id);

        return redirect('/product/manage')->with('message', 'Product info delete successfully.');
    }

}
