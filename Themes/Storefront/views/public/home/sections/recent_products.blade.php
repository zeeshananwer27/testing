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

<style type="text/css">
    @media (min-width:0px) and (max-width:425px){
    .comprar{
        float: right !important;
    }
    .btn-size{
        font-size: 13px !important;
    }
    
}
</style>
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

@if ($recentProducts->isNotEmpty())
    <section class="section-wrapper clearfix">
        <div class="section-header" style= "border: 0px;text-align: center;">
            <h3>{{ setting('storefront_recent_products_section_title') }}</h3>
        </div>

        <div class="recent-products">
            <div class="row">
                <div class="grid-products separator">
                    @each('public.products.partials.product_card', $recentProducts, 'product')
                </div>
            </div>
        </div>
    </section>
@endif
<p id="demo"></p>

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
