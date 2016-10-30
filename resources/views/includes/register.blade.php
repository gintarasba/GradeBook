<div class="col-md-4 col-md-offset-4">
    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        <form action="{{ route('register') }}" method="post">
            {!! csrf_field() !!}   
            <div class="form-group has-feedback" > 
                <input name="nick" type="text"  class="form-control" placeholder="Nick" >
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input name="email" type="email" class="form-control" placeholder="Email">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password_confirmation" type="password" class="form-control" placeholder="Retype password">
                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
            </div>
            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label class="">
                          <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;"><input type="checkbox" name="rememberMe" value="rememberMe" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"><ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins></div> I agree to the <a href="#">terms</a>
                        </label>
                    </div>
                </div>

                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
            </div>
           @if(count($errors->register->all()) > 0)
                <div class="alert alert-warning">
                    @foreach ($errors->register->all() as $error)
                        <div class="error">{{ $error }}</div>
                    @endforeach
                 </div>
            @endif 
            @if(Session::has('successReg'))
                <div class="alert alert-success">
                    {{ Session::get('successReg') }}
                </div>
            @endif
        </form>


    </div>
</div>