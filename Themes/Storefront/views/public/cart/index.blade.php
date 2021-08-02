<?php
//    dd(Session::all());
$seats  = json_decode(Session::get('seats_data'));
if($seats){
    $a = array();
    foreach($seats as $seat){
        $a[] = $seat->label;
    }
    $seat_name = $a;
}else{
    $seat_name = '';
}
//exit('huhuhu');
?>
@extends('public.layout')

@section('title', trans('storefront::cart.cart'))

@push('meta')
    <meta name="cart-has-shipping-method" content="{{ $cart->hasShippingMethod() }}">
@endpush

@section('content')
    <div class="row">
        <div class="cart-list-wrapper clearfix">
            @if ($cart->isEmpty())
                <h2 class="text-center">{{ trans('storefront::cart.your_cart_is_empty') }}</h2>
            @else
                <!--<div class="col-md-8">-->
                <div class="col-md-7 col-md-offset-1">
                    <div class="box-wrapper clearfix">
                        <div class="box-header">
                            <h4>{{ trans('storefront::cart.cart_totals') }}</h4>
                            <h6>{{ trans('storefront::cart.free_delivery_via_email') }}</h6>
                        </div>

                        <div class="cart-list">
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <?php $counter = 0;?>
                                        @foreach ($cart->items() as $cartItem)

                                            <tr class="cart-item">

                                                <td>
                                                    @if (! $cartItem->product->base_image->exists)
                                                        <div class="image-placeholder">
                                                            <i class="fa fa-picture-o" aria-hidden="true"></i>
                                                        </div>
                                                    @else
                                                        <div class="image-holder">
                                                            <img src="{{ $cartItem->product->base_image->path }}">
                                                        </div>
                                                    @endif
                                                </td>

                                                <td>
                                                    <h5>
                                                        <a href="{{ route('products.show', $cartItem->product->slug) }}">{{ $cartItem->product->name }}</a>
                                                    </h5>

                                                    <div class="option">

                                                        @foreach ($cartItem->options as $option)
                                                            <span>{{ $option->name }}: <span>{{ $option->values->implode('label', ', ') }}</span></span>
                                                        @endforeach
                                                    </div>
                                                </td>

                                                <td>
                                                    <!--<label>{{ trans('storefront::cart.price') }}:</label>-->
                                                    <!--<span>{{ $cartItem->unitPrice()->convertToCurrentCurrency()->format() }}</span>-->
                                                </td>
                                                <td>
                                                     <!--<form method="POST" action="{{ route('cart.items.update', encrypt($cartItem->id)) }}" onsubmit="return confirm('Are you sure want to update this event from your cart?');">-->
                                                     <!--   {{ csrf_field() }}-->
                                                     <!--   {{ method_field('put') }}-->

                                                    <table class="table mb-20">
                                                        <thead>
                                                            <th>Type</th>
                                                            <th>PRECIO</th>
                                                            <th>CARGOS</th>
                                                            <th>CANTIDAD</th>
                                                        </thead>
                                                        <?php $total_price = 0; ?>
                                                         <form method="POST" id = "form_id.<?php echo $counter;?>" action="{{ route('cart.items.update', encrypt($cartItem->id)) }}" onsubmit="return confirm('Are you sure want to update this event from your cart?');">
                                                                {{ csrf_field() }}
                                                                {{ method_field('put') }}
                                                                <input type = "hidden" name = "product_id" value  = "<?php echo $cartItem->advance_options['product_id']?>" />
                                                                <input type = "hidden" name = "cart_item_id" value  = "<?php echo encrypt($cartItem->id); ?>" />

                                                        @foreach($cartItem->advance_options['quantity'] as $key => $value)
                                                                @foreach($value as $key1 => $val)
                                                                    @if($val != 0)
                                                                        @if(is_numeric($key1))
                                                                            @php
                                                                                $total_price += $key1*$val;
                                                                                if(!empty($cartItem->advance_options['fee'][$key])){
                                                                                    $fee = $cartItem->advance_options['fee'][$key] * $val;
                                                                                    $total_price += $fee;
                                                                                }



                                                                            @endphp
                                                                            <tr>
                                                                                <th>{{$key}}</th>
                                                                                <th>${{$key1}}</th>
                                                                                <th>${{ @$fee }}</th>
                                                                                <th>
                                                                                    <input type = "number" name = "advance_options[quantity][{{$key}}][{{$key1}}]" value  = "{{$val}}" readonly />
                                                                                </th>

                                                                            </tr>
                                                                            <input type = "hidden" name = "advance_options[fee][{{$key}}]" value = "{{@ $fee}}" />
                                                                        @endif()
                                                                    @endif()
                                                                @endforeach()
                                                        @endforeach()
                                                        </form >
                                                    </table>
                                                </td>
                                                <?php //session()->push('advance_options_total_price', $total_price); ?>
                                                <!--<td class="clearfix">-->
                                                <!--    <div class="quantity pull-left clearfix">-->
                                                <!--        <div class="input-group-quantity pull-left clearfix">-->
                                                <!--            <input type="text" name="qty" value="{{ $cartItem->qty }}" class="input-number input-quantity pull-left {{ "qty-{$loop->index}"  }}" min="1" max="{{ $cartItem->product->manage_stock ? $cartItem->product->qty : '' }}">-->

                                                <!--            <span class="pull-left btn-wrapper">-->
                                                <!--                <button type="button" class="btn btn-number btn-plus" data-type="plus"> + </button>-->
                                                <!--                <button type="button" class="btn btn-number btn-minus" data-type="minus" {{ $cartItem->qty === 1 ? 'disabled' : '' }}> &#8211; </button>-->
                                                <!--            </span>-->
                                                <!--        </div>-->
                                                <!--    </div>-->
                                                <!--</td>-->

                                                <td>
                                                    <label>{{ trans('storefront::cart.total') }}:</label>
                                                    <!--<span>{{ $cartItem->total()->convertToCurrentCurrency()->format() }}</span>-->
                                                    <span style="margin-right:131px">${{ $total_price }}</span>
                                                    <label>Seat Name:</label>
                                                    @if($seat_name)
                                                        @foreach($seat_name as $data)
                                                            <span>{{$data}},</span>
                                                        @endforeach
                                                    @else
                                                        <span>Provided by Management</span>
                                                    @endif
                                                </td>

                                                <td>


                                                        <button type = "submit" form = "form_id.<?php echo $counter ?>" data-toggle="tooltip" data-placement="top" title="{{ trans('storefront::cart.update') }}" class="btn-update" data-id={{ encrypt($cartItem->id) }} data-quantity-field="{{ ".qty-{$loop->index}" }}">
                                                            <i class="fa fa-refresh" aria-hidden="true"></i>
                                                        </button>
                                                        <!--<button type="submit" form = "form_id.<?php echo $counter ?>" class="btn-close" data-toggle="tooltip" data-placement="top" title="{{ trans('storefront::cart.update') }}">-->
                                                    <!--</form>-->



                                                    <form method="POST" action="{{ route('cart.items.destroy', encrypt($cartItem->id)) }}" onsubmit="return confirm('Are you sure want to delete this event from your cart?');">
                                                        {{ csrf_field() }}
                                                        {{ method_field('delete') }}

                                                        <button type="submit" class="btn-close" data-toggle="tooltip" data-placement="top" title="{{ trans('storefront::cart.remove') }}">
                                                            &times;
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            <?php $counter++; ?>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="cart-list-bottom">
                            <form method="POST" action="{{ route('cart.coupon.store') }}" id="coupon-apply-form" class="clearfix">
                                {{ csrf_field() }}

                                <div class="form-group pull-left">
                                    <input type="text" name="coupon" class="form-control" id="coupon" value="{{ old('coupon') }}">

                                    <button type="submit" class="btn btn-primary" id="coupon-apply-submit" data-loading>
                                        {{ trans('storefront::cart.apply_coupon') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="order-review cart-list-sidebar">
                        <div class="cart-total">
                            <h3>{{ trans('storefront::cart.cart_totals') }}</h3>

                            <span class="item-amount">
                                {{ trans('storefront::cart.subtotal') }}
                                <span>{{ $cart->subTotal()->convertToCurrentCurrency()->format() }}</span>
                            </span>

                            @if ($cart->hasAvailableShippingMethod())
                                <div class="available-shipping-methods" style = "display : none;">
                                    <span>{{ trans('storefront::cart.shipping_method') }}</span>

                                    <div class="form-group">
                                        @foreach ($cart->availableShippingMethods() as $name => $shippingMethod)
                                            <div class="radio">
                                                <input type="radio" name="shipping_method" class="shipping-method" value="{{ $name }}" id="{{ $name }}" {{ $cart->shippingMethod()->name() === $shippingMethod->name || $loop->first ? 'checked' : '' }}>
                                                <label for="{{ $name }}">{{ $shippingMethod->label }}</label>
                                                <span class="pull-right">{{ $shippingMethod->cost->convertToCurrentCurrency()->format() }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @if ($errors->has('shipping_method'))
                                    <span class="error-message text-center">
                                        {!! $errors->first('shipping_method', '<span class="help-block">:message</span>') !!}
                                    </span>
                                @endif
                            @endif

                            @if ($cart->hasCoupon())
                                <span class="item-amount">
                                    {{ trans('storefront::cart.coupon') }} (<span class="coupon-code">{{ $cart->coupon()->code() }}</span>)
                                    <span id="coupon-value">&#8211;{{ $cart->coupon()->value()->convertToCurrentCurrency()->format() }}</span>
                                </span>
                            @endif

                            @foreach ($cart->taxes() as $tax)
                                <span class="item-amount">
                                    {{ $tax->name() }}
                                    <span>{{ $tax->amount()->convertToCurrentCurrency()->format() }}</span>
                                </span>
                            @endforeach

                            <span class="total">
                                {{ trans('storefront::cart.total') }}
                                <span id="total-amount">{{ $cart->total()->convertToCurrentCurrency()->format() }}</span>
                            </span>

                            @if ($cart->hasNoAvailableShippingMethod())
                                <span class="error-message text-center">{{ trans('storefront::cart.no_shipping_method_is_available') }}</span>
                            @endif

                            <a href="{{ route('checkout.create') }}" class="btn btn-primary btn-checkout {{ $cart->hasNoAvailableShippingMethod() ? 'disabled' : '' }}" data-loading>
                                {{ trans('storefront::cart.checkout') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('public.products.partials.landscape_products', [
        'title' => trans('storefront::product.you_might_also_like'),
        'products' => $cart->crossSellProducts()
    ])
@endsection
