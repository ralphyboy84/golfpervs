<?php



?>
<div class='row'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Find a Perv!</h3>
        </div>
        <div class="box-body">
            <div class="form-group">
				<label for="selectCourse" class="col-sm-2 control-label">Enter Username / Forename / Surname:</label>
				<div class="col-sm-5">
				    <input type='input' class='form-control' id='criteria' name='criteria' value='' placeholder='Search Criteria' />
			     </div>
			</div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <button id="searchpervs" type="button" class="btn btn-primary">Search</button>
                </div>
            </div>
      </div><!-- /.box -->
    </div>
  </div>
</div>

<div class='row' id='searchresultscontainer'>
    <div class="col-lg-12 col-xs-12">
      <div class="box box-success">
        <div class="box-header with-border">
          <h3 class="box-title"><i class="fa fa-user"></i> Search Results</h3>
        </div>
        <div class="box-body" id='searchresults'>

      </div><!-- /.box -->
    </div>
  </div>
</div>