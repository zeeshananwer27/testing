<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">
    <div class="product-details">
        <h1 class="product-name">{{ $product->name }}</h1>

        @if (setting('reviews_enabled'))
            @include('public.products.partials.product.rating', ['rating' => $product->avgRating()])

            <span class="product-review">
                ({{ intl_number($product->reviews->count()) }} {{ trans('storefront::product.customer_reviews') }})
            </span>
        @endif

        @unless (is_null($product->sku))
            <div class="sku">
                <label>{{ trans('storefront::product.sku') }}: </label>
                <span>{{ $product->sku }}</span>
            </div>
        @endunless

        @if ($product->manage_stock)
            <span class="left-in-stock">
                {{ trans('storefront::product.only') }}
                <span class="{{ $product->qty > 0 ? 'green' : 'red' }}">{{ intl_number($product->qty) }}</span>
                {{ trans('storefront::product.left') }}
            </span>
        @endif

        <div class="clearfix"></div>

        <!--<span class="product-price pull-left">{{ product_price($product) }}</span>-->

        <!--<div class="availability pull-left">-->
        <!--    <label>{{ trans('storefront::product.availability') }}:</label>-->

        <!--    @if ($product->in_stock)-->
        <!--        <span class="in-stock">{{ trans('storefront::product.in_stock') }}</span>-->
        <!--    @else-->
        <!--        <span class="out-of-stock">{{ trans('storefront::product.out_of_stock') }}</span>-->
        <!--    @endif-->
        <!--</div>-->

        <div class="clearfix"></div>

        @if (! is_null($product->short_description))
            <div class="product-brief">{{ $product->short_description }}</div>
        @endif
        <div class="product-variants clearfix">
        <div>{{$product->address}}</div>
        </div>
        <form method="POST" action="{{ route('cart.items.store') }}" class="clearfix">
            {{ csrf_field() }}

            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div class="product-variants clearfix">
                @foreach ($product->options as $option)
                    <div class="row">
                        <div class="col-md-7 col-sm-9 col-xs-10">
                            @includeIf("public.products.partials.product.options.{$option->type}")
                        </div>
                    </div>
                @endforeach
                
                <div class="row"> 
                    <div class="col-md-7 col-sm-9 col-xs-10">
                        @include("public.products.partials.product.advance_options.advance_option")
                    </div>
                </div>
                
            </div>

            <div class="quantity pull-left clearfix" style = "display:none">
                <label class="pull-left" for="qty">{{ trans('storefront::product.qty') }}</label>

                <div class="input-group-quantity pull-left clearfix">
                    <input type="text" name="qty" value="1" class="input-number input-quantity pull-left" id="qty" min="1" max="{{ $product->manage_stock ? $product->qty : '' }}">

                    <span class="pull-left btn-wrapper">
                        <button type="button" class="btn btn-number btn-plus" data-type="plus"> + </button>
                        <button type="button" class="btn btn-number btn-minus" data-type="minus" disabled> &#8211; </button>
                    </span>
                </div>
            </div>

            <button type="submit" class="add-to-cart btn btn-primary pull-left" {{ $product->isOutOfStock() ? 'disabled' : '' }} data-loading>
                {{ trans('storefront::product.add_to_cart') }}
            </button>
        </form>

        <div class="clearfix"></div>

        <div class="add-to clearfix">
            <!--<form method="POST" action="{{ route('wishlist.store') }}">-->
            <!--    {{ csrf_field() }}-->

            <!--    <input type="hidden" name="product_id" value="{{ $product->id }}">-->

            <!--    <button type="submit">{{ trans('storefront::product.add_to_wishlist') }}</button>-->
            <!--</form>-->

            <!--<form method="POST" action="{{ route('compare.store') }}">-->
            <!--    {{ csrf_field() }}-->

            <!--    <input type="hidden" name="product_id" value="{{ $product->id }}">-->

            <!--    <button type="submit">{{ trans('storefront::product.add_to_compare') }}</button>-->
            <!--</form>-->
        </div>
    </div>
</div>
<div class="col-lg-12 col-md-7 col-sm-7 col-xs-12" id="map" style="height: 350px;"></div>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC4ZnhpJNqTbOjYU0UYxWMjWHRLsgMhzX8&callback=initMap">
</script>

<script>
    // Initialize and add the map
    function initMap() {
        // The location of Uluru
        var uluru = {lat: <?php echo $product->lat; ?>, lng: <?php echo $product->lng; ?>};
        // The map, centered at Uluru
        var map = new google.maps.Map(
            document.getElementById('map'), {zoom: 4, center: uluru});
            // The marker, positioned at Uluru
            var marker = new google.maps.Marker({position: uluru, map: map}
        );
    }
</script>
<script>
    function ad(e, max, limit){
        if(limit > 0 ){
            if(limit < e.value ){document.getElementById(e.id).value = limit; }
            
        }
        else{
            if(max < e.value ){document.getElementById(e.id).value = max; }
            else{ }    
        }
        
 
        
    }
    
</script>