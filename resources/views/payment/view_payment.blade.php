@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">View Payment / Donation</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('payment-management') }}">Payment / Donation</a>
                                </li>
                                <li class="breadcrumb-item active">view payment / donation
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                
                <!-- Basic form layout section start -->
                <section id="horizontal-form-layouts">
                    <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h4 class="card-title" id="horz-layout-colored-controls">Payment / Donation Information</h4>
                                        <a class="heading-elements-toggle"><i class="la la-ellipsis-v font-medium-3"></i></a>
                                        <div class="heading-elements">
                                            <ul class="list-inline mb-0">
                                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                                <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="card-content collpase show">
                                        <div class="card-body">
                                            <form class="form form-horizontal">
                                                <div class="form-body">
                                                    <h4 class="form-section"><i class="la la-eye"></i> View Donor Detail</h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput1">Donor Name:</label>
                                                                <label class="label-control" for="userinput1">{{ $user->firstname }} {{ $user->lastname }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput2">Donor Email Address:</label>
                                                                <label class="label-control" for="userinput2">{{ $user->email }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput3">Donor Mobile Number:</label>
                                                                <label class="label-control" for="userinput3">{{ $user->mobile }}</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="la la-eye"></i> View Project Detail </h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput4">Project Name:</label>
                                                                <label class="label-control" for="userinput4">{{ $project->name }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput5">Project Goal Amount:</label>
                                                                <label class="label-control" for="userinput5">{{ $project->goal_amount }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput4">Start Date:</label>
                                                                <label class="label-control" for="userinput4">{{ $project->startdate }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput6">End Date:</label>
                                                                <label class="label-control" for="userinput6">{{ $project->enddate }}</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <h4 class="form-section"><i class="la la-eye"></i> About Payment / Donation </h4>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput7">Amount:</label>
                                                                <label class="label-control" for="userinput7">{{ $projectPaymentDonation->amount }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput8">Payment Status:</label>
                                                                <label class="label-control" for="userinput8">{{ $projectPaymentDonation->payment_status }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput9"> Payment Gateway:</label>
                                                                <label class="label-control" for="userinput9">{{ $projectPaymentDonation->payment_gateway_type }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput10"> Payment Transaction ID:</label>
                                                                <label class="label-control" for="userinput10">{{ $projectPaymentDonation->payment_transaction_id }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput11"> QR Scanned Verified:</label>
                                                                <label class="label-control" for="userinput11">{{ $projectPaymentDonation->qr_scanned_verified }}</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group row">
                                                                <label class="col-md-5 label-control" for="userinput12"> Payment Date:</label>
                                                                <label class="label-control" for="userinput12">{{ $projectPaymentDonation->created_at }}</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-actions text-right">
                                                    <a href="{{ route('payment-management') }}" class="btn btn-warning mr-1">
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