<?php
$modelAdvancedOptions = Modules\Product\Entities\AdvancedOptions::where('product_id', $product->id)->get();
?>
<div class="row">
    <div class="col-md-12">

        <?php if (count($modelAdvancedOptions) > 0) : ?>

        <?php foreach ($modelAdvancedOptions as $key => $option) : ?>
        <div class="row" id="row<?= $option->id ?>" style="margin-bottom: 10px">
            <input type="hidden" name="product_id" value="<?= $product->id ?>">
            <!--<input type="hidden" name="advance_option_id[]" value="<?= $option->id ?>">-->
            <input type="hidden" name="advance_option_id[]" value="<?= $option->id ?>">
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Label" name="advanced_label[]" value="<?= $option->label ?: '' ?>">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_label.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Price" name="advanced_price[]" value="<?= $option->price ?: '' ?>">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_price.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Quantity" name="advanced_quantity[]" value="<?= $option->quantity ?: '' ?>">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_quantity.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Fee" name="fee[]" value="<?= $option->fee ?: '' ?>">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_quantity.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Limit" name="purchase_limit[]" value="<?= $option->purchase_limit ?: '' ?>">
                <span class="help-block" style="color: #f36">{{ $errors->has('purchase_limit.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="color" class="form-control" name="advanced_color[]" value="<?= $option->advanced_color ?: '' ?>">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_color.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <button type="button" name="remove" id="<?= $key ?>" class="btn btn-danger" onclick="delete_option(<?= $option->id ?>,<?= $product->id ?>)">X</button>
            </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
        <div class="row" id="row0" style="margin-bottom: 10px">
            <input type="hidden" name="product_id" value="<?= $product->id ?>">
            <input type="hidden" name="advance_option_id[]" value="0">
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Label" name="advanced_label[]">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_label.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Price" name="advanced_price[]">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_price.0') ? 'Price is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Quantity" name="advanced_quantity[]">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_quantity.0') ? 'Quantity is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Fee" name="fee[]">
                <span class="help-block" style="color: #f36">{{ $errors->has('fee.0') ? 'Label is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" placeholder="Limit" name="purchase_limit[]">
                <span class="help-block" style="color: #f36">{{ $errors->has('purchase_limit.0') ? 'Limit is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <input type="color" class="form-control" name="advanced_color[]" value="">
                <span class="help-block" style="color: #f36">{{ $errors->has('advanced_color.0') ? 'advanced_color is required' : '' }}</span>
            </div>
            <div class="col-md-2">
                <button type="button" name="remove" id="0" class="btn btn-danger btn_remove">X</button>
            </div>

        </div>
        <?php endif; ?>

        <div class="dynamic_field" id="dynamic_field">

        </div>

        <div class="row" style="margin-top: 10px; margin-bottom: 10px;">
            <div class="col-md-12">
                <button type="button" name="add" id="add" class="btn btn-default m-r-10">Add New Option</button>
            </div>
        </div>

    </div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('.col-md-offset-2').css('cssText', 'margin-left: 0 !important');

        var postURL = "<?php echo url('advanced_options'); ?>";

        $("form").submit(function() {

            var data = $('#product-edit-form').serialize();

            $.ajax({
                url: postURL,
                method: "GET",
                data: $('#product-edit-form').serialize(),
                type: 'json',
                success: function(data) {
                    if (data.error) {
                        printErrorMsg(data.error);
                    } else {
                        //location.reload();
                    }
                }
            });
        });



        var b = <?= !empty($modelAdvancedOptions) ? count($modelAdvancedOptions) : 0 ?>;

        $('#add').click(function() {
            b++;
            $('#dynamic_field').append('' +
                '<div style="margin-bottom: 10px" class="row" id="row' + b + '">' +
                '<div class="col-md-2">' +
                '<input type="text" class="form-control" name="advanced_label[]" placeholder="Label">' +
                '<input type="hidden" name="advance_option_id[]" value="0">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<input type="text" class="form-control" name="advanced_price[]" placeholder="Price">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<input type="text" name="advanced_quantity[]" class="form-control" placeholder="Quantity">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<input type="text" class="form-control" placeholder="Fee" name="fee[]">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<input type="text" class="form-control" placeholder="Limit" name="purchase_limit[]">' +
                '</div>' +
                '<div class="col-md-2" >' +
                '<input type="color" class="form-control" id="advanced_color_' + b + '" name="advanced_color[]">' +
                '</div>' +
                '<div class="col-md-2">' +
                '<button type="button" name="remove" id="' + b + '" class="btn btn-danger btn_remove">X</button>' +
                '</div><br></div>');
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
           
            document.getElementById("advanced_color_" + b).value = color;
        });


        $(document).on('click', '.btn_remove', function() {
            var button_id = $(this).attr("id");
            $('#row' + button_id + '').remove();
        });

    });

    function delete_option(advanceOptionId, product_id) {
        @if($product->sitting_arrangement)
            var sitting_arrangement=<?php echo $product->sitting_arrangement ?>;
        @else
            var sitting_arrangement=[];
        @endif
        var reservedSeats=[];
        var url = "<?php echo url('reservedSeatsData'); ?>";
        $.ajax({
            url: url,
            method: "GET",
            data: {
                id: product_id,
            },
            success: function(data) {
                reservedSeats = data;
                console.log("reservedSeats: ", reservedSeats)
                
                var isadvanceOptionInReservedSeatExist = reservedSeats.find(seat => seat.seats_data.includes('advancedOptionId":' + advanceOptionId)) !== undefined
                var isadvanceOptionInJSON = false
                debugger
        
                if (isadvanceOptionInReservedSeatExist) {
                    alert("This advance option cannot be deleted because this is associated with reserve seat.");
                    return;
                }
        
                sitting_arrangement.find(model => {
                    if (model.category === "AssignedSeatingEditable" && `${model["advancedOptionId"]}` === `${advanceOptionId}`) {
                        isadvanceOptionInJSON = true
                        return true;
        
                    } else if (model.category === "CircularTableSeat" && `${model["advancedOptionId"]}` === `${advanceOptionId}`) {
                        isadvanceOptionInJSON = true
                        return true;
        
                    } else if (model.category === "TableC8") {
                        var found = model.nodeDataArray.find(seat => {
                            if (`${seat["advancedOptionId"]}` === `${advanceOptionId}`) {
                                isadvanceOptionInJSON = true
                                return true;
                            }
                        })
                        return found ? true : false;
        
                    }
                })
        
                if (isadvanceOptionInJSON) {
                    alert("This advance option can't' delete because this exists in diagram. First remove from diagram then proceed this process.");
                    return;
                }
        
                var postURL = "<?php echo url('delete_advance_option'); ?>";
                $.ajax({
                    url: postURL,
                    method: "GET",
                    data: {
                        id: advanceOptionId,
                    },
                    type: 'json',
                    success: function(data) {
                        console.log(data)
                        $('#row' + advanceOptionId + '').remove();
                        if (data.error) {
                            printErrorMsg(data.error);
                        } else {
                            //location.reload();
                        }
                    }
                });
            },
            error: function(error) {
                console.log(error)
                alert("Something went wrong. Contact to administration.");
            }
        });
    }
</script>
