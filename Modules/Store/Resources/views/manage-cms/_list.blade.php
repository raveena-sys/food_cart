@if(count($cms) > 0)
<table class="table">
    <thead>
        <tr>
            <th><span>Page Name</span></th>
            <th><span>Title</span></th>
            <th class="w_130 text-center"><span>Action</span>
            </th>
        </tr>
    </thead>
    <tbody>
         @foreach($cms as $data)
        <tr>
            <td>
                <span class="font-md">{{!empty($data->page_name) ? $data->page_name : '-'}}</span>
            </td>
            <td>
                <span class="font-md">{{!empty($data->page_title) ? $data->page_title : '-'}}</span>
            </td>
            <td class="text-center">
                <a class="action_icon" href="{{url('store/manage-cms/edit/'.$data->id)}}"><i class="icon-pencil"></i></a>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@else
<div class="alert alert-danger"><center>No Records Found</center></div>
@endif
