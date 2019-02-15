<?php echo wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.css' ); ?>

<?php echo wp_enqueue_style( 'remodal-default-theme', get_template_directory_uri() . '/css/remodal-default-theme.css' ); ?>
<?php echo wp_enqueue_style( 'remodal', get_template_directory_uri() . '/css/remodal.css' ); ?>
  <a href="#modal">Modal №1</a><br>
<div class="remodal-bg remodal-is-closed">
  
</div>
<div class="remodal form-request" data-remodal-id="modal" role="dialog" aria-labelledby="modal1Title" aria-describedby="modal1Desc">

  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
    <div> 	
      <h2>Sample request</h2>
      <h4>Complete the form below to send us your request</h4>
      <br>
    </div>
    <div class="formulaire-sample-request">
        <form>
        <div class="form-row">
          <div class="form-group col-md-6">
            <input type="text" placeholder="Name *" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <input type="text" placeholder="First Name *" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <input type="text" placeholder="Email *" class="form-control" />
        </div>
        <div class="form-group">
          <input type="text" placeholder="Adress *" class="form-control" />
        </div>
        <div class="form-row">
          <div class="form-group col-md-6">
            <input type="text" placeholder="Postal code *" class="form-control" />
          </div>
          <div class="form-group col-md-6">
            <input type="text" placeholder="City *" class="form-control" />
          </div>
        </div>



          <div class="form-row">
            <p>Please indicate the age range in which you are located.</p>
          </div>
          <div class="form-row">
            <div class="form-group col-md-6">
              <input type="radio" id="13_17" class="form-check-input" /><label class="form-check-label" for="13_17">13 - 17 ans</label><br>
              <input type="radio" id="18_24" class="form-check-input" /><label class="form-check-label" for="18_24">18 - 24 ans</label><br>
              <input type="radio" id="25_34" class="form-check-input" /><label class="form-check-label" for="25_34">25 - 34 ans</label><br>
            </div>
            <div class="form-group col-md-6">
              <input type="radio" id="34_44" class="form-check-input" /><label class="form-check-label" for="34_44">34 - 44 ans</label><br>
              <input type="radio" id="55_64" class="form-check-input" /><label class="form-check-label" for="55_64">55 - 64 ans</label><br>
              <input type="radio" id="65_et_plus" class="form-check-input" /><label class="form-check-label" for="65_et_plus">25 ans et plus</label>
            </div>
          </div>

          <div>
            <p>How did you know us</p>
            <select class="form-control" class="selectbox">
              <option>Select</option>
            </select>
          </div>
          <div>
            <p>What type of samples do you want to receive ?</p>
            <select class="form-control" class="selectbox">
              <option>Select</option>
            </select>
          </div>
          <div>
            <p><input type="checkbox" class="checkbox" /> Your consent to receive a sample *</p>
            <p>
              By checking, I agree to be contacted by mail to receive free samples and the information entered in this form will be used to respond to my request.
            </p>
          </div>
          <div class="submit-conteneur">
            <button type="submit" class="submit-btn">Send message</button>
<?php
/*			
			  <button data-remodal-action="cancel" class="remodal-cancel">Cancel</button>
			  <button data-remodal-action="confirm" class="remodal-confirm">OK</button>
*/?>

          </div>
          <br><br>
        </div>
        <div class="picture-product">
          <br>
          <br><br><br>
          <img src="/wp-content/themes/ODF-theme-2/template-parts/img/product1.png" />
          <br>
          <br>
          <br>
          <img src="/wp-content/themes/ODF-theme-2/template-parts/img/product2.png" />
        </div>
      </form>
    </div>
<script src="/wp-content/themes/ODF-theme-1/js/remodal.min.js" ></script>
<script>
jQuery(document).ready(function(){
	
});
</script>

