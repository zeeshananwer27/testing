<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\AdvancedOptions;
use Modules\Product\Events\ProductViewed;
use Modules\Product\Filters\ProductFilter;
use Modules\Product\Events\ShowingProductList;
use Modules\Product\Http\Middleware\SetProductSortOption;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(SetProductSortOption::class)->only('index');
    }



    /**
     * Display a listing of the resource.
     *
     * @param \Modules\Product\Entities\Product $model
     * @param \Modules\Product\Filters\ProductFilter $productFilter
     * @return \Illuminate\Http\Response
     */
    public function index(Product $model, ProductFilter $productFilter)
    {
        $productIds = [];

        if (request()->has('query')) {

            $model = $model->search(request('query'));

            $productIds = $model->keys();
        }

        if (request()->has('city')) {


            $id_for_cities = DB::table('products')->join('product_translations', 'products.id', '=', 'product_translations.product_id')
            ->where('products.deleted_at', NULL)->whereIn('products.is_active' ,['1'])->where('city', '=', request('city'))->select('product_translations.product_id')->get()->toArray();


            $p_ids = array();

            foreach($id_for_cities as $id_city){
                array_push($p_ids, $id_city->product_id);
                $id_city->product_id;
            }

            $p_ids = array_unique($p_ids);

            $productIds = $p_ids;
            // echo "<pre>"; print_r(request()->query()); exit('manes');

        }

        $query = $model->filter($productFilter);


        $products = $query->paginate(request('perPage', 15))
            ->appends(request()->query());


        if (request()->wantsJson()) {
            return response()->json($products);
        }

        event(new ShowingProductList($products));

        return view('public.products.index', compact('products', 'productIds'));


    }

    // function to get lat /long of given location

    public function location_search($location){
        $google_map_key=\DB::table('settings')->where('key','google_map_key')->select('plain_value')->first()->plain_value;
        // $location = 'lahore';
        $apiKey = "AIzaSyC4ZnhpJNqTbOjYU0UYxWMjWHRLsgMhzX8";
        $address = $location;
        $url = "https://maps.google.com/maps/api/geocode/json?address=".urlencode($address)."&sensor=false&key=".$apiKey;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $responseJson = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($responseJson);
        // echo "<pre>"; print_r($response); exit('check');
        $res = array();
        if ($response->status == 'OK') {
            $latitude = $response->results[0]->geometry->location->lat;
            $longitude = $response->results[0]->geometry->location->lng;
            $res['status']      = $response->status;
            $res['latitude']    = $latitude;
            $res['longitude']   = $longitude;
            // echo "<pre>"; print_r($res); exit('here');
            }
        else{
            $res['status']      = $response->status;
            $res['error']       = 'Not available';
            }
        return $res;
    }


    /**
     * Show the specified resource.
     *
     * @param string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::findBySlug($slug);
       if(empty($product->sitting_arrangement) || count(json_decode($product->sitting_arrangement))==0 ){
            $sitting_check=0;
        }else{
            $sitting_check=1;
        }
        $advancedOptions = AdvancedOptions::where('product_id',$product->id)->get()->toArray();
        $reservedSeats = DB::table('orders')
            ->join('order_products', 'orders.id', '=', 'order_products.order_id')
            ->select('orders.seats_data')->where('order_products.product_id',$product->id)->get();

        $relatedProducts = $product->relatedProducts()->forCard()->get();
        $upSellProducts = $product->upSellProducts()->forCard()->get();
        $reviews = $this->getReviews($product);

        if (setting('reviews_enabled')) {
            $product->load('reviews:product_id,rating');
        }

        event(new ProductViewed($product));

        return view('public.products.show', compact('product', 'relatedProducts', 'upSellProducts', 'reviews', 'advancedOptions','reservedSeats','sitting_check'));
    }

    /**
     * Get reviews for the given product.
     *
     * @param \Modules\Product\Entities\Product $product
     * @return \Illuminate\Support\Collection
     */
    private function getReviews($product)
    {
        if (! setting('reviews_enabled')) {
            return collect();
        }

        return $product->reviews()->paginate(15, ['*'], 'reviews');
    }
}
