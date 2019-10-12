@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Add Church Event</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('event-management') }}">Event List</a>
                                </li>
                                <li class="breadcrumb-item active">add church event
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control">Add Church Event</h4>
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
                                        <form class="form" action="{{ route('action-event-add') }}" method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">   
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Select Church :</label>
                                                            <select class="c-select form-control" id="location1" name="user_id">
                                                                <option value="">Select Church</option>
                                                                @foreach($churchList as $church)
                                                                    <option value="{{ $church->id }}">{{ $church->firstname }} {{ $church->lastname }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1">Title</label>
                                                            <input type="text" name="title" id="userinput1" class="form-control" placeholder="Title" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-6">
                                                        <label for="issueinput3">Start Date & Time</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="start-event" name="start_date" />
                                                            <input type="text" class="form-control input-lg td-input" id="autoswitch" name="start_time" placeholder="Time Dropper" readonly="">
                                                        </div>
                                                        <!-- <input type="datetime-local" id="issueinput3" class="form-control" name="start_date" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Date Opened"> -->
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="issueinput4">End Date</label>
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><i class="ft-calendar"></i></span>
                                                            </div>
                                                            <input type="text" class="form-control" id="end-event" name="end_date" />
                                                            <input type="text" class="form-control input-lg td-input" id="autoswitch1" name="end_time" placeholder="Time Dropper" readonly="">
                                                        </div>
                                                        <!-- <input type="datetime-local" id="issueinput4" class="form-control" name="end_date" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Date Fixed"> -->
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-12 mb-2">
                                                        <label for="issueinput8">Description</label>
                                                        <textarea id="issueinput8" rows="5" class="form-control" name="description" placeholder="Description" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Comments" data-original-title="" title=""></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions text-right">
                                                <a href="{{ route('event-management') }}" class="btn btn-warning mr-1">
                                                    <i class="ft-x"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary submit-event">
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