<x-app-layout>
  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">New Admin</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="javascript:;"><i class='bx bx-home-alt'></i></a>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Admins</li>
            </ol>
          </nav>
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
              <form method="post" action="{{ route('admin.create') }}">
                  @csrf
                  <div class="form-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>First Name</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="text" class="form-control border-left-0" placeholder="" name="firstname" required value="{{ @old('firstname') }}">
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Last Name</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="text" class="form-control border-left-0" placeholder="" name="lastname" required value="{{ @old('lastname') }}">
                        </div>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Email Address</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-envelope'></i></span>
                          </div>
                          <input type="email" class="form-control border-left-0" placeholder="" name="email" required value="{{ @old('email')}}">
                        </div>
                      </div>
                      <div class="form-group col-md-6">
                        <label>Role</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <select class="form-control border-left-0" required name="web_role_id">
                              <option value="">-- Select Role --</option>
                              @foreach($webRoles as $webRole)
                                <option value="{{ $webRole->id }}"
                                  @if($webRole->id == @old('web_role_id'))
                                    {{ 'selected' }}
                                  @endif
                                  >{{ $webRole->display_name }}</option>
                              @endforeach
                          </select>
                        </div>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-5">Register</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
