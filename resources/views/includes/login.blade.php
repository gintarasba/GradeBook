<div class="col-md-4 col-md-offset-4 screenCenter">
    <div class="login-box-body">
        <p class="login-box-msg">Prisijungimas</p>

        <form action="{{ route('loginPost') }}" method="post">
            {!! csrf_field() !!}
            <div class="form-group has-feedback">
                <input name="name" type="text" class="form-control" placeholder="GintarasBa4465">
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label class="">
                            <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                <input type="checkbox" name="rememberMe" value="rememberMe" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);">
                                <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                            </div> Prisiminti mane
                        </label>
                      </div>
                </div>

                <!-- /.col -->
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Prisijungti</button>
                </div>
                <!-- /.col -->
            </div>
            @if(count($errors->pLogin->all()) || count($errors->csrf_error))
            <div class="alert alert-warning">
              <ul>
                @foreach ($errors->pLogin->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                @foreach ($errors->csrf_error->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
            @endif
        </form>


        <a href="#">Pamiršau slaptažodį</a><br>


    </div>

</div>
