<?php 
// $cities = DB::table('products')->join('product_translations', 'products.id', '=', 'product_translations.product_id')->where('products.deleted_at', NULL)->select('product_translations.city')->get()->toArray();
$cities = DB::table('products')->join('product_translations', 'products.id', '=', 'product_translations.product_id')->where('products.deleted_at', NULL)->whereIn('products.is_active' ,['1'])->select('product_translations.city')->get()->toArray();
?>
<div class="search-area pull-left">
    <form action="{{ route('products.index') }}" method="GET" id="search-box-form">
        <div class="search-box hidden-sm hidden-xs">
            <input type="text" name="query" class="search-box-input" placeholder="{{ trans('storefront::layout.search_for_products') }}" value="{{ request('query') }}">

            <div class="search-box-button">
                <button class="search-box-btn btn btn-primary" type="submit">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    {{ trans('storefront::layout.search') }}
                </button>
                <select name="city" id="searchTextField" autocomplete="on" value="" class="select_dropdown_new">
                    <?php $double = array();?>
                    <option value="" disabled selected> {{ trans('storefront::layout.search_for_products_location') }} </option>
                    <?php
                    foreach ($cities as $city) {
                         if( empty($double) OR !in_array($city->city, $double)){?>
                            <option value="{{ $city->city }}">{{ $city->city }}</option>
                            <?php array_push($double, $city->city ); }?>
                      <?php } ?>
                </select>
            </div>
        </div>

        <div class="mobile-search visible-sm visible-xs">
            <div class="dropdown">
                <div class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </div>

                <div class="dropdown-menu">
                    <div class="search-box">
                        <input type="search" name="query" class="search-box-input" placeholder="{{ trans('storefront::layout.search_for_products') }}">

                        <div class="search-box-button">
                            <button type="submit" class="search-box-btn btn btn-primary">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<style>

.theme-sky-blue .search-box .search-box-button .search-box-btn {
    color: #fafafa;
    background: #104977;
    border-radius: 5px;
    height: 40px;
    margin-top: 0px;
}
.search-box input.search-box-input {
    font-size: 14px;
    -webkit-box-flex: 1;
    -ms-flex-positive: 1;
    flex-grow: 1;
    padding-left: 10px;
    border-left: none;
    background: #f3f3f3;
    height:39px !important;
}

.select_dropdown_new {
    font-size: 16px;
    height: 40px!important;
    margin-top: 0px;
    border-radius: 0px !important;
    padding-left: 20px;
    background: #f3f3f3;
        margin-left: 20px;
    margin-right: 20px;
    border-radius: 5px !important;
    border: none !important;
}
.search-box {

    border: none;
    background:none;
    margin-top: 4px;
}
</style>
<!--<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyC4ZnhpJNqTbOjYU0UYxWMjWHRLsgMhzX8&libraries=places" type="text/javascript"></script>-->
<!--    <script type="text/javascript">-->
<!--           function initialize() {-->
<!--                   var input = document.getElementById('searchTextField');-->
<!--                   var autocomplete = new google.maps.places.Autocomplete(input);-->
<!--           }-->
<!--           google.maps.event.addDomListener(window, 'load', initialize);-->
<!--   </script>-->