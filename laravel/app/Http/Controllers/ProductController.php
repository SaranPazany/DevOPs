<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct($productId) {
        $product = Product::find($productId);
        return $product;
    }

    public function getProducts() {
        return Product::all();
    }

    public function createProduct(Request $request) {
        $product = Product::create([
            "name" => $request->name,
            "category_id" => $request->category_id,
            "pricing" => $request->pricing,
        ]);
        $product->save();
        return $product;
    }

    public function updateProduct(Request $request, $productId) {
        $product = Product::find($productId);

        if(!$product) {
            print("Product $productId not found");
            return;
        }

        if($request->name) {
            $product->name = $request->name;
        }
        if($request->pricing) {
            $product->pricing = $request->pricing;
        }
        if($request->category_id) {
            $product->category_id = $request->category_id;
        }

        $product->save();
        return $product;
    }

    public function deleteProduct($productId) {
        $product = Product::find($productId);
        $product->delete();
        return $product;
    }

}
