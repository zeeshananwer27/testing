<style>
@media screen and (max-width: 767px){
    .customs-row{
        margin-right: 0 !important;
        margin-left: 0 !important;
    }
   .content-wrapper{
        padding-top:0px !important;
    }
}

@media screen and (min-width: 767px){
    .customs-row{
        margin-right: 165px !important;
        margin-left: 207px !important;
    }
    .content-wrapper {
        padding-top:15px !important;
    }
}


</style>


@extends('public.layout')

@section('title', trans('storefront::account.view_order.view_order'))

@section('breadcrumb')
    <li><a href="{{ route('account.dashboard.index') }}">{{ trans('storefront::account.links.my_account') }}</a></li>
    <li><a href="{{ route('account.orders.index') }}">{{ trans('storefront::account.links.my_orders') }}</a></li>
    <li class="active">{{ trans('storefront::account.orders.view_order') }}</li>
@endsection

@section('content')
    <div class="orders-view-wrapper clearfix ">
        <div class="row customs-row" >
            <h3>{{ trans('storefront::account.view_order.view_order') }}</h3>

            <div class="col-md-6 col-sm-6">
                <div class="order-details">
                    <h5>{{ trans('storefront::account.view_order.order_details') }}</h5>
 
 
                    <div class="table-responsive">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>{{ trans('storefront::account.view_order.telephone') }}:</td>
                                    <td>{{ $order->customer_phone ?: trans('storefront::account.view_order.n/a') }}</td>
                                </tr>

                                <tr>
                                    <td>{{ trans('storefront::account.view_order.email') }}:</td>
                                    <td>{{ $order->customer_email }}</td>
                                </tr>

                                <tr>
                                    <td>{{ trans('storefront::account.view_order.date') }}:</td>
                                    <td>{{ $order->created_at->toFormattedDateString() }}</td>
                                </tr>

                                <tr>
                                    <td>{{ trans('storefront::account.view_order.shipping_method') }}:</td>
                                    <td>{{ $order->shipping_method }}</td>
                                </tr>

                                <tr>
                                    <td>{{ trans('storefront::account.view_order.payment_method') }}:</td>
                                    <td>{{ $order->payment_method }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row customs-row" >
            <div class="col-md-6 col-sm-6">
                <div class="order-address">
                    <h5>{{ trans('storefront::account.view_order.billing_address') }}</h5>
                    <span>{{ $order->billing_full_name }}</span>
                    <span>{{ $order->billing_address_1 }}</span>
                    <span>{{ $order->billing_address_2 }}</span>
                    <span>{{ $order->billing_city }}, {{ $order->billing_state_name }} {{ $order->billing_zip }}</span>
                    <span>{{ $order->billing_country_name }}</span>
                </div>
            </div>

            <div class="col-md-6 col-sm-6">
                <!--<div class="order-address">
                    <h5>{{ trans('storefront::account.view_order.shipping_address') }}</h5>
                    <span>{{ $order->shipping_full_name }}</span>
                    <span>{{ $order->shipping_address_1 }}</span>
                    <span>{{ $order->shipping_address_2 }}</span>
                    <span>{{ $order->shipping_city }}, {{ $order->shipping_state_name }} {{ $order->shipping_zip }}</span>
                    <span>{{ $order->shipping_country_name }}</span>
                </div>-->
            </div>
        </div>
        
        <?php 
            $dir =  public_path('/storage/tickets');
           
            $dh  = opendir($dir);
            $files = array(); 
            while (false !== ($filename = readdir($dh))) {

            $exp = explode('.',$filename);
            $expo = explode('_',$exp[0]);

            if(isset($expo[1])){
            if($expo[1] == $order->id){
            $files[] = $filename;  

            }
            }
            }
            ?>

        <div class="clearfix"></div>
        <div class="row customs-row" >
        <?php if(!empty($files) ){ 
            foreach($files as $file ){ ?>    
                <tr style="border-top: 1px solid #e9e9e9;">
                    <td style="border-collapse: collapse; width: 300px; margin-top: 10px; float: left;">
                        <img style = "width:200px; height:200"  src= "<?php echo url('/storage/tickets/'.$file)?>"/>
                    </td>
                </tr>
            <?php } } ?>
        </div>
        <div class="col-md-10 col-md-offset-1">
            <div class="row">
                <div class="cart-list">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="hidden-xs">
                                <tr>
                                    <th>{{ trans('storefront::account.view_order.product') }}</th>
                                    <th>{{ trans('storefront::account.view_order.unit_price') }}</th>
                                    <th>{{ trans('storefront::account.view_order.quantity') }}</th>
                                    <th>{{ trans('storefront::account.view_order.line_total') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->products as $product)
                                    <tr>
                                        
                                        <td>
                                            <h5>
                                                <a href="{{ route('products.show', ['slug' => $product->slug]) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </h5>

                                            @if ($product->hasAnyOption())
                                                <div class="option">
                                                    @foreach ($product->options as $option)
                                                        <span>{{ $option->name }}: <span>{{ $option->values->implode('label', ', ') }}</span></span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>

                                         <?php
                                               
                                                $orderId = $product->order_id;
                                                $opt = Illuminate\Support\Facades\DB::table('orders')->where('id', $orderId)->first()->options_data;
                                                
                                                
                                                $productId = $product->product_id;
                                                $optionIds = Illuminate\Support\Facades\DB::table('advanced_options')->select('id')->where('product_id', $productId)->get();
                                                
                                                $Ids = [];
                                                
                                                foreach($optionIds as $d) :
                                                
                                                    array_push($Ids, $d->id);
                                                endforeach;
                                                
                                                $optionsData = json_decode($opt);
                                                
                                                $quantity = 0;
                                                $price = 0;
                                                $total = 0;
                                                foreach($optionsData as  $key => $option) :
                                                    $option = (array) $option;
                                                    $option_id = $option['option_id'];
                                                    if(in_array($option['option_id'], $Ids)) :
                                                        $quantity = $option['quantity'];
                                                        $price =  $option['price'];
                                                        $total += $quantity * $price;
                                                    endif;
                                                endforeach; ?>
                                        
                                        <td>
                                           
                                            <label>{{ trans('storefront::account.view_order.unit_price') }}:</label>
                                            <!--<span>{{ $product->unit_price->convert($order->currency, $order->currency_rate)->format($order->currency) }}</span>-->
                                             <!--{{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}-->
                                            <span>{{ $total }}</span>
                                        </td>

                                        <td>
                                            <?php
        
                                                foreach($optionsData as  $key => $option) :
                                                    
                                                    $option = (array) $option;
                                                    $option_id = $option['option_id'];
                                                    $html = '';
                                                    
                                                    if(in_array($option['option_id'], $Ids)) :
                                                    
                                                        $html .= "<span style='padding: 5px;'> <b> Label : </b> " . $option['label'] . "</span>";
                                                        $html .= "<span style='padding: 5px;'> <b> Quantity : </b> " . $option['quantity'] . "</span>";
                                                        $html .= "<span style='padding: 5px;'> <b> Price : </b> " . $option['price'] . "</span><br>";
                                                    endif;
                                    
                                            ?>
                                            <label>{{ trans('storefront::account.view_order.quantity') }}:</label>
                                            <span><?= $html ?></span>
                                            <?php endforeach; ?>
                                        </td>

                                        <td>
                                            <label>{{ trans('storefront::account.view_order.line_total') }}:</label>
                                            <!--<span>{{ $product->line_total->convert($order->currency, $order->currency_rate)->format($order->currency) }}</span>-->
                                             <!--{{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}-->
                                                 {{ $total }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="total-wrapper pull-right">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>{{ trans('storefront::account.view_order.subtotal') }}</td>
                                <td>{{ $order->sub_total->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                            </tr>

                            @if ($order->hasShippingMethod())
                                <tr>
                                    <td>{{ $order->shipping_method }}</td>
                                    <td>{{ $order->shipping_cost->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                                </tr>
                            @endif

                            @if ($order->hasCoupon())
                                <tr>
                                    <td>{{ trans('storefront::account.view_order.coupon') }} (<span class="coupon-code">{{ $order->coupon->code }}</span>)</td>
                                    <td>&#8211;{{ $order->discount->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                                </tr>
                            @endif

                            @foreach ($order->taxes as $tax)
                                <tr>
                                    <td>{{ $tax->name }}</td>
                                    <td>{{ $tax->order_tax->amount->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>{{ trans('storefront::account.view_order.total') }}</td>
                                <td>{{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
