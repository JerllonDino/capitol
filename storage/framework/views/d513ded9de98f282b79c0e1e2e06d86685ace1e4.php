<div class="col-md-12">
    <form method="GET"  action="<?php echo e(route('pdf.land_tax_collection2',[$base['receipt']->id])); ?>">
         <div class="form-group col-sm-1">
            <label for="period_covered" for="nsign">
                <input type="checkbox" name="nsign" checked="" class="form-control">
                W/SIGN
            </label>
        </div>

        <div class="form-group col-sm-1">
            <label for="period_covered" for="wmunicipality">
                <input type="checkbox" name="wmunicipality" checked="" class="form-control">
                W/Municipality
            </label>

        </div>

        <div class="form-group col-sm-1">
            <button type="submit" class="btn btn-success" name="button" id="confirm" >PRINT</button>
        </div>
          
    </form>


        <div class="form-group col-sm-1">
            <a class="btn btn-info" href="<?php echo e(route('pdf.form56_certificate',$base['receipt']->id)); ?>" <?php if($base['cert_paid'] != \Carbon\Carbon::parse($base['receipt']->report_date)->format('Y')): ?> disabled <?php endif; ?>>CERTIFICATE</a>
        </div>
</div>