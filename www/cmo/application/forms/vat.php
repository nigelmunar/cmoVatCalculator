<?php
    declare(strict_types=1);
?>
<form class="form-horizontal" action="<?php echo $pageURL; ?>" method="post">
    <?php
        if(count($errors) > 0)
        {
            echo '<ul class="alert alert-danger list-unstyled">';

            if(in_array('ValueBlank', $errors))
            {
                echo '<li class="member-account__error">Value cannot be empty.</li>';
            }

            if(in_array('ValueFormatInvalid', $errors))
            {
                echo '<li class="member-account__error">Value format is invalid.</li>';
            }

            if(in_array('VatRateBlank', $errors))
            {
                echo '<li class="member-account__error">Vat Rate cannot be empty.</li>';
            }

            if(in_array('VatRateFormatInvalid', $errors))
            {
                echo '<li class="member-account__error">Vat rate format is invalid.</li>';
            }
            echo '</ul>';
        }
    ?>
    <fieldset class="form-group">Value
        <input type="text" class="form-control<?php echo (in_array('Value', $errors) ? ' is-invalid' : ''); ?>" name="txtValue" value="<?php echo htmlspecialchars($value); ?>" placeholder="100" />
    </fieldset>


    <fieldset class="form-group">Vat Rate
        <input type="text" class="form-control<?php echo (in_array('VatRate', $errors) ? ' is-invalid' : ''); ?>" name="txtVatRate" value="<?php echo htmlspecialchars($vatRate); ?>" placeholder="21"/>
    </fieldset>

    <input type="hidden" name="txtFormType" value="VATCALCULATION" />
    <input type="submit" class="btn btn-primary" style="background-color: #130B91;" name="" value="Calculate" />&nbsp;&nbsp;&nbsp;or&nbsp;&nbsp;&nbsp;<a href="<?php echo $siteURL?>" style="color: #130B91;">cancel</a>
</form>