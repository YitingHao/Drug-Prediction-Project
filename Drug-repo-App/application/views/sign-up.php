      <link href=<?php echo css_url().'sign.css'?> rel="stylesheet">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <h2>Create Your Personal Account</h2>
          <?php echo validation_errors(); ?>
          <?php echo form_open('user/signup') ?>
            <div class="form-group">
              <label for="inputEmail">Email address</label>
              <input name="email" type="email" class="form-control" placeholder="Enter email">
            </div>
            <div class="form-group">
              <label for="inputUsername">Username</label>
              <input name="username" type="text" class="form-control" placeholder="Choose your username">
            </div>
            <div class="form-group">
              <label for="inputPassword">Password</label>
              <input name="password" type="password" class="form-control" placeholder="Password">
            </div>
            <div class="form-group">
              <label for="inputPassword">Confirm your password</label>
              <input name="passconf" type="password" class="form-control" placeholder="Re-enter Password">
            </div>
            <button type="submit" class="btn btn-primary btn-right col-md-offset-11">Submit</button>
          </form>

        </div>
      </div>
    </div>