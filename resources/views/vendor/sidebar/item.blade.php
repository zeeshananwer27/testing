<li class="{{ $item->getItemClass() ? $item->getItemClass() : null }}
    {{ $active ? 'active' : null }}
    {{ $item->hasItems() ? 'treeview' : null }}
">
    <a href="{{ $item->getUrl() }}" class="{{ count($appends) > 0 ? 'hasAppend' : null }}"
        @if ($item->getNewTab())
            target="_blank"
        @endif
    >
        <i class="{{ $item->getIcon() }}"></i>
        <span>{{ $item->getName() }}</span>

        @foreach ($badges as $badge)
            {!! $badge !!}
        @endforeach

        @if ($item->hasItems())
            <span class="pull-right-container">
                <i class="{{ $item->getToggleIcon() }} pull-right"></i>
            </span>
        @endif
    </a>
    
    @foreach ($appends as $append)
        {!! $append !!}
    @endforeach

    @if (count($items) > 0)
        
        
        <?php  
        
        
        
        // $event = '<a href="https://boletazo.bustosmediallc.com/public/admin/products" class="">
        //     <i class="fa fa-angle-double-right"></i>
        //     <span>Event</span>
        // </a>';
        
        
        // $event = '<li class="">
        //     <a href="https://boletazo.bustosmediallc.com/public/admin/products" class="">
        //     <i class="fa fa-angle-double-right"></i>
        //     <span>Events</span>
        //     </a>
        // </li>';
        
        //if( $items[0] == $event1 ){
         //   exit('hhhhh');
    //    }
        
        
        
        // $items[0] = $event; 
        
        // print_r($items); exit('jijiji'); 
        // foreach($items as $it){
            
        //     echo $it;
            
        //   exit('right');  
        // }
        
        ?>
        
        
        <ul class="treeview-menu">
            @foreach ($items as $item)
                
                {!! $item !!}
            @endforeach
        </ul>
    @endif
</li>
