@extends('public.layout')

@section('title', $product->name)

@push('meta')
    <meta name="title" content="{{ $product->meta->meta_title }}">
    <meta name="keywords" content="{{ implode(',', $product->meta->meta_keywords) }}">
    <meta name="description" content="{{ $product->meta->meta_description }}">
    <meta property="og:title" content="{{ $product->meta->meta_title }}">
    <meta property="og:description" content="{{ $product->meta->meta_description }}">
    <meta property="og:image" content="{{ $product->baseImage->path }}">
@endpush

@section('breadcrumb')
    <li><a href="{{ route('products.index') }}">{{ trans('storefront::products.shop') }}</a></li>
    <li class="active">{{ $product->name }}</li>
@endsection

@section('content')

</div>
<div id="event-bar">
  <div class="container">
      <div class="row">
    <div class="col-md-10">
      <div class="event-cal-box float-l">
        <div class="event-cal-month">
                                    <?php echo date("M", strtotime($product->start_event)); ?>
                                </div>
        <div class="event-cal-day">
                                    <?php echo date("d", strtotime($product->start_event)); ?>
                                </div>
      </div>
      <h1 class="event-h2" itemprop="name" style="font-size:20px">{{ $product->name }}</h1>
      <h3 class="event-h3">
          <!--bilal change-->
        <time><?php echo date("m-d-Y", strtotime($product->start_event)); ?></time>
      </h3>
      <h3 class="event-h3">
        <span><span itemprop="name">{{$product->address}}</span></span><input type="hidden" id="locationname" value="Rivera" Maya Mexican Grill"></h3>
    </div>
    </div>
          <div class="clear-b"> </div>
  </div>
</div>
<div class="container">
    <div class="product-details-wrapper">
        <div class="row">
            @include('public.products.partials.product.images')
            @include('public.products.partials.product.details')
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="tab product-tab ">
                    <!--<ul class="nav nav-tabs">
                        <li class="{{ request()->has('reviews') || review_form_has_error($errors) ? '' : 'active' }}">
                            <a data-toggle="tab" href="#description">{{ trans('storefront::product.tabs.description') }}</a>
                        </li>

                        @if ($product->hasAnyAttribute())
                            <li>
                                <a data-toggle="tab" href="#additional-information">{{ trans('storefront::product.tabs.additional_information') }}</a>
                            </li>
                        @endif

                        @if (setting('reviews_enabled'))
                            <li class="{{ request()->has('reviews') || review_form_has_error($errors) ? 'active' : '' }} {{ review_form_has_error($errors) ? 'error' : '' }}">
                                <a data-toggle="tab" href="#reviews">{{ trans('storefront::product.tabs.reviews') }}</a>
                            </li>
                        @endif
                    </ul>-->

                    <div class="tab-content">
                        @include('public.products.partials.product.tab_contents.description')

                        @if ($product->hasAnyAttribute())
                            @include('public.products.partials.product.tab_contents.additional_information')
                        @endif

                        @includeWhen(setting('reviews_enabled'), 'public.products.partials.product.tab_contents.reviews')
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('public.products.partials.landscape_products', [
        'title' => trans('storefront::product.related_products'),
        'products' => $relatedProducts
    ])

    @include('public.products.partials.landscape_products', [
        'title' => trans('storefront::product.you_might_also_like'),
        'products' => $upSellProducts
    ])
@endsection
