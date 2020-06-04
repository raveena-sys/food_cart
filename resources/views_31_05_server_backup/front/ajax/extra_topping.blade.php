    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
          <h3 class="modal-title" id="lineModalLabel">Extra Toppings Options</h3>
        </div>
        <div class="modal-body">

          <table class="table table-hover">
            <tbody>
              @if(!empty($topping_master))
              @foreach($topping_master as $k => $v)
              <tr>
                <td>
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" value="">
                      <span class="cr"><i class="cr-icon glyphicon glyphicon-ok"></i></span>
                      {{$v->name?$v->name:''}}
                    </label>
                  </div>
                </td>
              </tr>
              @endforeach
              @endif
            </tbody>
          </table>
          <button class="btn_save">Save</button>
          <div class="clearfix"></div>


        </div>

      </div>
    </div>