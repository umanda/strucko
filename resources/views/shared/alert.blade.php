@if (Session::has('alert'))            
<div class=" {{ session()->get('alert_class') }}">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    {{ Session::get('alert') }}
</div>
@endif