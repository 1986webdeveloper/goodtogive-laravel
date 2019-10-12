@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Edit Church</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('church-management') }}">Church</a>
                                </li>
                                <li class="breadcrumb-item active">edit church
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
                                    <h4 class="card-title">Edit Church</h4>
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
                                       
                                        <form action="{{ route('action-church-edit') }}" method = "post" class="edit-user-submit wizard-notification" id="target">
                                            @csrf
                                            <!-- Step 1 -->
                                            <input type="hidden" value="{{ $user->id }}" name = "id" id="userid">
                                            <h6>Step 1</h6>
                                            <fieldset>
                                               
                                                <div class="row">
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Reference ID :</label>
                                                            <input type="text" class="form-control" id="church_reference_id" name="church_reference_id" value="{{ $user->church_reference_id }}"  onkeypress="return KeycheckOnlyNumeric(event);">
                                                        </div>
                                                    </div> 
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Church Name :</label>
                                                            <input type="text" class="form-control" id="firstName1" name="firstname" value="{{ $user->firstname }}">
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
                                                            <input type="text" class="form-control" id="mobile1" name="mobile" onkeypress="return KeycheckOnlyNumeric(event);" maxlength="10"  value="{{ $user->mobile }}">
                                                        </div>
                                                    </div>
                                                </div>
                                              <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Select Start Financial Day: </label>
                                                             <select name="day" class="c-select form-control">
                                                            <option value="">Select Day</option>
                                                            <?php
                                                            for ($i = 1; $i <= 31; $i++) {
                                                            echo "<option value='$i'";
                                                            if($user->day == $i){ echo 'selected'; }
                                                            echo ">$i</option>";
                                                            }
                                                            ?>
                                                            </select>
                                                           
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Select Start Financial Month:</label>
                                                           
                                                            <select name="month" class="c-select form-control">
                                                            <option value="">Select Month</option>
                                                            <?php
                                                            for ($i = 0; $i < 12; $i++) {
                                                            $time = strtotime(sprintf('%d months', $i));   
                                                            $label = date('F', $time);   
                                                            $value = date('n', $time);
                                                            echo "<option value='$value'";
                                                            if($user->month == $value){ echo 'selected'; }
                                                            echo ">$label</option>";
                                                            }
                                                            ?>
                                                            </select>
                                                       
                                                           
                                                           
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                 <div class="row">
                                                <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Primary User :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_primary" class="custom-control-input" id="true23" value="Y" {{ $user->is_primary == "Y" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="true23">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    
                                                                    <input type="radio" name="is_primary" class="custom-control-input" id="false23" value="N" {{ $user->is_primary == "N" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="false23">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Screen view:</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_screen_view" class="custom-control-input" id="true45" value="Y" {{ $user->is_screen_view == "Y" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="true45">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    
                                                                    <input type="radio" name="is_screen_view" class="custom-control-input" id="false45" value="N" {{ $user->is_screen_view == "N" ? 'checked' : ''}}>
                                                                    <label class="custom-control-label" for="false45">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Step 2 -->
                                            <h6>Step 2</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-12 form-group">
                                                        <div class="file-loading">
                                                            <input id="edit_user_image" name="image" type="file"  />
                                                        </div>
                                                        <!-- <img src = "{{ $user->image }}" style="height: 100px;width: 100px;border: 0;border-radius: 30px;" /> -->
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