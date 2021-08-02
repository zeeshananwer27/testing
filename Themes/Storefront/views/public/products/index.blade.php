
<?php


namespace Modules\Cart;
use Modules\Cart\Facades\Cart;
$cart = Cart::instance();
    $productsInCart = array(); 
    foreach ($cart->items() as $cartItem){
        array_push($productsInCart, $cartItem->product->id);
    }
    if(!empty($productsInCart)){
     $nos = count($productsInCart);
     $productsInCart = json_encode($productsInCart);   
    
    }
    else{
        $productsInCart = null;
    }
?>

@extends('public.layout')

@section('title')
    @if (request()->has('query'))
        {{ trans('storefront::products.search_results_for') }}: "{{ request('query') }}"
    @else
        {{ trans('storefront::products.shop') }}
    @endif
@endsection

@section('content')
<!--.section-custom{ padding-left:25px; padding-right:25px; padding-top:13px ; }-->
    <section class="product-list" style = "padding-left:30px; padding-right:30px; padding-top:30px ;" >
        <div class="row">
            <div class="col-md-9 col-md-offset-1 col-sm-12">
            <!--@include('public.products.partials.filter')

            <div class="col-md-9 col-sm-12">-->
                <div class="product-list-header clearfix">
                    <div class="search-result-title pull-left">
                        @if (request()->has('query'))
                            <h3>{{ trans('storefront::products.search_results_for') }}: "{{ request('query') }}"</h3>
                        @else
                            <h3>{{ trans('storefront::products.shop') }}</h3>
                       @endif
						@if (request()->has('city'))
							 <span> {{intl_number(count($productIds))}}  {{ trans_choice('storefront::products.products_found', $products->total()) }}</span>
					    @else
				
                        <span>{{ intl_number(count($products)) }} {{ trans_choice('storefront::products.products_found', $products->total()) }}</span>
                        @endif
                    </div>

                    <div class="search-result-right pull-right">
                        <ul class="nav nav-tabs">
                            <li class="view-mode {{ ($viewMode = request('viewMode', 'grid')) === 'grid' ? 'active' : '' }}">
                                <a href="{{ $viewMode === 'grid' ? '#' : request()->fullUrlWithQuery(['viewMode' => 'grid']) }}" title="{{ trans('storefront::products.grid_view') }}">
                                    <i class="fa fa-th-large" aria-hidden="true"></i>
                                </a>
                            </li>

                            <li class="view-mode {{ $viewMode === 'list' ? 'active' : '' }}">
                                <a href="{{ $viewMode === 'list' ? '#' : request()->fullUrlWithQuery(['viewMode' => 'list']) }}" title="{{ trans('storefront::products.list_view') }}">
                                    <i class="fa fa-th-list" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>

                        <div class="form-group">
                            <select class="custom-select-black" onchange="location = this.value">
                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'relevance']) }}" {{ ($sortOption = request()->query('sort')) === 'relevance' ? 'selected' : '' }}>
                                    {{ trans('storefront::products.sort_options.relevance') }}
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'alphabetic']) }}" {{ ($sortOption = request()->query('sort')) === 'alphabetic' ? 'selected' : '' }}>
                                    {{ trans('storefront::products.sort_options.alphabetic') }}
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'topRated']) }}" {{ $sortOption === 'topRated' ? 'selected' : '' }}>
                                    {{ trans('storefront::products.sort_options.top_rated') }}
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'latest']) }}" {{ $sortOption === 'latest' ? 'selected' : '' }}>
                                    {{ trans('storefront::products.sort_options.latest') }}
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'priceLowToHigh']) }}" {{ $sortOption === 'priceLowToHigh' ? 'selected' : '' }}>
                                    {{ trans('storefront::products.sort_options.price_low_to_high') }}
                                </option>

                                <option value="{{ request()->fullUrlWithQuery(['sort' => 'priceHighToLow']) }}" {{ $sortOption === 'priceHighToLow' ? 'selected' : '' }}>
                                    {{ trans('storefront::products.sort_options.price_high_to_low') }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="product-list-result clearfix">
                    <div class="tab-content">
                        <div id="grid-view" class="tab-pane {{ ($viewMode = request('viewMode', 'grid')) === 'grid' ? 'active' : '' }}">
                            <div class="row">
                                <div class="grid-products separator">
                                    <?php //echo "<pre>"; print_r($products); exit('thihtihti'); ?>
                                    @if ($viewMode === 'grid')
                                        @forelse ($products as $product)
                                            
                                            <?php if(isset($_GET['city'])){
                                            // echo "<pre>"; print_r($product->id); exit('j'); 
                                                // echo "<pre>"; print_r($products); exit('j'); 
                                                if(!in_array($product->id, $productIds)){
                                                    continue;
                                                }
                                                else{?>
                                                    @include('public.products.partials.product_card')        
                                                <?php }
                                            }
                                            else{?>
                                                @include('public.products.partials.product_card')
                                            <?php }?>
                                            
                                            
                                        @empty
                                            <h3>{{ trans('storefront::products.no_products_were_found') }}</h3>
                                        @endforelse
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div id="list-view" class="tab-pane {{ $viewMode === 'list' ? 'active' : '' }}">
                            @if ($viewMode === 'list')
                                @forelse ($products as $product)
                                    @include('public.products.partials.list_view_product_card')
                                @empty
                                    <h3>{{ trans('storefront::products.no_products_were_found') }}</h3>
                                @endforelse
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pull-right">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection

<!-- Modal -->
<div class="modal fade" id="myModal" >
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body">
            <h4>You have already added this item in cart.</h4>
            <h6>So please select Update cart to update cart items.</h6>
            </div>
            <div class="modal-footer">
            <a href="{{ route('cart.index') }}" class="add-to-cart btn btn-primary">Update cart</a>
            <button type="button" class="add-to-cart btn btn-primary" onclick="hideModal()">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>

        function getval( id , slug )
        {
            var cart = "<?= $productsInCart; ?>";
            var flag=0;
            // console.log(cart);
            if(cart != "")
            {
            cart = JSON.parse(cart);
            for(var i = 0 ; i < cart.length ; i++){
            if(cart[i] == id){
            flag=1;

            // return false;
            }
            }
            if(flag==1)
            {
            $('#myModal').modal('show'); // Show modal
            }
            else{
            var url = "products/" + slug;
            window.location.href = url;
            }

        }
        else{
        var url = "products/" + slug;
        window.location.href = url;
        }
        }
    function hideModal()
    {
    $('#myModal').modal('hide'); // Hide modal
    }
    </script>


