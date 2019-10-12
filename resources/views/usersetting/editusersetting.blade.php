@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Edit User Setting</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('user-setting-management') }}">User Settings</a>
                                </li>
                                <li class="breadcrumb-item active">edit user setting
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-colored-form-control">Edit User Setting</h4>
                                    <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <form class="form" action="{{ route('action-user-setting-edit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $userSetting->id }}" />
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput1">User Name</label>
                                                            <input type="text" id="userinput1" class="form-control" placeholder="User Name" value="{{ $user->firstname }} {{ $user->lastname }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput1">Field Name</label>
                                                            <input type="text" id="userinput1" class="form-control" placeholder="Field Name" value="{{ $userSetting->field_name }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Field Value :</label>
                                                            @if($userSetting->field_name == 'NOTIFICATION_OF')
                                                                <select class="c-select form-control is_church" id="" name="field_value">
                                                                    <option value="">Select Notification Type</option>
                                                                    @foreach($notificationOptions as $notificationOption)
                                                                        <option   value="{{ $notificationOption->id }}" {{ $notificationOption->id == $userSetting->field_value ? 'selected' : ''}}>{{ $notificationOption->title }}</option>
                                                                    @endforeach
                                                                </select>
                                                            @else
                                                                <div class="c-inputs-stacked">
                                                                    <div class="d-inline-block custom-control custom-checkbox">
                                                                        <input type="radio" name="field_value" class="custom-control-input" id="enable1" value="enable" {{ $userSetting->field_value == 'enable'  ? 'checked' : ''}}>
                                                                        <label class="custom-control-label" for="enable1">Enable</label>
                                                                    </div>
                                                                    <div class="d-inline-block custom-control custom-checkbox">
                                                                        <input type="radio" name="field_value" class="custom-control-input" id="disable1" value="disable" {{ $userSetting->field_value == 'disable'  ? 'checked' : ''}}>
                                                                        <label class="custom-control-label" for="disable1">Disable</label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-actions text-right">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> Save
                                                </button>
                                                <a href="{{ route('user-setting-management') }}" class="btn btn-warning mr-1">
                                                    <i class="ft-x"></i> Cancel
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    <!-- END: Content-->
    @endsection