@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Edit User Task</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('task-management') }}">Task List</a>
                                </li>
                                <li class="breadcrumb-item active">edit user task
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
                                    <h4 class="card-title" id="basic-layout-colored-form-control">Edit User Task</h4>
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
                                        
                                        <form class="form" action="{{ route('action-task-edit') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $task->id }}">
                                               @foreach($taskgroupdetailmember as $user)
                                             <input type="hidden" name="userid[]" value="{{$user}}">
                                             @endforeach
                                               @foreach($taskgroupdetailchurchmember as $member)
                                             <input type="hidden" name="member[]" value="{{$member}}">
                                             @endforeach
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Select User :</label>
                                                            <select class="c-select  user-select form-control" id="location1" name="user_id">
                                                                <option value="">Select User</option>
                                                                @foreach($userList as $user)
                                                                    <option value="{{ $user->id }}" {{ $user->id == $task->user_id ? 'selected' : ''}}>{{ $user->firstname }} {{ $user->lastname }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Select Priority :</label>
                                                            <select class="c-select form-control" id="location1" name="priority_id">
                                                                <option value="">Select Priority</option>
                                                                @foreach($taskPriority as $priority)
                                                                    <option value="{{ $priority->id }}" {{ $priority->id == $task->priority_id ? 'selected' : ''}}>{{ $priority->title }}</option>
                                                                @endforeach
                                                            </select>
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Select Group :</label>
                                                            <select class="c-select form-control" id="location2" name="group_list[]" multiple="">
                                                                
                                                            </select>
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="userinput1">Title</label>
                                                            <input type="text" name="title" id="userinput1" class="form-control" placeholder="Title" value="{{ $task->title }}">
                                                            <span class="focus-border"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                   
                                                    <div class="form-group col-md-6">
                                                        <label for="issueinput3">Date</label>
                                                        <input type="text" class="form-control" id="edit-task-date" name="date" placeholder="Date Dropper" readonly="readonly"
                                                        value="@if (isset($task->date)){!!date('d-m-Y', strtotime($task->date))!!}@endif">
                                                        <span class="focus-border"></span>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group col-md-12">
                                                        <label for="issueinput8">Description</label>
                                                        <textarea id="issueinput8" rows="5" class="form-control" name="description" placeholder="Description" data-toggle="tooltip" data-trigger="hover" data-placement="top" data-title="Task Description" data-original-title="" title="">{{ $task->description }}</textarea>
                                                        <span class="focus-border"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-actions text-right">
                                                <a href="{{ route('task-management') }}" class="btn btn-warning mr-1">
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