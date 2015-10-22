        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <?php 
          $message = $this->aauth->print_errors();
          echo $message == "E-mail is already taken";
          ?>
          

        </div>
      </div>
    </div>