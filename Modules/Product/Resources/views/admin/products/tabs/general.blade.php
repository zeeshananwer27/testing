{{ Form::text('name', trans('product::attributes.name'), $errors, $product, ['labelCol' => 2, 'required' => true]) }}
{{ Form::text('organizer', trans('product::attributes.organizer'), $errors, $product, ['labelCol' => 2, 'required' => true]) }}
{{ Form::wysiwyg('description', trans('product::attributes.description'), $errors, $product, ['labelCol' => 2, 'required' => true]) }}

{{ Form::textarea('address', 'Address', $errors, $product, ['labelCol' => 2, 'required' => true]) }}
{{ Form::text('city', 'City', $errors, $product, ['labelCol' => 2, 'required' => true]) }}
{{ Form::text('state', 'State', $errors, $product, ['labelCol' => 2, 'required' => true]) }}
{{ Form::number('zip_code', 'Zip Code', $errors, $product, ['labelCol' => 2, 'required' => true]) }}

<div class="form-group">
	<label for="organizer" class="col-md-2 control-label text-left">
		Start Event Date<span class="m-l-5 text-red">*</span>
	</label>
	<div class='col-md-6'>
	    	<input type='date' name="start_event" class="form-control -picker" labelcol="2" required="true" value="{{ ($product['start_event']) ? date('Y-m-d',strtotime($product['start_event'])) : date('Y-m-d',strtotime(date('Y-m-d'))) }}"/>
	    <!-- <input type='datetime-local' name="start_event" class="form-control -picker" labelcol="2" required="true" value="{{date('Y-m-d\TH:i:s',strtotime($product['start_event'])) }}"/> -->
	    <!-- <input type='text' name="start_event" class="form-control datetime-picker" labelcol="2" required="true" value="{{$product['start_event']}}"/> -->

	</div>
</div>
<div class="form-group">
	<label for="organizer" class="col-md-2 control-label text-left">
		End Event Date<span class="m-l-5 text-red">*</span>
	</label>
	<div class='col-md-6'>
	    <input type='datetime-local' name="end_event" class="form-control -picker" labelcol="2" required="true" value="{{ date('Y-m-d\TH:i:s',strtotime($product['end_event'])) }}" />
	    <!-- <input type='text' name="end_event" class="form-control datetime-picker" labelcol="2" required="true" value="{{$product['end_event']}}" /> -->

	</div>
</div>
<div class="form-group">
	<label for="organizer" class="col-md-2 control-label text-left">
		Scanner Password:<span class="m-l-5 text-red">*</span>
	</label>
	<div class='col-md-6'>
		<div class="input-group">
		  	<input type='password' name="scanner_pass" id="scanner_password" class="form-control" aria-describedby="basic-addon2" labelcol="2" required="true" value="{{$product['scanner_pass']}}" />
		  	<span class="input-group-addon" id="basic-addon2" onclick = "pass( 'scanner_password' )" >@</span>
		</div>
	</div>
</div>

<div class="form-group">
	<div class='col-md-8'>
	    @php $users = valid_users(); @endphp
	    

		@if(@$product['id'])
				@php 
			    $assign_user = event_users($product['id']); 
					//echo "<pre>"; echo $product['id'] ; print_r($users); print_r($user); exit('oo');
			    @endphp
		<div class="form-group ">
			<label for="assign_user[]-selectized" class="col-md-3 control-label text-left">
				Assign User<span class="m-l-5 text-red">*</span></label>
				<div class="col-md-9">
					<select name="assign_user[]" class="selectize prevent-creation selectized" multiple="multiple" id="assign_user[]" tabindex="-1" style="display: none;">
						@foreach($users as $key =>$user)
								<option value="{{ $key }}" 
									@foreach($assign_user as $assign_u)
										@if($key == $assign_u->user_id ) selected = 'selected' @endif 
									@endforeach	
									>	
									{{$user}}
								</option>
						@endforeach
					</select>
					<div class="selectize-control selectize prevent-creation multi plugin-remove_button">
						<!-- <div class="selectize-input items not-full has-options has-items">
							<div class="item" data-value="45">local leadconcept
								<a href="javascript:void(0)" class="remove" tabindex="-1" title="Remove">×</a>
							</div>
							<input type="select-multiple" autocomplete="off" tabindex="" id="assign_user[]-selectized" style="width: 4px; opacity: 1; position: relative; left: 0px;">
						</div> -->
							<div class="selectize-dropdown multi selectize prevent-creation plugin-remove_button" style="display: none; visibility: visible; width: 370.859px; top: 40px; left: 0px;">
								<div class="selectize-dropdown-content">
									@foreach($users as $key =>$user)
									<div class="option" data-selectable="" data-value="{{ $key }}">{{$user}}</div>
									@endforeach							
								</div>
							</div>
					</div>
			</div>
		</div>
		@else
			{{ Form::select('user_id', 'Assign User', $errors, $users,'', [ 'multiple' => true, 'required' => true, 'class' => 'selectize prevent-creation']) }}
		@endif

	</div>
