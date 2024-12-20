<x-app-layout>
  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">View Report</div>
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
              <form method="post" action="{{ route('rules.create') }}">
                  @csrf
                  <div class="form-body">

                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label><b>Created By</b></label>
                      @php
                          $user = \App\Models\User::where('id',$report->user_id)->first();
                      @endphp
                      <p>{{ $user->firstname.' '.$user->lastname }}</p>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group col-md-12">
                      <label><b>Rule</b></label>
                      @php
                          $rule = \App\Models\Rule::where('id',$report->rule_id)->first();
                      @endphp
                      <p>{{ $rule->content ?? '' }}</p>
                    </div>
                  </div>

                    <div class="form-row">
                      <div class="form-group col-md-12">
                        <label><b>Report</b></label>
                        <p>{{ $report->content }}</p>
                      </div>
                    </div>

                    @if(isset($userId))
                        <a href="{{ route('post-reports.user.view', ['userId' => $userId]) }}" class="btn btn-primary px-2">Back to User's Reports</a>
                        <a href="{{ route('user.view', ['user' => $userId]) }}" class="btn btn-secondary px-2">Back to user's Profile</a>
                    @else
                        <a href="{{ route('post-reports') }}" class="btn btn-primary px-5">Back to All Reports</a>
                    @endif


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
