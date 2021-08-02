<?php  $e_id = Request::segment(3); ?>
@if( Request::segment(3) != null  && is_numeric(Request::segment(3)))

    <div class="form-group ">
        <div class="col-md-9">
            <a href = "sitting_arrangements/{{ $e_id }} " class="btn btn-primary" target="_blank">
                Go to Sitting Arrangement Settings</a>
        </div>
    </div>
@else

    <h2>Notes</h2>
    <div class="danger" style="margin-bottom: 15px;padding: 4px 12px;background-color: #ffdddd;border-left: 6px solid #f44336;">
        <p><strong>Note!</strong> Please save event first to create sitting arrangement.</p>
    </div>
    <div class="form-group ">
        <div class="col-md-9" style="cursor: no-drop;">
            <a href = "sitting_arrangements/00"  disabled class="btn btn-primary disabled" target="_blank"  >Go to Sitting Arrangement Settings</a>
        </div>
    </div>
<script>
    $('.disabled').click(function(e){
        e.preventDefault();
    })
</script>
@endif()