</div>

<input type="hidden" name="lat" id="add_lat" value="{{$product['lat']}}">
<input type="hidden" name="lng" id="add_long" value="{{$product['lng']}}">

<div class="row">
    <div class="col-md-8">
        {{ Form::select('categories', trans('product::attributes.categories'), $errors, $categories, $product, ['class' => 'selectize prevent-creation', 'multiple' => true]) }}
        {{ Form::select('tax_class_id', trans('product::attributes.tax_class_id'), $errors, $taxClasses, $product) }}
        {{ Form::checkbox('is_active', trans('product::attributes.is_active'), trans('product::products.form.enable_the_product'), $errors, $product) }}
    </div>
</div>

@php $google_map_key=\DB::table('settings')->where('key','google_map_key')->select('plain_value')->first()->plain_value; @endphp


<script
src="https://code.jquery.com/jquery-3.4.1.js"
integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
crossorigin="anonymous"></script>
<script src="https://maps.google.com/maps/api/js?sensor=false&key=AIzaSyC4ZnhpJNqTbOjYU0UYxWMjWHRLsgMhzX8" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js"></script>
<link rel="stylesheet" href="https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/css/bootstrap-datetimepicker.min.css">
<script src="https://rawgit.com/Eonasdan/bootstrap-datetimepicker/master/build/js/bootstrap-datetimepicker.min.js"></script>

<script>

	window.onload = function() {

	    $( "#save_scanner_password" ).hide();
	    // $('#scanner_password').prop('disabled', true);
	}

	function pass(val){
	 var pas = document.getElementById(val);
		if(pas.type ==="password"){
		    $( "#save_scanner_password" ).show();
		    // $('#scanner_password').prop('disabled', false);
			pas.type = "text";
		}
		else{
		    pas.type = "password";
			// $('#scanner_password').prop('disabled', true);
			$( "#save_scanner_password" ).hide();
		}
	}
</script>
<script type="text/javascript">
	$(document).ready(function () {
		$('#address').change(function () {
			var address = $('#address').val();
			var addr = document.getElementById("address");
			// Get geocoder instance
			var geocoder = new google.maps.Geocoder();

			// Geocode the address
			geocoder.geocode({
			    'address': addr.value
			}, function(results, status) {
			    if (status === google.maps.GeocoderStatus.OK && results.length > 0) {
			      // set it to the correct, formatted address if it's valid
			     // addr.value = results[0].formatted_address;
			      var lat_long = JSON.parse(JSON.stringify(results[0].geometry.location));
			      $('#add_lat').val(lat_long['lat']);
			      $('#add_long').val(lat_long['lng']);

			      // show an error if it's not
			    } else {
			    	alert("Invalid address");
			    	$('#address').val('');
			    }
			});
		})

		window.onload=function() {
		    var addr = document.getElementById("address");
		    if (addr != "") {
		    	// Get geocoder instance
				var geocoder = new google.maps.Geocoder();

				// Geocode the address
				geocoder.geocode({
				    'address': addr.value
				}, function(results, status) {
				    if (status === google.maps.GeocoderStatus.OK && results.length > 0) {
				      // set it to the correct, formatted address if it's valid
				    //   addr.value = results[0].formatted_address;
				      var lat_long = JSON.parse(JSON.stringify(results[0].geometry.location));
				      $('#add_lat').val(lat_long['lat']);
				      $('#add_long').val(lat_long['lng']);

				      // show an error if it's not
				    } 
				});
		    }
		}
	})
</script>
<script type="text/javascript">
    // $('#datetimepicker1').datetimepicker({
	//     defaultDate: new Date(),
	//     format: 'YYYY-MM-DD H:mm:ss',
	//     sideBySide: true
	// });
	// $('#datetimepicker2').datetimepicker({
	//     defaultDate: new Date(),
	//     format: 'YYYY-MM-DD H:mm:ss',
	//     sideBySide: true
	// });
</script>