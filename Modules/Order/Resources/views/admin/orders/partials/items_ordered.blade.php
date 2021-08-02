@php $fee_total = 0 ; 
$sub_total = 0;
@endphp 
<div class="items-ordered-wrapper">
    <h3 class="section-title">{{ trans('order::orders.items_ordered') }}</h3>

    <div class="row">
        <div class="col-md-12">
            <div class="items-ordered">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{ trans('order::orders.product') }}</th>
                                <th>{{ trans('order::orders.unit_price') }}</th>
                                <th>{{ trans('order::orders.charges_per_unit') }}</th>
                                <th>{{ trans('order::orders.quantity') }}</th>
                                <th>{{ trans('order::orders.line_total') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            
                            @foreach ($order->products as $product)
                            @php $line_total = 0 ; @endphp 
                                <tr>
                                    <td>
                                        @if ($product->trashed())
                                            {{ $product->name }}
                                        @else
                                            <a href="{{ route('admin.products.edit', $product->product->id) }}">{{ $product->name }}</a>
                                        @endif
                                        <br>{{ $product->product->organizer }}

                                        @if ($product->hasAnyOption())
                                            <br>
                                            @foreach ($product->options as $option)
                                                <span>
                                                    {{ $option->name }}:
                                                    <span>{{ $option->values->implode('label', ', ') }}</span>
                                                </span>
                                            @endforeach
                                        @endif
                                    </td>
                                    
                            <td>
                                <?php
                                    $user = DB::table('orders')->where('id',$order->id)->get();
                                    $res=$user[0]->options_data;
                                    $p=json_decode($res,true);
                                  
                                    foreach ($p as $value)
                                    {
                                        $p=$value['price'];
                                        $q=$value['quantity'];
                                        $line_total +=$p*$q;
                                    ?>
                                        {{$value['label']}} = ${{$value['price']}}
                                    <br>
                               
                                
                                    <?php  } ?>
                            </td>

                            <td>
                                
                                 <?php
                                $user = DB::table('orders')->where('id',$order->id)->get();
                                $res=$user[0]->options_data;
                                $p=json_decode($res,true);
                                   
                                foreach ($p as $value)
                                {
                                    if(isset($value['fee'])){
                                        $fee_total += $value['fee'] * $value['quantity'];
                                        $fee = $value['fee'];
                                    }
                                    else{
                                        $fee = 0;     
                                    }
                                    ?>
                                    ${{@$fee}}
                                   <!--{!! $value['label'] !!}-->
                                  <br>
                                
                                <?php } ?>
                                
                                
                            </td>                            
                            <td>
                                
                                 <?php
                                $user = DB::table('orders')->where('id',$order->id)->get();
                                $res=$user[0]->options_data;
                                $p=json_decode($res,true);
                                   
                                foreach ($p as $value)
                                { ?>
                                    {{$value['quantity']}}
                                  <br>
                                
                                <?php } ?>
                            </td>
                        
                        <td>
                            <?php
                             $user = DB::table('orders')->where('id',$order->id)->get();
                             $res=$user[0]->options_data;
                                $p=json_decode($res,true);
                               $ltotal =0;
                            foreach ($p as $value)
                            {
                                $ltotal= $value['price'] * $value['quantity'];
                                $ftotal = 0;
                                if(isset($value['fee'])){
                                    $ftotal = $value['fee'] * $value['quantity'];    
                                }
                                
                                $ltotal = $ltotal + $ftotal;
                                
                                if($value['quantity'] > 0){
                                    $sub_total = $sub_total + $ltotal;    
                                }
                                
                                
                                ?>
                                ${{$ltotal}}
                                <br>
                                <?php 
                                $line_total = 0; 
                            }
                   
                            ?> 
                            
                        </td>
                         
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
//echo $sub_total; exit('s'); ?>

<div class="order-totals-wrapper">
    <div class="row">
        <div class="order-totals pull-right">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <td>{{ trans('order::orders.subtotal') }}</td>
                            {{-- <td class="text-right">{{ $order->sub_total->format($order->currency) }}</td> --}}
                             <td class="text-right">${{ $sub_total - $fee_total }}.00</td>
                             {{-- <td class="text-right">{{ $order->sub_total - $fee_total }}</td> --}}
                        </tr>

                        <!--@if ($order->hasShippingMethod())-->
                        <!--    <tr>-->
                        <!--        <td>{{ $order->shipping_method }}</td>-->
                        <!--        <td class="text-right">{{ $order->shipping_cost->format($order->currency) }}</td>-->
                        <!--    </tr>-->
                        <!--@endif-->

                        @if ($order->hasCoupon())
                            <tr>
                                <td>{{ trans('order::orders.coupon') }} (<span class="coupon-code">{{ $order->coupon->code }}</span>)</td>
                                <td class="text-right">&#8211;{{ $order->discount->format($order->currency) }}</td>
                            </tr>
                        @endif

                        @foreach ($order->taxes as $tax)
                            <tr>
                                <td>{{ $tax->name }}</td>
                                <td class="text-right">{{ $tax->order_tax->amount->format($order->currency) }}</td>
                            </tr>
                        @endforeach
                         
                        <tr>
                            <td>{{ trans('order::orders.charges') }}</td>
                            <td class="text-right" >${{ $fee_total }} </td>
                        </tr>
                        <tr>
                            <td>{{ trans('order::orders.total') }}</td>
                            <td class="text-right">{{ $order->total->format($order->currency) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

