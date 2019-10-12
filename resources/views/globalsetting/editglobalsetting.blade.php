@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Edit General Setting</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('global-setting-management') }}">General Setting List</a>
                                </li>
                                <li class="breadcrumb-item active">edit general setting
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control">Edit Global Setting</h4>
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
                                        @if(Session::has('message'))
                                            <div class="alert alert-danger mb-2" role="alert">
                                                {{ Session::get('message')}}    
                                            </div>
                                        @endif
                                        <form class="form" action="{{ route('action-global-setting-edit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $siteGeneralSetting->id }}" name="id" />
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput3">Option Name</label>
                                                            <input type="text" id="userinput3" class="form-control" placeholder="Option Name" name="option_name" value="{{ $siteGeneralSetting->option_name }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput4">Option Value</label>
                                                            <input type="text" id="userinput4" class="form-control" placeholder="Option Value" name="option_value" value="{{ $siteGeneralSetting->type == '0' ? $siteGeneralSetting->option_value : ''}}" >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput4">OR</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                             @if($siteGeneralSetting->type == '1')
                                                                  <img src="{{$siteGeneralSetting->imageurl}}" height="150px" width="150px" />
                                                                @endif
                                                            <br/>    
                                                            <label for="userinput4">Option Image</label>
                                                            <input type="hidden" name="old_image" value="{{ $siteGeneralSetting->type == '1' ? $siteGeneralSetting->option_value : ''}}">
                                                            <input type="file" class="form-control-file" id="exampleInputFile" name = "option_image">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions text-right">
                                                <a href="{{ route('global-setting-management') }}" class="btn btn-warning mr-1">
                                                    <i class="ft-x"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> Save
                                                </button>
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