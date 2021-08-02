<style>

    .section-divider-title{
        display: block;
        padding: 5px;
        font-size: 14px;
        color: #FFF;
        background-color: #999;
        margin-bottom: 5px;
        font-weight: bold;
    }
    .info-box {
        background-color: #F1F0EE;
        padding: 10px .5%;
        margin-bottom: 15px;
        width: 99%;
        line-height: 20px;
    }
    .important-info div.icons {
        margin-right: 10px;
    }
    .important-info p {
        font-weight: bold;
        font-size: 13px;
        color: #414141;
        padding-top: 1px;
        -webkit-font-smoothing: antialiased;
    }
    .exclam_icon{
        font-size: 20px;
        padding: 0px 5px 0;
        color: #00457c;
    }
    .single_product_table .table thead{
        background-color: #555;
        padding: 8px 5px 4px 5px;
        color: #fff;
        text-transform: uppercase;
    }
    .single_product_table .table thead tr th{
        color: #fff;
        font-size: 11px;
    }
    .single_product_table .table tbody{
        border-bottom: 1px solid #DDD;
    }
    .single_product_table .table tbody tr:hover{
        background-color: #E6E5E2;
        transition: all .5s;
    }
    .single_product_table .table tbody tr td{
        font-size: 14px !important;
        border-top: 0px !important;
    }
    .single_product_table .table tbody tr td:first-child{
        font-weight: bold;
        font-size: 14px;
    }
    .single_product_table .table tbody tr td .input-quantity input{
        height: auto !important;
        padding: 3px 0px 3px 10px;
        width: 100px !important;
    }
    .single_product_table .table tbody tr td .quantity{
        float: none !important;
    }
    .tab-location{
        line-height: 28px;
        color: #999999;
        padding-bottom: 10px;
        border-bottom: 1px solid #999;
    }
    .tab-location h4{
        font-weight: 500;
        line-height: 28px;
        color: #999999;
        font-size: 16px;
    }
    .event-page-location{
        margin-top: 20px;
        width: 100%;
        height: auto;
        float: left;

    }
    .product-variants div,.product-variants p{
        color: #000;
        font-size: 14px;
    }
    #event-bar {
        padding: 20px 0;
        width: 100%;
        background: #F1F0EE;
        margin-bottom: 40px;
    }
    .event-cal-box {
        display: block;
        width: 60px;
        margin: 0 2% 0 0;
        background-color: #fff;
        border: none;
        border-radius: 8px;
        float: left;
    }
    .event-cal-box .event-cal-month {
        font-weight: normal;
        font-size: 14px;
        color: #fff;
        display: block;
        width: 60px;
        text-align: center;
        border-radius: 6px 6px 0 0;
        padding: 2px 0;
        text-transform: uppercase;
        -webkit-font-smoothing: antialiased;
        background-color: #00457c;
        line-height: 20px;
    }
    .event-cal-box .event-cal-day {
        font-size: 28px;
        text-align: center;
        padding: 2px 0;
        -webkit-font-smoothing: antialiased;
        color: #00457c;
        line-height: 32px;
    }
    .event-h2{
        font-size: 20px;
        color: #000;
        font-weight: 500;
        line-height: 30px;
    }
    .event-h3{
        font-size: 15px;
        color: #000;
        line-height: 18px;
    }
    .header-icon-list{
        list-style: none;
        background: #fff;
        padding: 10px;
        border-radius: 5px;
        column-count: 3;
        margin: 0 16px;
    }
    .header-icon-list li i{font-size: 20px;color:#000;}
    @media (min-width:0px) and (max-width:425px){
        .comprar{
            float: right !important;
        }
        .btn-size{
            font-size: 13px !important;
        }

    }
</style>
<?php //echo "<pre>"; print_r($product->toArray()); exit('thia'); ?>
<?php //$options = DB::table('advanced_options')->select('label,purchase_limit')->get()->toArray();?>
<?php //echo "<pre>"; print_r($product->options); exit('out'); ?>
<div class="col-lg-8 col-md-7 col-sm-7 col-xs-12">

    <div class="product-details">
    <!--<h1 class="product-name">{{ $product->name }}</h1>-->

    @if (setting('reviews_enabled'))
        <!--@include('public.products.partials.product.rating', ['rating' => $product->avgRating()])-->

        <!--<span class="product-review">
                ({{ intl_number($product->reviews->count()) }} {{ trans('storefront::product.customer_reviews') }})
            </span>-->
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

        <div class="col-sm-12 no-padding">
            <div class="section-divider-title">
                <!--Comprar Boletazos-->
                <!--bilal change-->
                Comprar Boletos

            </div>
        </div>

        <div class="col-sm-12 no-padding">



            <div class="info-box important-info">


                <!--bilal change-->
                <p><i class="fa fa-exclamation-circle exclam_icon"> </i>
                    <!--Hay un limite de 10 boletazos por comprador. No hay reembolsos de cargos de servicio.-->
                    <?php
                    $result1="";
                    $count=0;
                    $html = '';
                    foreach($advancedOptions as $option){

                        if(count($advancedOptions)==1)
                        {
                            $result1.=$option['purchase_limit'].'&nbsp'.$option['label'].'&nbsp';
                        }
                        else
                        {
                            $result1.=$option['purchase_limit'].'&nbsp'.$option['label'].', ';
                        }}
                    ?>

                    <?php

                    ?>
                    Hay un limite de <?php echo rtrim($result1, ", ");?> boletos por comprador. No hay reembolsos de cargos de servicio.

                </p>

            </div>






            <form method="POST" action="{{ route('cart.items.store') }}" class="clearfix" id="add_to_cart_form" onsubmit = "return validateForm()">
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
                        <div class="col-md-12 col-sm-9 col-xs-10 single_product_table">
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
                <?php $disable = 0 ;
                foreach($advancedOptions as $optionss) {
                    if($optionss['quantity'] >0){
                        $disable++;
                    }
                }
                ?>
            <!--<button type="submit" class="add-to-cart btn btn-primary pull-left btn-size" {{ $product->isOutOfStock() ? 'disabled' : '' }} data-loading>-->
                <input type="hidden" class="seats_data" name="seats_data">
                <div>
                    <label class="pull-left" for="qty">Select Seats</label><br>
                    <div class="" style = "width: 100%; height: 350px; padding: 10px; margin-bottom: 10px;">
                        <div id="myDiagramDiv" style="background-color: white; width:
                    100%; height:
                    100%">
                        </div>
                    </div>
                </div>
                <div class="row" style=" margin-bottom: 10px;">
                    <div class="col-md-12 col-sm-9 col-xs-10 single_product_table">
                        <table class="table "  id="table_id" style="display: none">
                            <thead>
                            <th >TIPO DE BOLETO</th>
                            <th style = "text-align:center;width: 100px;">Seat</th>
                            <th style = "text-align:center ;width: 100px;">PRECIO</th>
                            <th style = "text-align:center;width: 100px;">CARGOS</th>
                            <th style = "text-align:center;width: 100px;">Cantidad</th>
                            <th style = "text-align:center;width: 100px;">Precio Total</th>
                            <th style = "text-align:center;width: 100px;"></th>
                            <th></th>

                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>

                <button type="submit" class="add-to-cart btn btn-primary pull-left btn-size" <?php if( $disable == 0 ) echo 'disabled'; else ''; ?> >
                    <?php
                    if($disable == 0){
                        echo 'boletos agotados';
                    }
                    else{?>
                    {{ trans('storefront::product.add_to_cart') }}
                    <?php }
                    ?>

                </button>

                <?php
                if($disable != 0){?>
                <button type="button" class="add-to-cart btn btn-primary pull-left comprar btn-size" <?php if( $disable == 0 ) echo 'disabled'; else ''; ?> id="add_to_cart">
                    Comprar
                </button>
                <?php }    ?>


            </form>



            <div class="clearfix"></div>

            <div class="event-page-location">
                <div class="tab-location">
                    <h4>DETALLES DEL EVENTO</h4>
                </div>

                <div class="product-variants clearfix">
                <!--<p>{{strip_tags($product->description)}}</p>-->
                <?php echo $product->description; ?>
                <!--{{$product->description}}-->
                </div>
                <br>
            </div>
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
    <script
        src="https://code.jquery.com/jquery-3.4.1.js"
        integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
        crossorigin="anonymous"></script>
    <script type="text/javascript">
     
        $(document).on('click','#add_to_cart',function(e) {
            var APP_URL = {!! json_encode(url('/')) !!}
            var a = new Array();
            var t_qty = parseInt($('#ad_0').val()) + parseInt($('#ad_1').val());
            data1 = Helper__GetSelectedCirclesIds();
            console.log(data1);
            if(data1.length != 0)
            {
            console.log(data1);
            data1.forEach(function (item,index) {
            a.push(item);
            });
            $('.seats_data').val('');
            $('.seats_data').val(JSON.stringify(a));
            }else{
            $('.seats_data').val('');
            }
            var form = $('#add_to_cart_form');
            
            var data = form.serialize();
            var qut = 0;
            var count = $("#counter").val();
            if(data1.length != 0){
            $('.showall').hide();
            $('.required_quantity').hide();
            $.ajax({
            type: 'post',
            url: "{{ route('cart.items.ajaxstore') }}",
            data: data,
            success: function (response) {
            window.location.replace(APP_URL+"/cart");}
            });
            }else{
            alert("Selected Seats should be equal to Total quantity");
            return false;
            }
        });
        function validateForm() {
            var a = new Array();var b = new Array();
            var qut= 0;
            var count = $("#counter").val();
            var t_qty = parseInt($('#ad_0').val()) + parseInt($('#ad_1').val());



            data1 = Helper__GetSelectedCirclesIds();
            if(data1.length > 0){
                if(data1.length != 0)
                {
                    data1.forEach(function (item,index) {
                        a.push(item);

                    });

                    $('.seats_data').val('');
                    $('.seats_data').val(JSON.stringify(a));
                    $('.qty').val(data1.length);
                }else{
                    $('.seats_data').val('');
                }
                $('.showall').hide();
                $('.required_quantity').hide();
                $("#add_to_cart_form").submit();
            }else{
                alert("Selected Seats should be equal to Total quantity");
                return false;
            }

        }

    </script> 
    <script >
        var dataDiagram = <?php echo $product['sitting_arrangement']; ?>;
        var advancedOptions = <?php echo json_encode($advancedOptions); ?>;
        // var reservedSeats = <?php echo json_encode($reservedSeats); ?>;
        @if(sizeof($reservedSeats) != 0 )
            var reservedSeats = <?php echo $reservedSeats ?>;
            // for (i=0;i<reservedSeatss.length;i++){
            //     var reservedSeats = JSON.parse(reservedSeatss[i].seats_data);
            //     console.log(i);
            // }
            var optional = <?php echo $reservedSeats ?>;
            optional.map(item => {
                reservedSeats = reservedSeats.concat(JSON.parse(item.seats_data));
            });
        @else
            console.log('here');
            var reservedSeats = [{
            "label":"D1",
            "seatKey":-15,
            "associationKey":-14,
            "advancedOptionId":327
            },
            {
            "label":"D2",
            "seatKey":-3,
            "associationKey":-71,
            "advancedOptionId":318
            },
            {
            "label":"D2",
            "seatKey":-33,
            "associationKey":-35,
            "advancedOptionId":318
            }];
        @endif
        
        function getValues(){

            var advancedOptions = <?php echo json_encode($advancedOptions); ?>;
            var id=advancedOptions.length;
            var selectedSeats = Helper__GetSelectedCirclesIds();
            for(i=0;i<id; i++)
            {
                var value=0;

                for (j=0;j<selectedSeats.length;j++)
                {

                    if(advancedOptions[i]['id']==selectedSeats[j]['advancedOptionId']){

                        value++;
                        document.getElementById("ad_"+advancedOptions[i]['id']).value = value;
                    }

                }
            }

            var table_id = [];

            try {
                selectedSeats.map(seat => {
                    var selectedOption = advancedOptions.find(option => option.id === seat.advancedOptionId);
                    table_id.push({
                        seatLabel: seat.label,
                        optionLabel: selectedOption.label,
                        optionPrice: selectedOption.price,
                        optionCharges: selectedOption.fee,
                        optionTotal: Math.round(selectedOption.price) + Math.round(selectedOption.fee)
                    });
                });

                const table = document.getElementById("table_id");
                while (table.children[1].firstChild) {
                    table.children[1].removeChild(table.children[1].lastChild);
                }

                if(table_id.length) {
                    table_id.map(row => {
                        var markup = "" +
                            "<tr >" +
                            "<td >"+ row.optionLabel +"</td>" +
                            "<td style = \"text-align:center\">"+ row.seatLabel +"</td>" +
                            "<td style = \"text-align:center\">"+ row.optionPrice +"</td>" +
                            "<td style = \"text-align:center\">" + row.optionCharges + "</td>" +
                            "<td style = \"text-align:center\">" + 1 + "</td>" +
                            "<td style = \"text-align:center\">" + row.optionTotal + "</td>" +
                            "</tr>";

                        $("#table_id tbody").append(markup);
                    });
                    document.getElementById("table_id").style.display = "block";
                } else {
                    document.getElementById("table_id").style.display = "none";
                }
            } catch(error) {
                // console.log(error);
            }
        }
    </script>
