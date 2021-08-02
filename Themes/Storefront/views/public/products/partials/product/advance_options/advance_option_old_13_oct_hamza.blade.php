<style type="text/css">
	.input-cus {
    width: 60px;
    padding-left: 15px;
    height: 32px!important;
}
.mb-20{
	    margin-bottom: 20px;
}
@media only screen and (max-width:724px) {
    .table {
    width: 120%;
    max-width: 120%;
    margin-bottom: 20px;
    }
}
</style>

    <label>
        Opciones:
    </label>
    <div class="clearfix"></div>
    <table class="table mb-20">
    	<thead>
    	<th>TIPO DE BOLETO</th>
    	<th style = "text-align:center">PRECIO</th>
    	<th style = "text-align:center">CARGOS</th>
    	<th style = "text-align:center"></th>
    	<th style = "text-align:center"></th>
    	</thead>
    	<tbody>
    	    <?php $i = 0; ?>
    		@foreach($advancedOptions as $option)

    		<tr>
                <td>{{$option['label']}}</td>
    		    <td style = "text-align:center">${{$option['price']}}</td>
    		    <td style = "text-align:center">${{$option['fee']}}</td>
    		    <td style = "text-align:center">


    		        <div class="quantity pull-left clearfix">

                        <div class="input-quantity">
                            <input type="hidden" name="quantity[{{$option['label']}}][{{$option['price']}}]" onchange = "ad(this,<?php echo $option['quantity'] ;?>, <?php echo $option['purchase_limit'] ;?>)"; id ="ad_{{$option['id']}}"  value="0" class="input-number input-quantity pull-left"  min="0" <?php if($option['quantity']==0 ||$option['purchase_limit']==0) {echo "disabled";}?> >
                            <input type = "hidden" name = "fee[{{$option['label']}}]" value = "{{$option['fee']}}" />
                            <br class = "showall" style = "display:none;">
                            <small style = "display:none; color:red" class = "required_quantity" >required</small>
                        </div>
                    </div>

                </td>

                <td style = "text-align:center">
                    <!--bilal change-->
                    <span> Entradas disponsbles {{$option['quantity']}}</span>
    			    <!--<input type="number" name="quantity" value="{{$option['quantity']}}" disabled class="input-cus">-->
                </td>
    		</tr>
    		<?php $i++; ?>
            @endforeach()
            <input type ="hidden" name = "counter" id = "counter" value = <?php echo $i; ?>
    	</tbody>
    </table>
<div class="clearfix"></div>

