<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    /**
     * getAllSubCategories
     *
     * @param  string $lang
     * @return JsonResponse
     */
    public function getAllSubCategories(string $lang) : JsonResponse
    {
        $dataSubcategories = [];

        $subcategories = Subcategory::select('id', 'name', 'image', 'category_id')
        ->with('translations', 'category.translations')
        ->where([
            ['level', 1],
            ['parent_id', NULL],
        ])
        ->get();

        foreach ($subcategories as $sub) {
            $thisSubcategory['id'] = $sub->id;

            if (isset($sub->translations)) {
                foreach ($sub->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['title'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            $path_parent = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->category->name)));
            $path_sub = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->name)));

            $thisSubcategory['path'] = '/' . $path_parent . '/' . $path_sub;
            $thisSubcategory['image'] = $sub->image;

            if (isset($sub->category->translations)) {
                foreach ($sub->category->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['parent'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            $thisSubcategory['category_id'] = $sub->category_id;

            array_push($dataSubcategories, $thisSubcategory);
        }

        return response()->json($dataSubcategories);
    }

    /**
     * getAllChildSubCategories
     *
     * @param  mixed $lang
     * @return JsonResponse
     */
    public function getAllChildSubCategories(string $lang) : JsonResponse
    {
        $dataChildSubcategories = [];

        $childSubcategories = Subcategory::select('id', 'name', 'image', 'category_id', 'parent_id', 'level')
        ->with('translations', 'category.translations', 'parent.translations')
        ->where([
            ['level', '!=', 1],
            ['parent_id', '!=', NULL],
        ])
        ->get();


        foreach ($childSubcategories as $sub) {
            $thisSubcategory['id'] = $sub->id;

            if (isset($sub->translations)) {
                foreach ($sub->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['title'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            $path_parent = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->category->name)));
            $parent_sub = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->parent->name)));
            $path_sub = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->name)));

            $thisSubcategory['path'] = '/' . $path_parent . '/' . $parent_sub . '/' . $path_sub;
            $thisSubcategory['image'] = $sub->image;

            if (isset($sub->category->translations)) {
                foreach ($sub->category->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['parent'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            if (isset($sub->parent->translations)) {
                foreach ($sub->parent->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['route'] = $thisSubcategory['parent'] . ' > ' . $tr->name . ' > ' . $thisSubcategory['title'];
                    }
                }
            }

            $thisSubcategory['category_id'] = $sub->category_id;

            array_push($dataChildSubcategories, $thisSubcategory);
        }

        return response()->json($dataChildSubcategories);
    }

    /**
     * getSubcategories
     *
     * @param  int $id
     * @param  string $lang
     * @return JsonResponse
     */
    public function getSubcategories(int $id, string $lang): JsonResponse
    {
        $dataSubcategories = [];

        $categoryImage = Category::where('id', $id)->pluck('bg_image')[0];

        $subcategories = Subcategory::select('id', 'name', 'image', 'category_id')
        ->with('translations', 'category.translations')
        ->where([
            ['category_id', $id],
            ['level', 1],
            ['parent_id', NULL],
        ])
        ->get();

        foreach ($subcategories as $sub) {

            $thisSubcategory['id'] = $sub->id;

            if (isset($sub->translations)) {
                foreach ($sub->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['title'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            $path_parent = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->category->name)));
            $path_sub = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->name)));

            $thisSubcategory['path'] = '/' . $path_parent . '/' . $path_sub;
            $thisSubcategory['image'] = $sub->image;

            if (isset($sub->category->translations)) {
                foreach ($sub->category->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['parent'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            $thisSubcategory['category_id'] = $sub->category_id;

            array_push($dataSubcategories, $thisSubcategory);
        }

        return response()->json([
            'bg_image' => $categoryImage,
            'data' => $dataSubcategories
        ]);
    }

    /**
     * getSubcategoryProducts
     *
     * @param  int $id
     * @param  string $lang
     * @return JsonResponse
     */
    public function getSubcategoryProducts(int $id, string $lang): JsonResponse
    {
        $dataProducts = [];

        $products = Product::with('translations', 'images', 'subcategory.translations')
            ->whereHas('subcategory', function ($query) use ($id) {
                $query->where('subcategories.id', $id);
            })
            ->get();

        foreach ($products as $product) {
            $thisProduct['id'] = $product->id;

            if (isset($product->translations)) {
                foreach ($product->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisProduct['title'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            foreach ($product->subcategory as $key => $cat) {
                if ($cat->id != $id) {
                    unset($product->subcategory[$key]);
                } else {
                    if (isset($product->subcategory[$key]->translations)) {
                        foreach ($product->subcategory[$key]->translations as $tr) {
                            if ($tr->language == $lang) {
                                $thisProduct['parent'] = ucwords(strtolower($tr->name));
                            }
                        }
                    }
                }
            }

            $thisProduct['path'] = '/product/' . $product->id;
            $thisProduct['images'] = [];
            foreach ($product->images as $image) {
                array_push($thisProduct['images'], $image->filename);
            }

            array_push($dataProducts, $thisProduct);
        }

        return response()->json($dataProducts);
    }

    /**
     * getChildSubcategories
     *
     * @param  mixed $id
     * @param  mixed $lang
     * @return JsonResponse
     */
    public function getChildSubcategories (int $id, string $lang) : JsonResponse
    {
        $dataChildSubcategories = [];

        $childSubcategories = Subcategory::select('id', 'name', 'image', 'category_id', 'parent_id', 'level')
        ->with('translations', 'category.translations', 'parent.translations')
        ->where([
            ['parent_id', $id],
            ['level', 2],
        ])
        ->get();

        $categoryImage = Subcategory::where('id', $id)->pluck('bg_image')[0];


        foreach ($childSubcategories as $sub) {
            $thisSubcategory['id'] = $sub->id;

            if (isset($sub->translations)) {
                foreach ($sub->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['title'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            $path_parent = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->category->name)));
            $parent_sub = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->parent->name)));
            $path_sub = str_replace(' ', '_',  str_replace(',', '', strtolower($sub->name)));

            $thisSubcategory['path'] = '/' . $path_parent . '/' . $parent_sub . '/' . $path_sub;
            $thisSubcategory['image'] = $sub->image;

            if (isset($sub->category->translations)) {
                foreach ($sub->category->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['parent'] = ucwords(strtolower($tr->name));
                    }
                }
            }

            if (isset($sub->parent->translations)) {
                foreach ($sub->parent->translations as $tr) {
                    if ($tr->language == $lang) {
                        $thisSubcategory['route'] = $thisSubcategory['parent'] . ' > ' . $tr->name . ' > ' . $thisSubcategory['title'];
                    }
                }
            }

            $thisSubcategory['category_id'] = $sub->category_id;


            array_push($dataChildSubcategories, $thisSubcategory);

        }

        return response()->json([
            'bg_image' => $categoryImage,
            'data' => $dataChildSubcategories
        ]);
    }
}
