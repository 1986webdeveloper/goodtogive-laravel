@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <style>
        button.kv-file-remove.btn.btn-sm.btn-kv.btn-default.btn-outline-secondary {
        display: none;
        }
    </style>
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Edit User</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb"> 
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('user-management') }}">Users</a>
                                </li>
                                <li class="breadcrumb-item active">edit user
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
                                    <h4 class="card-title">Edit User</h4>
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
                                       
                                        <form action="{{ route('action-user-edit') }}" method = "post" class="edit-user-submit wizard-notification" id="target">
                                            @csrf
                                            <!-- Step 1 -->

                                            
                                            <input type="hidden" value="{{ $user->id }}" name = "id" id="userid">
                                            <h6>Step 1</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">First Name :</label>
                                                            <input type="text" class="form-control" id="firstName1" name="firstname" value="{{ $user->firstname }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastName1">Last Name :</label>
                                                            <input type="text" class="form-control" id="lastName1" name="lastname" value="{{ $user->lastname }}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Email :</label>
                                                            <input type="text" class="form-control" id="email1" name="email" value="{{ $user->email }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mobile1">Mobile :</label>
                                                            <input type="text" class="form-control" id="mobile1" name="mobile" maxlength="10" onkeypress="return KeycheckOnlyNumeric(event);"  value="{{ $user->mobile }}">
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
                                                                    <option value="{{ $user_role->id }}" {{ $user_role->id == $user->user_role_id ? 'selected' : ''}}>{{ $user_role->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userrole2">Select Church :</label>
                                                            <select class="c-select form-control is_church" id="church_list" name="church_id">
                                                                <option value="">Select Church </option>
                                                                @if($user->user_role_id != 3)
                                                                    @foreach($churchList as $churchinfo)
                                                                        <option value="{{ $churchinfo->id }}" {{ $user->church_id == $churchinfo->id ? 'selected' : ''}}>{{ $churchinfo->firstname }} {{ $churchinfo->lastname }}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="file-loading">
                                                            <input id="edit_user_image" name="image" type="file"  />
                                                        </div>
                                                        <!-- <img src = "{{ $user->image }}" style="height: 100px;width: 100px;border: 0;border-radius: 30px;" /> -->
                                                    </div>
                                                </div>
                                                </br>
                                                 <div class="row" id="selectdonor" <?php if($user->user_role_id != 2){ ?> style="display: none;" <?php } ?>>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingAddress1">First Billing Address :</label>
                                                            <input type="text" class="form-control" id="billingAddress1" value="{{$useraddressdetails['billingAddress1']}}" placeholder="First Billing Address" name="billingAddress1">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingAddress2">Second Billing Address :</label>
                                                            <input type="text" class="form-control" id="billingAddress2" value="{{$useraddressdetails['billingAddress2']}}" placeholder="Second Billing Address " name="billingAddress2">
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingState">Billing State :</label>
                                                            <input type="text" class="form-control" id="billingState" value="{{$useraddressdetails['billingState']}}" placeholder="Billing State" name="billingState">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingCity">Billing City:</label>
                                                            <input type="text" value="{{$useraddressdetails['billingCity']}}" class="form-control" id="billingCity" name="billingCity" placeholder="Billing City">
                                                        </div>
                                                    </div>
                                               
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="billingPostcode">Billing Postcode :</label>
                                                            <input type="text" value="{{$useraddressdetails['billingPostcode']}}" class="form-control" id="billingPostcode" placeholder="Billing Postcode" name="billingPostcode">
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
                                                                    <input type="radio" name="VIBRATION" class="custom-control-input" id="enable1" value="enable" {{ $vibration == "enable" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="enable1">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="VIBRATION" class="custom-control-input" id="disable1" value="disable" {{ $vibration == "disable" ? 'checked' : ''}}>
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
                                                                    <input type="radio" name="COLOURED_BLURED" class="custom-control-input" id="true1" value="enable" {{  $coloured_blur == "enable" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="true1">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="COLOURED_BLURED" class="custom-control-input" id="false1" value="disable" {{ $coloured_blur == "disable" ? 'checked' : ''}}>
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
                                                                    <input type="radio" name="CVV_CARD" class="custom-control-input" id="true2" value="enable" {{ $cvv_card == "enable" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="true2">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="CVV_CARD" class="custom-control-input" id="false2" value="disable" {{ $cvv_card == "disable" ? 'checked' : ''}}>
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
                                                                    <option value="{{ $option->id }}" {{ $option->id == $notification ? 'selected' : ''}}>{{ $option->title }}</option>
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