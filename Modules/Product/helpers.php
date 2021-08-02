<?php

use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Auth;
use Modules\User\Entities\User;
use Modules\Order\Entities\OrderProduct;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\UserEvents;
if (! function_exists('product_price')) {
    /**
     * Get the selling price of the given product.
     *
     * @param \Modules\Product\Entities\Product $product
     * @param string $class
     * @return \Illuminate\Support\HtmlString|string
     */
    function product_price($product, $class = 'previous-price')
    {
        if (! $product->hasSpecialPrice()) {
            return $product->price->convertToCurrentCurrency()->format();
        }

        $price = $product->price->convertToCurrentCurrency()->format();
        $specialPrice = $product->special_price->convertToCurrentCurrency()->format();

        return new HtmlString("{$specialPrice} <span class='{$class}'>{$price}</span>");
    }

}

if (! function_exists('validate_events')) {
    function validate_events(){
        if($user = Auth::user()){
            $user_id = $user->toArray()['id'];
            $all = User::where('id', $user_id)->get()->first();
            return $all->events->pluck('id')->toArray();
        }
    }
}    

if (! function_exists('validate_orders')) {
    function validate_orders(){
        if($user = Auth::user()){
            $user_id = $user->toArray()['id'];
            $all = User::where('id', $user_id)->get()->first();
            $events = $all->events->pluck('id')->toArray();
            $orders = OrderProduct::whereIn('product_id', $events)->get()
                        ->pluck('order_id')->toArray();
            return $orders;
        }
    }
}   

if (! function_exists('valid_users')) {
    function valid_users($ids = ''){

        if($user = Auth::user()){
           if($ids){
                return User::adminStaffList($ids);
           }else{
                return User::adminStaffList();
           }
            
            // return $orders;
        }
    }
}

if (! function_exists('event_users')) {
    function event_users($ids = ''){

       if($ids){
            return UserEvents::where('product_id',$ids)->get();
                        // ->mapWithKeys(function ($user) {
                        //     return [$user->user_id];
                        // }); 
            // return User::adminStaffList($ids);
       }else{
            return UserEvents::select('id', 'first_name', 'last_name')->get()
                ->mapWithKeys(function ($user) {
                    return [$user->id => $user->first_name.' '.  $user->last_name];
                }); 
            // return User::adminStaffList();
       }
            
            // return $orders;

    }
}    

// list