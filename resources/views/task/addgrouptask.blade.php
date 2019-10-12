@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Add User Task Group</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('task-group-management') }}">Task Group List</a>
                                </li>
                                <li class="breadcrumb-item active">add user task group
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control">Add User Task Group</h4>
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
                                        
                                        <form class="form" action="{{ route('action-group-add-task') }}" method="POST">
                                            @csrf
                                            <div class="form-body">
                                                <div class="row">
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1">Title</label>
                                                            <input type="text" name="group_title" id="userinput1" class="form-control" placeholder="Title" value="">
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Select User :</label>
                                                            <select class="c-select form-control" id="location1" name="church_id">
                                                                <option value="">Select User</option>
                                                                @foreach($userList as $user)
                                                                    <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                
                                                    
                                                </div>
                                                <div class="row">
                                                       <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Select Group member :</label>
                                                                <select name="user_id[]" id="group_user" class="form-control" multiple="">
                                                                    </select>
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                 </div>
                                                
                                            </div>
                                            <div class="form-actions text-right">
                                                <a href="{{ route('task-group-management') }}" class="btn btn-warning mr-1">
                                                    <i class="ft-x"></i> Cancel
                                                </a>
                                                <button type="submit" class="btn btn-primary submit-group-task">
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