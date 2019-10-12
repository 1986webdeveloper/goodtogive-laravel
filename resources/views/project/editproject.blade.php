@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Update Project</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('project-management') }}">Projects</a>
                                </li>
                                <li class="breadcrumb-item active">update project
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
                                    <h4 class="card-title">Edit Project Wizard</h4>
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
                                       
                                        <form action="{{ route('action-project-edit') }}" method = "post" class="icons-tab-steps wizard-notification" id="target">
                                            @csrf
                                            <input type="hidden" value="{{$project->id}}" name="id">
                                            <!-- Step 1 -->
                                            <h6>Step 1</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Church Name :</label>
                                                            <select class="c-select form-control church_name" id="location1" name="church_id">
                                                                <option value="">Select Church</option>
                                                                @foreach($churchList as $church)
                                                                    <option value="{{ $church->id }}" {{ $project->church_id == $church->id ? 'selected' : ''}}>{{ $church->firstname }} {{ $church->lastname }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="lastName1">Church Fund Name :</label>
                                                            <select class="c-select form-control" id="fund_name" name="church_fund_id">
                                                                <option value="">Select Fund Name</option>
                                                                @foreach($fundNames as $fundName)
                                                                    <option value="{{ $fundName->id }}" {{ $project->church_fund_id == $fundName->id ? 'selected' : ''}}>{{ $fundName->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name1">Project Name :</label>
                                                            <input type="text" class="form-control" id="name1" name="name"  value="{{ $project->name }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="amount1">Goal Amount :</label>
                                                            <input type="text" class="form-control" id="amount1" name="goal_amount" value="{{ $project->goal_amount }}" onkeypress="return KeycheckOnlyNumeric(event);">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="startdate1">Start Date :</label>
                                                            <input type="date" class="form-control" id="startdate1" name="startdate" value="{{ $project->startdate }}">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="date1">End Date :</label>
                                                             <?php if($project->enddate == '0000-00-00 00:00:00'){ ?>
                                                            <input type="date" class="form-control" id="enddate1" name="enddate">
                                                            <?php }else{ ?>
                                                                 <input type="date" class="form-control" id="enddate1" name="enddate" value="{{$project->enddate}}" >
                                                            
                                                            <?php } ?>

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
                                                            <label>Need to scan qr :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="need_to_scan_qr" class="custom-control-input" id="enable1" value="enable" {{ $project->need_to_scan_qr == 'enable' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="enable1">Enable</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="need_to_scan_qr" class="custom-control-input" id="disable1" value="disable" {{ $project->need_to_scan_qr == 'disable' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="disable1">Disable</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Donation slab custom amount :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="donation_slab_custom_amount" class="custom-control-input" id="true1" value="true" {{ $project->donation_slab_custom_amount == 'true' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="true1">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="donation_slab_custom_amount" class="custom-control-input" id="false1" value="false" {{ $project->donation_slab_custom_amount == 'false' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="false1">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Show Goal Amount on Screen :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_goal_amount" class="custom-control-input" id="yes1" value="Y" {{ $project->is_goal_amount == 'Y' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="yes1">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_goal_amount" class="custom-control-input" id="no1" value="N" {{ $project->is_goal_amount == 'N' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="no1">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                      <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Show Donation Amount on Screen :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_donated_amount" class="custom-control-input" id="yes12" value="Y" {{ $project->is_donated_amount == 'Y' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="yes12">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_donated_amount" class="custom-control-input" id="no12" value="N" {{ $project->is_donated_amount == 'N' ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="no12">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group">
                                                            <label for="shortDescription1">Description :</label>
                                                            <textarea name="description" id="shortDescription" rows="4" class="form-control">{{ $project->description }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>

                                            <!-- Step 2 -->
                                            <h6>Step 3</h6>
                                            <fieldset>
                                                <div class="row">
                                                   
                                                    <div class="col-md-12">
                                                        <div class="form-group col-12 mb-2 contact-repeater required-error">
                                                            <div data-repeater-list="repeater">
                                                                @foreach($project_donation_slabs as $project_donation_slabss)
                                                                <div class="input-group mb-1" data-repeater-item>
                                                                    <input type="number" name="fundprice" placeholder="Price" class="form-control" id="example-tel-input" value="{{$project_donation_slabss->amount}}" required>
                                                                    <span class="input-group-append" id="button-addon2">
                                                                        <button class="btn btn-danger" type="button" data-repeater-delete><i class="ft-x"></i></button>
                                                                    </span>
                                                                </div>
                                                                @endforeach
                                                            </div>

                                                            <button type="button" data-repeater-create class="btn btn-primary">
                                                                <i class="ft-plus"></i> Add New Donation Slab
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <h6>Step 4</h6>
                                            <fieldset>
                                                <div class="row">
                                                 <div class="col-md-12">
                                                        <div class="form-group col-12 mb-2 file-repeater">
                                                             <div class="card-content collapse show">
                                                            <div class="card-body">
                                                                <div  class="dropzone dropzone-area " id="dpz-multiple-files"></div>
                                                                  <div class="dropzone-previews"></div>
                                                                <div  class="dropzone dropzone-area" id="dpz-multiple-files">
                                                                    <div class="dz-message">Drop Files Here To Upload</div>
                                                                    <input id="file" name="project_image[]" style="height:auto !important;" type="file" multiple>
                                                                  
                                                                </div>
                                                                <div class="display-error-after"></div>
                                                            </div>
                                                        </div>
                                                     
                                                          
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

