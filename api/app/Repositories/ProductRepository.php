<?php

namespace App\Repositories;

use App\Models\File;
use App\Models\Link;
use App\Models\Product;
use App\Models\ProductImages;
use App\Services\FileServices;
use App\Models\CategoryProduct;
use App\Models\ProductSubcategory;
use Illuminate\Support\Facades\DB;
use App\Models\ProductTranslations;

class ProductRepository
{

    /**
     * fileServices
     *
     * @var FileServices
     */
    private $fileServices;

    /**
     * __construct
     *
     * @param  FileServices $fileServices
     * @return void
     */
    public function __construct(
        FileServices $fileServices
    ) {
        $this->fileServices = $fileServices;
    }

    /**
     * create
     *
     * @param  mixed $data
     * @return void
     */
    public function create($data)
    {
        DB::beginTransaction();

        $product = Product::create([
            'name' => $data['en_name'],
            'description' => $data['en_description'],
            'text' => $data['en_text'],
        ]);

        ProductTranslations::insert([
            [
                'product_id' => $product->id,
                'language' => 'en',
                'name' => $data['en_name'],
                'description' => trim($data['en_description']),
                'text' => trim($data['en_text']),
            ],
            [
                'product_id' => $product->id,
                'language' => 'am',
                'name' => $data['am_name'],
                'description' => trim($data['am_description']),
                'text' => trim($data['am_description']),
            ]
        ]);

        if ( isset($data['images']) ) {
            foreach ($data['images'] as $key => $image) {
                $imageFileName = rand(1000000, 99999999999) . '_product_' . $product->id . '_image_' . $key + 1 . '.' . strtolower($image->getClientOriginalExtension());

                $path = $this->fileServices->savePhoto(1000, $image, 'products/' . $product->id, $imageFileName);

                ProductImages::create([
                    'product_id' => $product->id,
                    'filename' => $path,
                ]);
            }
        }

        if ( isset($data['files']) ) {
            foreach ($data['files'] as $key => $file) {
                $fileName = rand(1000000, 99999999999) . '_file_' . $key + 1 . '.' . strtolower($file->getClientOriginalExtension());
                $path = $this->fileServices->saveFile($file, 'products/' . $product->id . '/files', $fileName);

                File::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        if ( isset($data['categories']) ) {
            foreach ( $data['categories'] as $catergory ) {
                CategoryProduct::create([
                    'category_id' => $catergory,
                    'product_id' => $product->id
                ]);
            }
        }

        if ( isset($data['subcategories']) ) {
            foreach ( $data['subcategories'] as $subcategory) {
                ProductSubcategory::create([
                    'subcategory_id' => $subcategory,
                    'product_id' => $product->id
                ]);
            }
        }

        if ( isset($data['links']) ) {
            foreach ( $data['links'] as $link) {
                if (!empty($link)) {
                    $link = str_replace('watch?v=', 'embed/', $link);

                    Link::create([
                        'product_id' => $product->id,
                        'path' => $link,
                    ]);
                }
            }
        }

        DB::commit();

        return $product;
    }

    /**
     * update
     *
     * @param  mixed $product
     * @param  mixed $data
     * @return void
     */
    public function update ($product, $data)
    {
        DB::beginTransaction();

        $product->update([
            'name' => $data['en_name'],
            'description' => $data['en_description'],
            'text' => $data['en_text']
        ]);

        ProductTranslations::where('product_id', $product->id)->delete();

        ProductTranslations::insert([
            [
                'product_id' => $product->id,
                'language' => 'en',
                'name' => $data['en_name'],
                'description' => trim($data['en_description']),
                'text' => trim($data['en_text']),
            ],
            [
                'product_id' => $product->id,
                'language' => 'am',
                'name' => $data['am_name'],
                'description' => trim($data['am_description']),
                'text' => trim($data['am_text']),
            ]
        ]);

        if ( isset($data['images']) ) {
            foreach ($data['images'] as $key => $image) {
                $imageFileName = rand(1000000, 99999999999) . '_product_' . $product->id . '_image_' . $key + 1 . '.' . strtolower($image->getClientOriginalExtension());

                $path = $this->fileServices->savePhoto(1000, $image, 'products/' . $product->id, $imageFileName);

                ProductImages::create([
                    'product_id' => $product->id,
                    'filename' => $path,
                ]);
            }
        }

        if ( isset($data['files']) ) {
            foreach ($data['files'] as $key => $file) {
                $fileName = rand(1000000, 99999999999) . '_file_' . $key + 1 . '.' . strtolower($file->getClientOriginalExtension());
                $path = $this->fileServices->saveFile($file, 'products/' . $product->id . '/files', $fileName);

                File::create([
                    'product_id' => $product->id,
                    'path' => $path,
                ]);
            }
        }

        if ( isset($data['categories']) ) {
            CategoryProduct::where('product_id', $product->id)->delete();
            foreach ( $data['categories'] as $catergory ) {
                CategoryProduct::create([
                    'category_id' => $catergory,
                    'product_id' => $product->id
                ]);
            }
        }

        if ( isset($data['subcategories']) ) {
            ProductSubcategory::where('product_id', $product->id)->delete();
            foreach ( $data['subcategories'] as $subcategory) {
                ProductSubcategory::create([
                    'subcategory_id' => $subcategory,
                    'product_id' => $product->id
                ]);
            }
        }

        if ( isset($data['links']) ) {
            Link::where('product_id', $product->id)->delete();
            foreach ( $data['links'] as $link) {
                if (!empty($link)) {
                    $link = str_replace('watch?v=', 'embed/', $link);
                    Link::create([
                        'product_id' => $product->id,
                        'path' => $link,
                    ]);
                }
            }
        }

        DB::commit();

        return $product;
    }

    /**
     * delete
     *
     * @param  mixed $product
     * @return void
     */
    public function delete($product)
    {
        DB::beginTransaction();

        ProductTranslations::where('product_id', $product->id)->delete();
        CategoryProduct::where('product_id', $product->id)->delete();
        ProductSubcategory::where('product_id', $product->id)->delete();
        ProductImages::where('product_id', $product->id)->delete();
        Link::where('product_id', $product->id)->delete();
        $product->delete();

        DB::commit();

        return $product;
    }
}
