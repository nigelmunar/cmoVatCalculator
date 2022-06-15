<?php
    declare(strict_types = 1);
    
    require_once __DIR__ . '/../application/config/config.php';
	require_once ROOT_PATH . 'application/includes/init.php';
    require_once ROOT_PATH . 'application/tools/dateFunctions.php';
    require_once ROOT_PATH . 'application/tools/filterFunctions.php';
	require_once ROOT_PATH . 'application/tools/generalFunctions.php';
    require_once ROOT_PATH . 'application/factories/VatDatabaseFactory.php';

    $errors = [];

    $vatDB = \VatDatabaseFactory::create();

    $value      = '';
    $vatRate    = '';
    $incVat   = '';
    $exVat  ='';

    if(isset($_POST['txtFormType']) && $_POST['txtFormType'] === "VATCALCULATION")
    {
        $value    = (isset($_POST['txtValue']) ? (trim(mb_substr(trim($_POST['txtValue']), 0, 11))) : '');
        $vatRate  = (isset($_POST['txtVatRate']) ? (trim(mb_substr(trim($_POST['txtVatRate']), 0, 11))) : '');

        if(strlen($value) == 0)
        {
            $errors[] = 'Value';
            $errors[] = 'ValueBlank';
        }
        elseif(!is_numeric($value))
        {
            $errors[] = 'Value';
            $errors[] = 'ValueFormatInvalid';
        }

        if(strlen($vatRate) == 0)
        {
            $errors[] = 'VatRate';
            $errors[] = 'VatRateBlank';
        }
        elseif(!is_numeric($vatRate))
        {
            $errors[] = 'VatRate';
            $errors[] = 'VatRateFormatInvalid';
        }
        

        if(count($errors) == 0)
        {
            $exVat      = floatval($value);
            $vatToPay   = (floatval($value) / 100) * floatval($vatRate);
            $incVat     = $vatToPay + $value;

            $vat        = new \Entities\Vat();

            $vat->setExVat((string)$value);
            $vat->setIncVat((string)$incVat);
            $vat->setVatRate((string)$vatRate);

            $vatDB->saveVat($vat);


            $_SESSION['Vat Added'] = 'true';

            header('Location: ' . $siteURL );
            exit;
        }
    
    }

    if(isset($_POST['txtFormType']) && $_POST['txtFormType'] === "CLEARHISTORY")
    {
        $vatDB->clearHistory();

        header('Location: ' . $siteURL);
        exit;
    }

    if(isset($_POST['txtFormType']) && $_POST['txtFormType'] === "DOWNLOADCSV")
    {
        $vatDB->getCSV();
        exit;
    }

    require ROOT_PATH . 'application/config/pageVars.php';
	require ROOT_PATH . 'application/html-includes/admin/header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Dashboard</h1>
                </div>
                <div class="col-sm-6">
                   
                    <?php
                        require ROOT_PATH . 'application/html-includes/admin/breadcrumb.php';
                    ?>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>   

  <!-- Main content -->
  <section class="content">
        <div class="container-fluid">
			<div class="row">
				<div class="col-sm-2">
					<div class="card card-primary">
						<div class="card-body">
							<?php
                                require ROOT_PATH . 'application/forms/vat.php';
							?>
						</div>
                    </div>
				</div>
                <div class="col-sm-7">
                    <div class="card card-primary">
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="vat-datatable" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Excluding Vat</th>
                                                <th>Including Vat</th>
                                                <th>Vat Rate (%)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer">
                                <form action="" method="post">
                                    <input type="hidden" name="txtFormType" value="CLEARHISTORY" />
                                    <input type="submit" id="clearHistory" class="btn btn-primary" style="background-color: #130B91;" name="" value="Clear History" />
                                </form>
                              
                                <form action="" method="post">
                                    <input type="hidden" name="txtFormType" value="DOWNLOADCSV" />
                                    <input id="downloadCSV" type="submit" class="btn btn-primary" style="background-color: #130B91; float: right;" name="" value="CSV Download" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
			</div>
		</div>
	</section>
</div>

<script> 


</script>

<?php
    require ROOT_PATH . 'application/html-includes/admin/footer.php';

