@if(!empty($data) && $data->total() > 1 )
 
<div class="common-pagination d-flex align-items-center justify-content-between">
    <div class="count-wrap d-flex align-items-center">
        <div class="count font-md">
            {{$data->total()}} {{(!empty($userType)) ? ucfirst($userType) : ''}}
        </div>
    </div>
    <div class="pagination-item ">
        {{ $data->links() }}
        <!-- <ul class="pagination mb-0">
            <li class="page-item "><a class="page-link" href="#"><i class="icon-keyboard_arrow_left"></i></a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">...</a></li>
            <li class="page-item"><a class="page-link" href="#">4</a></li>
            <li class="page-item"><a class="page-link" href="#">5</a></li>
            <li class="page-item"><a class="page-link" href="#">...</a></li>
            <li class="page-item"><a class="page-link" href="#">24</a></li>
            <li class="page-item"><a class="page-link" href="#"><i class="icon-keyboard_arrow_right"></i></a></li>
        </ul> -->
    </div> 
   
</div> 
@endif