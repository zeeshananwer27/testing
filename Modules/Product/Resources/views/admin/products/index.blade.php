<style>

@media screen and (max-width: 767px){
   .custom-div{
        position: absolute;
        top: -70px;
        left:0;
   }
  .customs-spn{
    font-size: 11px;
    padding-top: 0px;
   }

   .cus-btn-s {
    font-size: 15px;
    border: none;
    background: #0068e1;
    border-radius: 3px;
    padding: 10px 10px;
    color: white;
   }

    #notify {
        color: green;
    }
    .field-s {
        font-size: 14px;
        padding: 6px 12px;
        width: 70px;
        height: 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

}

@media screen and (min-width: 767px){
   .custom-div{
        position: absolute;
        top: -60px;
        left:0;
   }
   .cus-btn-s {
    font-size: 15px;
    border: none;
    background: #0068e1;
    border-radius: 3px;
    padding: 10px 10px;
    color: white;
    }
    #notify {
        color: green;
    }
    .field-s {
        font-size: 15px;
        padding: 6px 12px;
        width: 110px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
     .dis{
        display:none;
    }
}

</style>
<?php
$pass =  DB::table('users')->where('id', 1)->select('scanner_password')->get();
// echo "<pre>";print_r($pass[0]->scanner_password); exit('s');
?>


@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('product::products.products'))

    <li class="active">{{ trans('product::products.products') }}</li>
@endcomponent
@component('admin::components.page.index_table')
    <div class = "custom-div" >
    <span class = "customs-spn" > Scanner password: </span>
    <br class="dis">
    <input type = "password" class="field-s " id = "scanner_password"; name = "scanner_password" = value = "<?php echo $pass[0]->scanner_password; ?>"    style="background: white;" >
    <input type = "button" class="cus-btn-s" id = "edit_scanner_password" value = "edit" onclick = "pass( 'scanner_password' )">
    <input type = "button" class="cus-btn-s" id = "save_scanner_password" value = "save" style = "display:none">
    <div id = "notify" style = "display:none"><small>Scanner password saved. </small></div>
    </div>
    @slot('buttons', ['create'])
    @slot('resource', 'products')
    @slot('name', trans('product::products.product'))

    @slot('thead')
        @include('product::admin.products.partials.thead', ['name' => 'products-index'])
    @endslot
@endcomponent

@push('scripts')
    <script>

    window.onload = function() {

        $( "#save_scanner_password" ).hide();
        $('#scanner_password').prop('disabled', true);
    }


    function pass(val){
// 	$( "#save_scanner_password" ).show();
	 var pas = document.getElementById(val);
		if(pas.type ==="password"){
		    $( "#save_scanner_password" ).show();
		    $('#scanner_password').prop('disabled', false);
			pas.type = "text";
		}
		else{
		    pas.type = "password";
			$('#scanner_password').prop('disabled', true);
			$( "#save_scanner_password" ).hide();
		}
    }

    $("#save_scanner_password").click(function(){
        var passw = $('#scanner_password').val();

        $.ajax({
				type:'get',
				url:route("scanner"),
				data:{'password':passw},
				success:function(dt){
				    if(dt== true ){
				        $('#notify').show();
				        pass('scanner_password');
				        // $('#scanner_password').prop('disabled', true);
			         //   $( "#save_scanner_password" ).hide();
				    }

				}
			});

    });




        new DataTable('#products-table .table', {
            columns: [
                { data: 'checkbox', orderable: false, searchable: false, width: '3%' },
                { data: 'thumbnail', orderable: false, searchable: false, width: '10%' },
                { data: 'name', name: 'translations.name', orderable: false, defaultContent: '' },
                // { data: 'price', searchable: false },
                { data: 'status', name: 'is_active', searchable: false },
                { data: 'created', name: 'created_at' },
            ],
        });
    </script>
@endpush
