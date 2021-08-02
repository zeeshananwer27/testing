<div class="billing-address clearfix">
    <h5>{{ trans("storefront::checkout.tabs.billing_address") }}</h5>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('billing.first_name') ? 'has-error': '' }}">
            <label for="billing-first-name">
                {{ trans('storefront::checkout.tabs.attributes.billing.first_name') }}<span>*</span>
            </label>

            <input type="text" name="billing[first_name]" value="<?php echo isset($user->first_name) ? $user->first_name : ''; ?>" class="form-control" id="billing-first-name">

            {!! $errors->first('billing.first_name', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('billing.last_name') ? 'has-error': '' }}">
            <label for="billing-last-name">
                {{ trans('storefront::checkout.tabs.attributes.billing.last_name') }}<span>*</span>
            </label>

            <input type="text" name="billing[last_name]" value="<?php echo isset($user->last_name) ? $user->last_name : ''; ?>" class="form-control" id="billing-last-name">

            {!! $errors->first('billing.last_name', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group {{ $errors->has('billing.address_1') ? 'has-error': '' }}">
            <label for="billing-1">
                {{ trans('storefront::checkout.tabs.attributes.billing.address_1') }}<span>*</span>
            </label>

            <input type="text" name="billing[address_1]" value="<?php echo isset($user_info->billing_address_1) ? $user_info->billing_address_1 : ''; ?>" class="form-control" id="billing-address-1">

            {!! $errors->first('billing.address_1', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group {{ $errors->has('billing.address_2') ? 'has-error': '' }}">
            <label for="billing-2">
                {{ trans('storefront::checkout.tabs.attributes.billing.address_2') }}
            </label>

            <input type="text" name="billing[address_2]" value="<?php echo isset($user_info->billing_address_2) ? $user_info->billing_address_2 : ''; ?>" class="form-control" id="billing-address-2">

            {!! $errors->first('billing.address_2', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('billing.city') ? 'has-error': '' }}">
            <label for="billing-city">
                {{ trans('storefront::checkout.tabs.attributes.billing.city') }}<span>*</span>
            </label>

            <input type="text" name="billing[city]" value="<?php echo isset($user_info->billing_city) ? $user_info->billing_city : ''; ?>" class="form-control" id="billing-city">

            {!! $errors->first('billing.city', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('billing.zip') ? 'has-error': '' }}">
            <label for="billing-zip">
                {{ trans('storefront::checkout.tabs.attributes.billing.zip') }}<span>*</span>
            </label>

            <input type="text" name="billing[zip]" value="<?php echo isset($user_info->billing_zip) ? $user_info->billing_zip : ''; ?>" class="form-control" id="billing-zip">

            {!! $errors->first('billing.zip', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('billing.country') ? 'has-error': '' }}">
            <label for="billing-country">
                {{ trans('storefront::checkout.tabs.attributes.billing.country') }}<span>*</span>
            </label>

            <select name="billing[country]" class="custom-select-black" id="billing-country">
                @foreach ($countries as $code => $name)
                    <option value="{{ $code }}" {{ old('billing.country') === $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            {!! $errors->first('billing.country', '<span class="error-message">:message</span>') !!}
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group {{ $errors->has('billing.state') ? 'has-error': '' }}">
            <label for="billing-state">
                {{ trans('storefront::checkout.tabs.attributes.billing.state') }}<span>*</span>
            </label>
         <!--<input type="text" name="billing[state]" value="{{ old('billing.state') }}" class="form-control" id="billing-state">-->
         <!--bilal change-->
            <input type="text" name="billing[state]" value="<?php echo isset($user_info->billing_state) ? $user_info->billing_state : ''; ?>" class="form-control" id="billing-state">
            
        
            {!! $errors->first('billing.state', '<span class="error-message">:message</span>') !!}
        </div>
    </div>
    
</div>
