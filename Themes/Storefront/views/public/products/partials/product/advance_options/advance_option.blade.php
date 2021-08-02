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
        @if($sitting_check==1)
            <th style = "text-align:center">Color</th>
            <th style = "text-align:center"></th>
        @endif
        
        @if($sitting_check==0)
            <th style = "text-align:center">asientos disponibles</th>
            <th style = "text-align:center">Quantity</th>
        @endif
        <th style = "text-align:center"></th>
    </thead>
    <tbody>
    <?php $i = 0; ?>
    @foreach($advancedOptions as $option)
    <tr>
        <td>{{$option['label']}}</td>
        <td style = "text-align:center">${{$option['price']}}</td>
        <td style = "text-align:center">${{$option['fee']}}</td>
        @if($sitting_check==1)
            <td style = "text-align:center"><input type= "color" style="height:25px !important;" value = "{{$option['advanced_color']}}" disabled/></td>
        @endif
        <td style = "text-align:center">
            <!--bilal change-->
            @if($sitting_check==0)
            <span id="{{$option['id']}}" style = "display:block;"> {{$option['quantity']}}</span>
            @else
            <span id="{{$option['id']}}" style = "display:none;"> {{$option['quantity']}}</span>
            @endif
            <input type="hidden" id="limit_{{$option['id']}}" value="{{$option['quantity']}}">
            <!--<input type="number" name="quantity" value="{{$option['quantity']}}" disabled class="input-cus">-->
        </td>
        <td style = "text-align:center">
            <div class="quantity pull-left clearfix">
            
                <div class="input-quantity">
                    @if($sitting_check==1)
                        <input type="hidden" name="quantity[{{$option['label']}}][{{$option['price']}}]" onchange = "ad(this,<?php echo $option['quantity'] ;?>, <?php echo $option['purchase_limit'] ;?>)"; id ="ad_{{$option['id']}}" value="0" class="input-number input-quantity pull-left" min="0" <?php if($option['quantity']==0 ||$option['purchase_limit']==0) {echo "disabled";}?> >
                    @else
                        <input type="number" name="quantity[{{$option['label']}}][{{$option['price']}}]" onchange = "ad(this,<?php echo $option['quantity'] ;?>, <?php echo $option['purchase_limit'] ;?>)"; id ="ad_{{$option['id']}}" value="0" class="input-number input-quantity pull-left" min="0" <?php if($option['quantity']==0 ||$option['purchase_limit']==0) {echo "disabled";}?> >
                    @endif
                        <input type = "hidden" name = "fee[{{$option['label']}}]" value = "{{$option['fee']}}" />
                        <br class = "showall" style = "display:none;">
                        <small style = "display:none; color:red" class = "required_quantity" >required</small>
                </div>
            </div>
        </td>
    </tr>
    <?php $i++; ?>
    @endforeach()
    <input type ="hidden" name = "counter" id = "counter" value = <?php echo $i; ?>
    </tbody>
    </table>
<div class="clearfix"></div>

