<div class="col-md-4 col-md-offset-4 screenCenter">
    <div class="login-box-body">
        <p class="login-box-msg"><center><h3>Prisijungimas</h3></center></p>
            <div class="form-group has-feedback">
                <label for="name">Slapyvardis</label>
                <input
                    id="name" name="name"
                    type="text" class="form-control red-tooltip"
                    placeholder="GintarasBa4465"
                    />
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>

            <div class="form-group has-feedback">
                <label for="password">Slaptažodis</label>
                <input name="password" id="password" type="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>

            <div class="row">
                <div class="col-xs-8">
                    <div class="checkbox icheck">
                        <label class="">
                            <div class="icheckbox_square-blue" aria-checked="false" aria-disabled="false" style="position: relative;">
                                <input type="checkbox" id="rememberMe" name="rememberMe" value="rememberMe" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);">
                                <ins class="iCheck-helper" style="position: absolute; top: -20%; left: -20%; display: block; width: 140%; height: 140%; margin: 0px; padding: 0px; border: 0px; opacity: 0; background: rgb(255, 255, 255);"></ins>
                            </div> Prisiminti mane
                        </label>
                      </div>
                </div>

                <!-- /.col -->
                <div class="col-xs-4">
                    <button class="btn btn-primary btn-block btn-flat ladda-button loginButton" data-style="expand-right"><span class="ladda-label">Prisijungti</span></button>
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

<script>
    var _token = '{{ Session::token() }}';
    var loginButton = Ladda.create( document.querySelector( '.loginButton' ) );

    $('.loginButton').on('click', function(event) {
        loginButton.start();

        var username = $('#name').val();
        var password = $('#password').val();
        var remember = $('#rememberMe').is(':checked');
        console.log(username, password, remember);

        $.ajax({
            url: "{{ route('auth.loginPost') }}",
            type: 'POST',
            dataType: 'json',
            data: {
                _token: _token,
                username: username,
                password: password,
                remember: remember,
            }
        }).done(function(out) {
            console.log("success", out);
            if(out.success == true) {
                destroyOldTooltips(['#name', '#password']);
                showMessageBox('.login-box-body', out.messages, 'success');
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                switch(out.code) {
                    case 'LOGIN_FAIL':
                        showInputTooltips(out.errors);
                        break;

                    case 'LOGIN_FAIL_AUTH':
                        showMessageBox('.login-box-body', out.errors, 'warning');
                        break;

                }
            }
            loginButton.stop();
        });
    });
</script>
