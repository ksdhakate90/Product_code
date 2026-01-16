<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductBrand;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductController extends Controller
{

public function store(Request $request)
{
   $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'brands' => 'required|array|min:1',
        'brands.*.brand_name' => 'required|string|max:255',
        'brands.*.detail' => 'required|string',
        'brands.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        'brands.*.price' => 'required|numeric|min:0',
    ]);

    DB::beginTransaction();

    try {
        $product = Product::create([
            'seller_id' => $request->user()->id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        foreach ($request->brands as $brand) {
            $imagePath = $brand['image']->store('brands', 'public');

            ProductBrand::create([
                'product_id' => $product->id,
                'brand_name' => $brand['brand_name'],
                'detail' => $brand['detail'],
                'image' => $imagePath,
                'price' => $brand['price'],
            ]);
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Product added successfully.',
            'data' => $product->load('brands'),
            'code' => "201"
        ], 201);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'success' => false,
            'message' => 'Failed to add product.',
            'error' => $e->getMessage()
        ], 500);
    }
}



/*  product list */

public function index(Request $request)
{
    $perPage = $request->get('per_page', 10);

    $products = Product::with('brands')
        ->where('seller_id', $request->user()->id)
        ->orderBy('id', 'desc')
        ->paginate($perPage);

    return response()->json([
        'success' => true,
        'message' => 'Product list fetched successfully.',
        'data' => $products,
        'code' => '200'
    ], 200);
}

/*  product PDf */


public function viewPdf($id, Request $request)
{
    $product = Product::with('brands')
        ->where('id', $id)
        ->where('seller_id', $request->user()->id)
        ->firstOrFail();

    $products = ProductBrand::select('brand_name')
    ->selectRaw('SUM(price) as total_price')
    ->groupBy('brand_name')
    ->get();

    $totalPrice = $products;

    $pdf = Pdf::loadView('pdf.product', compact('product', 'totalPrice'));

    return $pdf->stream('product.pdf');
}


/*   Delete Product*/
public function destroy($id, Request $request)
{
    try {
        $product = Product::where('id', $id)
            ->where('seller_id', $request->user()->id)
            ->firstOrFail();

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully.',
            'code' => '204',
            // 'error' => $e->getMessage()
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found or unauthorized.'
        ], 404);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to delete product.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}