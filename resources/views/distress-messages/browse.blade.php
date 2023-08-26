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
        <div class="breadcrumb-title pr-3">Distress Messages</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <!-- <li class="breadcrumb-item active" aria-current="page">DataTable</li> -->
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
        <div class="card-body">

          @include('components.flash-message')

          <!-- <div class="card-title">
            <h4 class="mb-0">DataTable Example</h4>
          </div> -->
          <!-- <hr/> -->
          <div class="table-responsive">
                @if(isset($userId))
                    <a class="btn btn-primary mb-4" href="{{ route('user.view', ['user' => $userId] ) }}">Back to User Profile</a>
                @endif

              <table id="example" class="table table-striped table-bordered" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Sent By</th>
                    <th>Time of Message</th>
                    <th>Phone No</th>
                    <th>Priority</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php $x = 1; @endphp
                  @foreach($distressMessages as $distressMessage)
                      <tr>
                        <td>{{ $x }}</td>
                        <td>{{ $distressMessage->firstname .' '.$distressMessage->lastname }}</td>
                        <td>{{ date('j-M-Y h:iA', strtotime($distressMessage->time_of_message)) }}</td>
                        <td>{{ $distressMessage->phone_number}}</td>
                        <td>{{ $distressMessage->priority }}</td>
                        <td><a href="{{ route('distress-message.show', ['message' => $distressMessage->distress_message_id]) }}" class="btn btn-primary">View</a></td>
                        <!-- <td><a href="javascript:;" class="btn btn-sm btn-light-success btn-block radius-30">Delivered</a> -->
                        </td>
                      </tr>
                      @php $x++; @endphp
                  @endforeach
                </tbody>
              </table>
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
  @endsection
</x-app-layout>
