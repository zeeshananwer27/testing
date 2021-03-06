<?php

namespace Themes\Storefront\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\AdvancedOptions;

class ProductQuickViewController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::findBySlug($slug);
        $advancedOptions = AdvancedOptions::where('product_id',$product->id)->get()->toArray();
        if (setting('reviews_enabled')) {
            $product->load('reviews:product_id,rating');
        }

        return view('public.products.quick_view.show', compact('product', 'advancedOptions'));
    }
    
}
