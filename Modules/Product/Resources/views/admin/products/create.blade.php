@extends('admin::layout')
@php

  //  $tranucate=DB::table('temp_data')->TRUNCATE();
@endphp
@component('admin::components.page.header')
    @slot('title', trans('admin::resource.create', ['resource' => trans('product::products.product')]))

    <li><a href="{{ route('admin.products.index') }}">{{ trans('product::products.products') }}</a></li>
    <li class="active">{{ trans('admin::resource.create', ['resource' => trans('product::products.product')]) }}</li>
@endcomponent

@section('content')
    <form method="POST" action="{{ route('admin.products.store') }}" class="form-horizontal" id="product-create-form" novalidate>
        {{ csrf_field() }}

        {!! $tabs->render(compact('product')) !!}
    </form>
@endsection

@include('product::admin.products.partials.shortcuts')
<script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
<script type="text/javascript">
	$(document).on("submit", "#product-create-form", function(event){
	    event.preventDefault();
	    //event.stopPropagation();
	    advanced_label = $('#advanced_label').val();
	    advanced_price = $('#advanced_price').val();
	    advanced_quantity = $('#advanced_quantity').val();
	    purchase_limit = $('#purchase_limit').val();
		if (advanced_label == "") {
			alert('Please Add Advance Options Values');
		}

	});
</script>
