<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{

    public function getSingleProduct(int $id,string $lang) : JsonResponse
    {
        $product = Product::with('translations','images','links','files')->find($id);

        if (!$product) {
            return response()->json(
                data:[
                    'success' => false,
                    'message' => 'Product not found',
                ],
                status: JsonResponse::HTTP_NOT_FOUND
            );
        }

        $dataProduct = [];

        if (isset($product->translations)) {
            foreach ($product->translations as $tr) {
                if ( $tr->language == $lang  ) {
                    $dataProduct['title'] = $tr->name;
                    $dataProduct['description'] = $tr->description;
                    $dataProduct['text'] = $tr->text;
                }
            }
        }

        $dataProduct['created_at'] = $product->created_at->format('d-m-Y') ;
        $dataProduct['updated_at'] = $product->updated_at->format('d-m-Y') ;

        $dataProduct['images'] = [];
        foreach ($product->images as $image ) {
            array_push($dataProduct['images'], $image->filename);
        }

        $dataProduct['files'] = [];
        foreach ($product->files as $file ) {
            array_push($dataProduct['files'], $file->path);
        }

        $dataProduct['links'] = [];
        foreach ($product->links as $image ) {
            array_push($dataProduct['links'], $image->path);
        }

        return response()->json($dataProduct);
    }
}
