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
        <div class="breadcrumb-title pr-3">New Post</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.posts')}}">Posts</a></li>
              <li class="breadcrumb-item active" aria-current="page">New Posts</li>
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
            <h4 class="mb-0">Form text editor</h4>
          </div>
          <hr/> -->
          <form method="post" action="{{ route('admin.posts.store') }}">
            @csrf
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>Post Title</label>
                <div class="input-group">
                  <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                  </div>
                  <input type="text" class="form-control border-left-0" placeholder="" name="title" required value="{{ @old('title') }}">
                </div>
              </div>
            </div>

            <!-- <textarea id="mytextarea" name="content" style="height:400px !important;"></textarea> -->

            <textarea id="mytextarea" name="content" style="height:400px !important;">
             {{ @old('content')}}
           </textarea>

            <div class="form-row">
              <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary px-3 mt-4">Submit Post</button>
              </div>
            </div>


          </form>
        </div>

      </div>

    </div>
  </div>

  @section('more-scripts')
    <!--Data Tables js-->
    <!-- <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script> -->

    <!-- <script>
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
  	</script> -->

    <script src='https://cdn.tiny.cloud/1/to0t6nustig4dk0jkgg4sd0046qwhhhrdm1e7ptkknfbqits/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#mytextarea',
        plugins: 'media',
        menubar: 'insert',
        media_filter_html: false
      });
    </script>
  @endsection
</x-app-layout>
