@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">View Qr Code</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('church-management') }}">Church</a>
                                </li>
                                <li class="breadcrumb-item active">View church qr code
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control">View Qr Code</h4>
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
                                        <form class="form" action="{{ route('action-project-slab-edit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" value="{{ $user->id }}" name="id" id="user_id" />
                                           
                                            <div class="form-body">
                                               
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="userinput1">Church Name</label>
                                                            <input type="text" id="userinput3" class="form-control" placeholder="User Name" value="{{ $user->firstname }} {{ $user->lastname }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="qrcode">Member Qr Code</label>
                                                            <input type="text" id="qrcode" class="form-control" placeholder="Qr Code" value="{{ $user->qrcode }}" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group" id="download_png">
                                                            @if($user->qrcode != "")
                                                                <label for="userinput3">Member Qr Code Image</label>
                                                                <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(300)->generate($user->qrcode))!!} ">
                                                                
                                                                <div class="text-left">
                                                                    <a href="data:image/png;base64, {!! base64_encode(QrCode::format('png')->margin(1)->size(300)->generate($user->qrcode))!!}"  class="btn btn-primary"id="download_qr_code" download="{{ $user->firstname }}-{{ $user->id }}">
                                                                        <i class="la la-check-square-o"></i> Download Member Qr Code
                                                                    </a>
                                                                </div>
                                                            @else
                                                                <div class="text-left">
                                                                    <a href="#"  class="btn btn-primary" id="user_generate_qr_code">
                                                                        <i class="la la-check-square-o"></i> Generate Member Qr Code
                                                                    </a>
                                                                </div>
                                                            @endif
                                                            <!-- <div class="text-left">
                                                                <a href="#"  class="btn btn-warning mr-1">
                                                                    <i class="ft-x"></i> Download Image
                                                                </a>
                                                            </div> -->
                                                        </div>
                                                    </div>
                                                </div>
                                              
                                            </div>
                                            <div class="form-actions text-right">
                                                <a href="{{ route('church-management') }}" class="btn btn-warning mr-1">
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