<div>
  @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      <strong></strong> {{session()->get('message')}}
    </div>
  @elseif(session()->has('error'))
      <div class="alert alert-danger alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong></strong> {{session()->get('error')}}
      </div>
  @elseif(session()->has('warning'))
      <div class="alert alert-warning alert-dismissible">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong></strong> {{session()->get('warning')}}
      </div>
  @endif
  @if($errors->any())
  <div class="text-danger">
      <ul>
        <div class="alert alert-danger alert-dismissible">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          @foreach($errors->all() as $error)
            <div><strong></strong> {{ $error }}</div>
          @endforeach
        </div>
      </ul>
  </div>
  @endif
</div>
