@extends('common.common')
@extends('partial.sidebar')
    @section('content')
    <!-- BEGIN: Content-->
        <div class="content-wrapper">
            <div class="content-header row mb-1">
                <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
                    <h3 class="content-header-title mb-0 d-inline-block">Add Church</h3>
                    <div class="row breadcrumbs-top d-inline-block">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('church-management') }}">Church</a>
                                </li>
                                <li class="breadcrumb-item active">add church
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
                                    <h4 class="card-title">Add Church</h4>
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
                                       
                                        <form action="{{ route('action-church-add') }}" method = "post" class="number-tab-steps wizard-notification" role="application" id="target" novalidate="novalidate" id="target">
                                            @csrf
                                            <!-- Step 1 -->
                                            <h6>Step 1</h6>
                                            <fieldset>
                                              
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Reference ID :</label>
                                                            <input type="text" class="form-control" id="church_reference_id" name="church_reference_id" placeholder="Reference ID"  onkeypress="return KeycheckOnlyNumeric(event);">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="firstName1">Church Name :</label>
                                                            <input type="text" class="form-control" id="firstName1" placeholder="Church Name" name="firstname">
                                                        </div>
                                                    </div>

                                                
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Email Address:</label>
                                                            <input type="text" class="form-control" id="email1" placeholder="Email Address" name="email">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mobile1">Mobile Number:</label>
                                                            <input type="text" class="form-control" id="mobile1" name="mobile" placeholder="Mobile Number" onkeypress="return KeycheckOnlyNumeric(event);" maxlength="10">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="email1">Select Start Financial Day </label>
                                                             <select name="day" class="c-select form-control">
                                                            <option value="">Select Day</option>
                                                            <?php
                                                            for ($i = 1; $i <= 31; $i++) {
                                                            echo "<option value='$i'>$i</option>";
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
                                                            echo "<option value='$value'>$label</option>";
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
                                                                    <input type="radio" name="is_primary" class="custom-control-input" id="true23" value="Y" >
                                                                    <label class="custom-control-label" for="true23">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    
                                                                    <input type="radio" name="is_primary" class="custom-control-input" id="false23" value="N" checked="checked">
                                                                    <label class="custom-control-label" for="false23">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label>Screen view :</label>
                                                            <div class="c-inputs-stacked">
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    <input type="radio" name="is_screen_view" class="custom-control-input" id="true45" value="Y" >
                                                                    <label class="custom-control-label" for="true45">Yes</label>
                                                                </div>
                                                                <div class="d-inline-block custom-control custom-checkbox">
                                                                    
                                                                    <input type="radio" name="is_screen_view" class="custom-control-input" id="false45" value="N" checked="checked">
                                                                    <label class="custom-control-label" for="false45">No</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password1">Password :</label>
                                                            <input type="password" class="form-control" id="password1" placeholder="Password" name="password">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="password2">Confirm Password :</label>
                                                            <input type="password" class="form-control" id="password2" placeholder="Confirm Password" name="cpassword">
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                            <!-- Step 2 -->
                                            <h6>Step 2</h6>
                                            <fieldset>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="file-loading">
                                                            <input id="user_image" name="image" type="file" >
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