@extends('layouts.app')
    @section('content')
        <div class="content-wrapper">
            <div class="content-body">
                <section class="flexbox-container">
                    <div class="col-12 d-flex align-items-center justify-content-center">
                        <div class="col-lg-4 col-md-8 col-10 box-shadow-2 p-0">
                            <div class="card border-grey border-lighten-3 px-1 py-1 m-0">
                                <div class="card-header border-0">
                                    <div class="card-title text-center">
                                        <img src="{{ asset('app-assets/images/ico/logo.png')}}" alt="branding logo">
                                    </div>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        @if(Session::has('message'))
                                            <div class="alert alert-danger mb-2" role="alert">
                                                {{ Session::get('message')}}    
                                            </div>
                                        @endif
                                        <form class="form-horizontal ajax" id="" action="{{route('check-login')}}" method="post">
                                            @csrf
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="text" class="form-control" id="user-name" name="email" placeholder="Email Address" >
                                                <div class="form-control-position">
                                                    <i class="ft-user"></i>
                                                </div>
                                            </fieldset>
                                            <fieldset class="form-group position-relative has-icon-left">
                                                <input type="password" class="form-control" id="user-password" name="password" placeholder="Password">
                                                <div class="form-control-position">
                                                    <i class="la la-key"></i>
                                                </div>
                                            </fieldset>
                                            <div class="form-group row">
                                                <div class="col-sm-6 col-12 text-center text-sm-left pr-0">
                                                </div>
                                                <div class="col-sm-6 col-12 float-sm-left text-center text-sm-right"><a data-toggle="modal" href="#myModal" class="card-link">Forgot Password?</a></div>
                                            </div>
                                            <button type="submit" class="btn btn-outline-info btn-block" name="submit"><i class="ft-unlock"></i> Login</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="javascript:void(0);" class="form-forget" id='forgot_password_frm'>
            <div class="modal-header">
                <h4 class="modal-title">Forgot Password?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p style="color:red;" id = "er_msg"></p>
                <p>Enter your e-mail address below to reset your password.</p>
                <p id="forgotemailmsg" style="color:red;"></p>
                <input type="text" name="forgotemail" id="forgotemail"  placeholder="Email Address" autocomplete="off" class="form-control placeholder-no-fix">
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" onclick="forgetpassword();" type="submit">Submit</button>
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
            </div>
            </form>
        </div>
    </div>
</div>
    @endsection
    