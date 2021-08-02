<div class="col-lg-4 col-md-5 col-sm-5 col-xs-7">
    <div class="product-image">
        <div class="base-image">
            @if (! $product->base_image->exists)
                <div class="image-placeholder">
                    <i class="fa fa-picture-o"></i>
                </div>
            @else
                <a class="base-image-inner" href="{{ $product->base_image->path }}">
                    <img src="{{ $product->base_image->path }}">
                    <span><i class="fa fa-search-plus" aria-hidden="true"></i></span>
                </a>
            @endif

            @foreach ($product->additional_images as $additionalImage)
                @if (! $additionalImage->exists)
                    <div class="image-placeholder">
                        <i class="fa fa-picture-o"></i>
                    </div>
                @else
                    <a class="base-image-inner" href="{{ $additionalImage->path }}">
                        <img src="{{ $additionalImage->path }}">
                        <span><i class="fa fa-search-plus" aria-hidden="true"></i></span>
                    </a>
                @endif
            @endforeach
        </div>

        <div class="additional-image">
            @if (! $product->base_image->exists)
                <div class="image-placeholder">
                    <i class="fa fa-picture-o"></i>
                </div>
            @else
                <div class="thumb-image">
                    <img src="{{ $product->base_image->path }}">
                </div>
            @endif

            @foreach ($product->additional_images as $additionalImage)
                @if (! $additionalImage->exists)
                    <div class="image-placeholder">
                        <i class="fa fa-picture-o"></i>
                    </div>
                @else
                    <div class="thumb-image">
                        <img src="{{ $additionalImage->path }}">
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="event-page-location">
      <div class="tab-location">
        <h4>LUGAR DEL EVENTO</h4>
      </div>

        <div class="product-variants clearfix">
            <div>
                {{$product->address}}
                <a href="https://maps.google.com/maps?f=d&amp;hl=en&amp;daddr={{$product->address}}" target="_blank">Optener direcciones</a>
            </div>
        </div>
        <div class="col-lg-12" id="map" style="height: 300px;margin-top: 20px;"></div>
      </div>

     <div class="event-page-location">
      <div class="tab-location">
        <h4>organizador</h4>
      </div>

           <div class="product-variants clearfix">
               <div><b>{{ $product->organizer}}</b><br> </div>
        </div>
      </div>


























</div>
