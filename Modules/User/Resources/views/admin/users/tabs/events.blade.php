<div class="row">
    <div class="col-md-8">
    	<?php 
    	// echo"<pre>"; print_r($user); 

    	// exit(' k');?>
        {{ Form::select('events', trans('user::attributes.users.events'), $errors, $events, @$user, ['multiple' => true, 'required' => true, 'class' => 'selectize prevent-creation']) }}
    </div>

    <div class="col-md-4">

    </div>
</div>
