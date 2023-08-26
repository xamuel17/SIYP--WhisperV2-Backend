<x-app-layout>
  @section('more-styles')
      <!--Data Tables -->
      <link href="{{ asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
      <link href="{{ asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css">
  @endsection
  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">New Harmspot</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('harmspot.index')}}">Harmspots</a></li>
              <li class="breadcrumb-item active" aria-current="page">New Harmspot</li>
            </ol>
          </nav>
        </div>
        <!-- <div class="ml-auto">
          <div class="btn-group">
            <button type="button" class="btn btn-primary">Actions</button>
            <button type="button" class="btn btn-primary bg-split-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown">	<span class="sr-only"> Actions</span>
            </button>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg-left">	<a class="dropdown-item" href="javascript:;">Action</a>
              <a class="dropdown-item" href="javascript:;">New Admin</a>
              <a class="dropdown-item" href="javascript:;">Something else here</a>
              <div class="dropdown-divider"></div>	<a class="dropdown-item" href="javascript:;">Separated link</a>
            </div>
          </div>
        </div> -->
      </div>
      <!--end breadcrumb-->

      <div class="card">
      @include('components.flash-message')

        <div class="card-body">
            <div class="row">
              <div class="col-md-6">
                  <h4>{{$harmspot->title}}</h4>
                  <h4>{{$harmspot->location}}</h4>
                  <h5>{{$harmspot->content}}</h4>
              </div>
              <div class="col-md-6">
              </div>
            </div>
        </div>

      </div>

      <div class="card">

        <div class="card-body">
          <!-- <div class="card-title">
            <h4 class="mb-0">Form text editor</h4>
          </div>
          <hr/> -->
            <div class="row">
              <div class="col-md-6">
                <h4 class="text-success">True Votes({{ count($trueVotes) }})</h4>
                @if(count($trueVotes))
                    <table class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>SN</th>
                          <th>User</th>
                          <th>Date Voted</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $x=1; @endphp
                        @foreach($trueVotes as $trueVote)
                          @php
                          $user = \App\Models\User::where('id',$trueVote->user_id)->first();
                          @endphp
                          <tr>
                              <td>{{$x}}</td>
                              <td>{{ $user->firstname. ' '.$user->lastname}}</td>
                              <td>{{ date('j-M-Y h:iA', strtotime($trueVote->created_at)) }}</td>
                          </tr>
                          @php $x++; @endphp
                        @endforeach
                      </tbody>
                    </table>
                @endif
              </div>
              <div class="col-md-6">
                <h4 class="text-danger">False Votes({{ count($falseVotes) }})</h4>
                @if(count($falseVotes))
                    <table class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>SN</th>
                          <th>User</th>
                          <th>Date Voted</th>
                        </tr>
                      </thead>
                      <tbody>
                        @php $x=1; @endphp
                        @foreach($falseVotes as $falseVote)
                          @php
                          $user = \App\Models\User::where('id',$falseVote->user_id)->first();
                          @endphp
                          <tr>
                              <td>{{$x}}</td>
                              <td>{{ $user->firstname. ' '.$user->lastname}}</td>
                              <td>{{ date('j-M-Y h:iA', strtotime($falseVote->created_at)) }}</td>
                          </tr>
                          @php $x++; @endphp
                        @endforeach
                      </tbody>
                    </table>
                @endif
              </div>
            </div>
        </div>

      </div>

    </div>
  </div>

  @section('more-scripts')
    <!--Data Tables js-->
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script>
      $(document).ready(function () {
        //Default data table
        $('#example').DataTable();
        var table = $('#example2').DataTable({
          lengthChange: false,
          buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
        });
        table.buttons().container().appendTo('#example2_wrapper .col-md-6:eq(0)');
      });
    </script>
    <script src="{{ asset('assets/js/tinymce.min.js') }}" referrerpolicy="origin"></script>
    <script>
  		tinymce.init({
  		  selector: '#mytextarea'
  		});
  	</script>
  @endsection
</x-app-layout>
