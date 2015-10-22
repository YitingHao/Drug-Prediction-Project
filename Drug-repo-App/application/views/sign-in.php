      <link href=<?php echo css_url().'sign.css'?> rel="stylesheet">

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

            <form class="form-signin">
              <div class="form-group">
                <h2 class="form-signin-heading text-center">Please Sign In</h2>
                <label for="inputEmail" class="sr-only">Email address</label>
                <input type="email" id="inputEmail" class="form-control" placeholder="Email address" required autofocus>
                <label for="inputPassword" class="sr-only">Password</label>
                <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
                <div class="checkbox">
                  <label>
                    <input type="checkbox" value="remember-me"> Remember me
                  </label>
                </div>
                <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
              </div>
              <div class="form-group text-center">
                <h3>OR</h3>
              </div>
              <div class="form-group text-center">
                <h2 class="form-signin-heading">New Member?</h2>
                <a href=<?php echo site_url("user/signup") ?> class="btn btn-lg btn-primary btn-block" role="button">Sign Up</a>
              </div>
            </form>

        </div>
        
      </div>
    </div>