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
        <div class="breadcrumb-title pr-3">{{$user->firstname.' '.$user->lastname}}'s Post Reports</div>
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
          <!-- <div  class="mb-4">
            <a href="{{ route('rules.new') }}" class="btn btn-primary"> + New Rule </a>
          </div> -->
          <div class="table-responsive">
            <div id="deleteRes" class="text-success"></div>
            <table id="example" class="table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th>SN</th>
                  <th>Post Title</th>
                  <!-- <th>Rule ID</th> -->
                  <th>Created By</th>
                  <th>Content</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @php $sn = 1; @endphp
                @foreach($reports as $report)
                  <tr>
                    <td>{{ $sn }}</td>
                    @php
                        $post = \App\Models\Post::where('id',$report->post_id)->first();
                    @endphp
                    <td>{{ $post->title }}</td>
                    <!-- <td>{{ $report->rule_id }}</td> -->
                    @php
                        $user = \App\Models\User::where('id',$report->user_id)->first();
                    @endphp
                    <td>{{ $user->firstname.' '.$user->lastname }}</td>
                    <td>{{ substr($report->content, 0, 70) }} @if(strlen($report->content) > 70) {{"..."}}@endif</td>
                    <td>
                        <div class="btn-group">
          								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Action</button>
          								<div class="dropdown-menu">
                            <a class="dropdown-item" href="{{ route('post-reports.view', ['reportId' => $report->id, 'userId' => $user->id]) }}">View</a>
                            <a class="dropdown-item text-danger" href="#" onclick="confirmDelete( {{ $report->id }} )">Delete</a>
          								</div>
          							</div>
                    </td>
                  </tr>
                  @php $sn++; @endphp
                @endforeach
              </tbody>
              <a href="{{ route('user.view', ['user' => $user->id]) }}" class="btn btn-secondary px-2">Back to user's Profile</a>
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



      function confirmDelete(id){
          const resp = confirm('Are you sure you want to delete selected Report? ');
          if(resp) {
              $.get('{{$appUrl}}/post-reports/delete/'+id, {

              },
              function(data) {
                  // jQuery('#deleteRes').html(data);

                  if(data == 'yes') {
                    // $('#deleteRes').html('Rule Deleted Successfully');
                    alert('Report Deleted Successfully');
                    location.reload(true);
                  } else {
                    // $('#deleteRes').html('Failed to delete Rule, Please try again');
                    alert('Failed to Delete Report');
                  }


                  // setTimeout(function () {
                  //   //valert('Reloading Page');
                  //   location.reload(true);
                  // }, 3000);

              });
          }
      }

      // $(document).ready(function () {
      //     setTimeout(function () {
      //       alert('Reloading Page');
      //       location.reload(true);
      //     }, 5000);
      //   });

    </script>
  @endsection
</x-app-layout>
