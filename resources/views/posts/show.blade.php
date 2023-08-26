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
        <div class="breadcrumb-title pr-3">{{ $post->title }} - Likes ({{$postLikes}})</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item" aria-current="page"><a href="{{ route('admin.posts')}}">Posts</a></li>
              <li class="breadcrumb-item active" aria-current="page">View Post</li>
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
          <form method="post" action="{{ route('admin.posts.update', ['post' => $post->id]) }}">
            @csrf
            <div class="form-row">
              <div class="form-group col-md-12">
                <label>Post Title</label>
                <div class="input-group">
                  <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-edit'></i></span>
                  </div>
                  <input type="text" class="form-control border-left-0" placeholder="" name="title" required value="{{ old('title') ? old('title') : $post->title  }}">
                </div>
              </div>
            </div>

            <textarea id="mytextarea" name="content" style="height:400px !important;">{{ old('content') ? old('content') : $post->content  }}</textarea>

            <div class="form-row">
              <div class="form-group col-md-12">
                <button type="submit" class="btn btn-primary px-3 mt-4">Update Post</button>
              </div>
            </div>


          </form>
        </div>

      </div>

      <div class="card">

        <div class="card-body">
          <div class="card-title">
            <h4 class="mb-4">Comments</h4>
            @php $x = 0; @endphp
            @forelse ($comments as $comment)
              <div class="col-12 col-lg-12 col-xl-12">
                <div class="card bg-primary radius-15">
                  <div class="card-body">
                    <h5 class="card-title text-white">{{ $comment->firstname. ' '. $comment->lastname}}
                      <span class="text-white" style="float:right !important; font-size:12px;">{{ date('j-M-Y h:iA', strtotime($comment->created_at)) }}</span>
                    </h5>

                    <!-- <h6 class="card-subtitle mb-2 text-white">Card subtitle</h6> -->
                    <p class="text-white">{{ $comment->content }}</p>
                    @php
                      $likes = \App\Models\CommentLike::where('comment_id',$comment->comment_id)->count();
                      $commentReplies = \App\Models\CommentReply::where('comment_id',$comment->comment_id)->latest()->get();
                    @endphp
                    <a href="#" class="card-link text-white">Likes - {{ $likes }}</a>
                  </div>

                  @forelse($commentReplies as $commentReply)
                    @php
                      if($commentReply->user_id == 0) {
                          $user = 'Admin';
                      } else {
                          $user = \App\Models\User::where('id', $commentReply->user_id)->first();
                      }
                    @endphp
                    <div class="col-12 col-lg-12 col-xl-12">
                      <div class="card bg-dark radius-15">
                        <div class="card-body">
                          <h6 class="card-subtitle mb-2 text-white">
                            @if($user == 'Admin')
                              Admin
                            @else
                              {{ $user->firstname. ' '. $user->lastname }}
                            @endif
                            <span style="float:right !important; font-size:12px;">{{ date('j-M-Y h:iA', strtotime($commentReply->created_at)) }}</span>
                          </h6>
                          <p class="card-text text-white">{{ strip_tags($commentReply->content) }}</p>
                          <a href="{{ route('admin.posts.delete-reply', ['reply' => $commentReply->id] )}}" class="btn btn-warning"> X Delete Reply</a>
                        </div>
                      </div>
                    </div>
                  @empty
                  @endforelse

                  <div>
                    <form method="post" action="{{ route('admin.posts.reply-comment', ['comment' => $comment->comment_id]) }}">
                        @csrf
                        <textarea id="mytextarea{{$x}}" name="commentReply{{$comment->comment_id}}" style="height:200px !important;"></textarea>
                        <div class="form-row">
                          <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary px-3 mt-4">Reply Comment</button>
                            <a href="{{ route('admin.posts.delete-comment', ['comment' => $comment->comment_id]) }}" class="btn btn-danger px-3 mt-4"> X Delete Comment</a>
                          </div>
                        </div>
                    </form>
                  </div>

                </div>
              </div>
              @php $x++; @endphp
            @empty
                <p>No Comments found</p>
            @endforelse

          </div>
          <hr/>
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
    <script src='https://cdn.tiny.cloud/1/to0t6nustig4dk0jkgg4sd0046qwhhhrdm1e7ptkknfbqits/tinymce/5/tinymce.min.js' referrerpolicy="origin"></script>
    <script>
      tinymce.init({
        selector: '#mytextarea',
        plugins: 'media',
        menubar: 'insert',
        media_filter_html: false
      });
    </script>
    @for($y=0; $y<=$x; $y++)
      <script>
        tinymce.init({
          selector: '#mytextarea{{$y}}',
          plugins: 'media',
          menubar: 'insert',
          media_filter_html: false
        });
      </script>
    @endfor

  @endsection
</x-app-layout>
