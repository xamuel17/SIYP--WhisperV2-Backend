<x-app-layout>
  <div class="page-content-wrapper">
    <div class="page-content">
      <!--breadcrumb-->
      <div class="page-breadcrumb d-none d-md-flex align-items-center mb-3">
        <div class="breadcrumb-title pr-3">Change Password</div>
        <div class="pl-3">
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class='bx bx-home-alt'></i></a>
              </li>
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
              <form method="post" action="{{ route('admin.change-password') }}">
                  @csrf
                  <div class="form-body">
                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Current Password</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="password" class="form-control border-left-0" placeholder="" name="current_password" required>
                        </div>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>New Password</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="password" class="form-control border-left-0" placeholder="" name="new_password" required>
                        </div>
                      </div>
                    </div>

                    <div class="form-row">
                      <div class="form-group col-md-6">
                        <label>Confirm New Password</label>
                        <div class="input-group">
                          <div class="input-group-prepend">	<span class="input-group-text bg-transparent"><i class='bx bx-user'></i></span>
                          </div>
                          <input type="password" class="form-control border-left-0" placeholder="" name="confirm_new_password" required>
                        </div>
                      </div>
                    </div>

                    <button type="submit" class="btn btn-primary px-5">Change Password</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
