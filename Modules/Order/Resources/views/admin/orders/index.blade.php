<style>
   .cust_form{
    padding-top: 15px;
    padding-right: 15px;
    margin-bottom: 0px;
   }
@media screen and (min-width: 762px) {
  div.dataTables_filter label {
        padding-right: 0px !important;
        text-align: left; 
    }
    .custom-export{
        position: relative !important;
        z-index: 999 !important;
        margin-top: 46px !important;
    }

}
</style>

@extends('admin::layout')

@component('admin::components.page.header')
    @slot('title', trans('order::orders.orders'))

    <li class="active">{{ trans('order::orders.orders') }}</li>
    
    

@endcomponent

@section('content')
    <div class="box box-primary">
         <div class="row">
            <div class="col-md-7 col-md-offset-5 text-right">
                <form action="{{route('export')}}" method="POST" class="cust_form">
                    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
                    <input type="hidden" name="search_data" id = "search_data" value="">
                    <select class="selectpicker" data-show-subtext="true" data-live-search="true" name="events">
                         <option value="">Select Event</option>
                            @foreach($events as $event)
                                <option value="{{$event->id}}">{{$event->name}}</option>
                            @endforeach
                    </select>
                    <input type="submit" class="btn btn-primary" value="Export">
                </form> 
            </div>   
        </div>
        <div class="box-body index-table" id="orders-table">
            @component('admin::components.table')
                @slot('thead')
                    <tr>
                        <th>{{ trans('order::orders.table.order_id') }}</th>
                        <th>{{ trans('order::orders.table.customer_name') }}</th>
                        <th>{{ trans('order::orders.table.customer_email') }}</th>
                        <th>{{ trans('order::orders.table.total') }}</th>
                        <th data-sort>{{ trans('admin::admin.table.created') }}</th>
                        <th data-sort>Organizer</th>
                        <th data-sort>Event name</th>
                        <th data-sort>Event start</th>
                    </tr>
                @endslot
            @endcomponent
        </div>
    </div>
@endsection

@push('scripts')
   <script>
        DataTable.setRoutes('#orders-table .table', {
        index: '{{ "admin.orders.index" }}',
        show: '{{ "admin.orders.show" }}',
        });
        
        new DataTable('#orders-table .table', {
            columns: [
            { data: 'id',name:'id' },
            { data: 'customer_name', orderable: false, searchable: false },
            { data: 'customer_email',name:'orders.customer_email' },
            // { data: 'status' },
            { data: 'total',name:'orders.total' },
            { data: 'created', name: 'orders.created_at' },
            { data: 'organizer',name: 'pt.organizer'},
            { data: 'name',name: 'pt.name'},
            { data: 'start_event',name: 'pt.start_event'},
            // { data: 'Event start', name: 'start_event' },
            ],
        });
 
 
 $("input").keyup(function(){
  
    if($(this).attr('type') == 'search'){
        // alert($(this).val());
        $('#search_data').val($(this).val());
    }
 });
</script>

@endpush
