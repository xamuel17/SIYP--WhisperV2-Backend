<x-app-layout>
  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">Edit Sex Offender</div>
        <div class="pl-3">
          <!-- <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item" aria-current="page">Sex Offenders</li>
            </ol>
          </nav> -->
        </div>
      </div>
      <!--end breadcrumb-->
      <div class="row">
        <div class="col-12 col-lg-12">
          <div class="card border-lg-top-primary">
            <div class="card-body p-5">
              <!-- <div class="card-title d-flex align-items-center">
                <div><i class='bx bxs-user mr-1 font-24 text-primary'></i>
                </div>
                <h4 class="mb-0 text-primary">Admin Registration</h4>
              </div> -->
              @include('components.flash-message')
              <form method="post" action="{{ route('offenders.edit-action', ['offender' => $offender->id]) }}" enctype="multipart/form-data">
                  @csrf
                  <div class="form-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Offender Name</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="text" class="form-control border-left-0" placeholder="" name="offender_name" required value="{{ @old('offender_name') ?? $offender->offender_name }}">
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Title</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="text" class="form-control border-left-0" placeholder="" name="title" required value="{{ @old('title') ?? $offender->title }}">
                        </div>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Offence</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-envelope'></i></span>
                          </div>
                          <input type="text" class="form-control border-left-0" placeholder="" name="offence" required value="{{ @old('offence') ?? $offender->offence }}">
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Photo</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"></span>
                          </div>
                          <input type="file" class="form-control border-left-0" name="photo">
                          <img src="{{ asset('/blog/public/offenders-photo/'.$offender->photo) }}" width="80px" height="80px" />
                        </div>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label>Content</label>
                          <textarea class="form-control" name="content" required id="mytextarea" style="height:400px !important;">
                              {{ @old('content') ?? $offender->content }}
                          </textarea>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Source Url</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"></span>
                          </div>
                          <input type="text" class="form-control border-left-0" placeholder="" name="source_url" value="{{ @old('source_url') ?? $offender->source_url }}">
                        </div>
                      </div>
                    </div>



                    <button type="submit" class="btn btn-primary px-5">Save</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @section('more-scripts')
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
