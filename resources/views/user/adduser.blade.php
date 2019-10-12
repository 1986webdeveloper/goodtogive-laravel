@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Add User</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('user-management') }}">Users</a>
                                </li>
                                <li class="breadcrumb-item active">add user
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Form wizard with number tabs section start -->
                <section id="number-tabs">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Add User</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-h font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                       
                                        <form action="{{ route('action-user-add') }}" method = "post" class="number-tab-steps wizard-notification" role="application" id="target" novalidate="novalidate" id="target">
                                            @csrf
                                            <!-- Step 1 -->
                                            <h6>Step 1</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">First Name :</label>
                                                            <input type="text" class="form-control text_field_validation" id="firstName1" placeholder="First Name" name="firstname">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastName1">Last Name :</label>
                                                            <input type="text" class="form-control text_field_validation" id="lastName1" placeholder="Last Name" name="lastname">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Email Address:</label>
                                                            <input type="text" class="form-control" id="email1" placeholder="Email Address" name="email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mobile1">Mobile Number:</label>
                                                            <input type="text" class="form-control" id="mobile1" name="mobile" placeholder="Mobile Number"  maxlength="10" onkeypress="return KeycheckOnlyNumeric(event);">
                                                        </div>
                                                    </div>
                                                </div>
                                                    <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Date of Birth :</label>
                                                            <input type="date"  class="form-control" id="dob" name="dob">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password1">Password :</label>
                                                            <input type="password" class="form-control" id="password1" placeholder="Password" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password2">Confirm Password :</label>
                                                            <input type="password" class="form-control" id="password2" placeholder="Confirm Password" name="cpassword">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Step 2 -->
                                            <h6>Step 2</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userrole1">Select User Role :</label>
                                                            <select class="c-select form-control is_church" id="user_role_id" onchange="getval(this);" name="user_role_id">
                                                                <option value="">Select User Role</option>
                                                                @foreach($user_roles as $user_role)
                                                                    <option value="{{ $user_role->id }}">{{ $user_role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userrole2">Select Church :</label>
                                                            <select class="c-select form-control"  name="church_id">
                                                                <option value="">Select Church </option>
                                                                @foreach($church as $userlist)
                                                                    <option value="{{ $userlist->id }}">{{ $userlist->firstname }} {{ $userlist->lastname }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="file-loading">
                                                            <input id="user_image" name="image" type="file" >
                                                        </div>
                                                    </div>
                                                </div>
                                            </br>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingAddress1">First Billing Address :</label>
                                                            <input type="text" class="form-control" id="billingAddress1" placeholder="First Billing Address" name="billingAddress1">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingAddress2">Second Billing Address :</label>
                                                            <input type="text" class="form-control" id="billingAddress2" placeholder="Second Billing Address " name="billingAddress2">
                                                        </div>
                                                    </div>
                                              
                                                    <div class="col-md-6" style="display: none;">
                                                        <div class="form-group">
                                                            <label for="billingState">Billing State :</label>
                                                            <input type="text" class="form-control" id="billingState" value="England" placeholder="Billing State" name="billingState">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingCity">Billing City:</label>
                                                            <input type="text" class="form-control" id="billingCity" name="billingCity" placeholder="Billing City">
                                                        </div>
                                                    </div>
                                             
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingPostcode">UK Postal Code :</label>
                                                            <input type="text" class="form-control" id="billingPostcode" placeholder="Billing Postcode" name="billingPostcode">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Step 3 -->
                                            <h6>Step 3</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Vibration :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="VIBRATION" class="custom-control-input" id="enable1" value="enable">
                                                                    <label class="custom-control-label" for="enable1">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="VIBRATION" class="custom-control-input" id="disable1" value="disable" checked>
                                                                    <label class="custom-control-label" for="disable1">Disable</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Coloured Blurred :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="COLOURED_BLURED" class="custom-control-input" id="true1" value="enable">
                                                                    <label class="custom-control-label" for="true1">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="COLOURED_BLURED" class="custom-control-input" id="false1" value="disable" checked>
                                                                    <label class="custom-control-label" for="false1">Disable</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Cvv Save :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="CVV_CARD" class="custom-control-input" id="true2" value="enable" checked>
                                                                    <label class="custom-control-label" for="true2">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="CVV_CARD" class="custom-control-input" id="false2" value="disable" >
                                                                    <label class="custom-control-label" for="false2">Disable</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="notification_of1">Notification Of :</label>
                                                            <select class="c-select form-control" id="notification1" name="NOTIFICATION_OF">
                                                                <option value="">Select Notification Type</option>
                                                                @foreach($userOption as $option)
                                                                    <option value="{{ $option->id }}">{{ $option->title }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Form wizard with number tabs section end -->
            </div>
        </div>
    <!-- END: Content-->
    @endsection