<?php
    declare(strict_types = 1);

	require_once __DIR__ . '/../../application/config/config.php';
	require_once ROOT_PATH . 'application/includes/init.php';
    require_once ROOT_PATH . 'application/tools/dateFunctions.php';
    require_once ROOT_PATH . 'application/tools/filterFunctions.php';
	require_once ROOT_PATH . 'application/tools/generalFunctions.php';
	require_once ROOT_PATH . 'application/factories/VatDatabaseFactory.php';
	

	$draw     = 0;
	$start    = 0;
	$length   = 0;
	$filters  = [];
	$order    = [];
	
	if(isset($_GET['draw']) && is_numeric($_GET['draw']))
	{
		$draw = abs((int)$_GET['draw']);
	}

	if(isset($_GET['length']) && is_numeric($_GET['length']))
	{
		$length = abs((int)$_GET['length']);

		if($length < 10)
		{
			$length = 10;
		}
	}

	if(isset($_GET['start']) && is_numeric($_GET['start']))
	{
		$start = abs((int)$_GET['start']);
	}

	//Get the search filters
	$columnData = [];

	if(isset($_GET['columns']))
	{
		$columnData = $_GET['columns'];
	}

	for($i = 0; $i < count($columnData); $i++)
	{
		if(isset($columnData[$i]['name']) && isset($columnData[$i]['search']) && isset($columnData[$i]['search']['value']) && strlen($columnData[$i]['search']['value']) > 0)
		{
			$filters[$columnData[$i]['name']] = $columnData[$i]['search']['value'];
		}
	}

	//Get the sort order
	$orderData = [];

	if(isset($_GET['order']))
	{
		$orderData = $_GET['order'];
	}

	for($i = 0; $i < count($orderData); $i++)
	{
		if(isset($orderData[$i]['dir']) && isset($orderData[$i]['column']) && strlen($orderData[$i]['column']) > 0)
		{
			if(isset($columnData[$orderData[$i]['column']]) && isset($columnData[$orderData[$i]['column']]['name']))
			{
				$order[] = [ $columnData[$orderData[$i]['column']]['name'], $orderData[$i]['dir'] ];
			}
		}
	}


	$vatDB = \VatDatabaseFactory::create();

	$vatData = $vatDB->getVatList($start, $length, $order);

	$vatData['draw'] = $draw;

    header('Content-type: application/json');
    echo json_encode($vatData);