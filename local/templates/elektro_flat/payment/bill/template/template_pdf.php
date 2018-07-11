<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><?

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arPaySysAction["ENCODING"] = "";

if (!CSalePdf::isPdfAvailable())
	die();

if ($_REQUEST['BLANK'] == 'Y')
	$blank = true;

/** @var CSaleTfpdf $pdf */
$pdf = new CSalePdf('P', 'pt', 'A4');

if ($params['BILL_BACKGROUND'])
{
	$pdf->SetBackground(
		$params['BILL_BACKGROUND'],
		$params['BILL_BACKGROUND_STYLE']
	);
}

$pageWidth  = $pdf->GetPageWidth();
$pageHeight = $pdf->GetPageHeight();

$pdf->AddFont('Font', '', 'pt_sans-regular.ttf', true);
$pdf->AddFont('Font', 'B', 'pt_sans-bold.ttf', true);

$fontFamily = 'Font';
$fontSize   = 10.5;

$margin = array(
	'top' => intval($params['BILL_MARGIN_TOP'] ?: 15) * 72/25.4,
	'right' => intval($params['BILL_MARGIN_RIGHT'] ?: 15) * 72/25.4,
	'bottom' => intval($params['BILL_MARGIN_BOTTOM'] ?: 15) * 72/25.4,
	'left' => intval($params['BILL_MARGIN_LEFT'] ?: 20) * 72/25.4
);

$width = $pageWidth - $margin['left'] - $margin['right'];

$pdf->SetDisplayMode(100, 'continuous');
$pdf->SetMargins($margin['left'], $margin['top'], $margin['right']);
$pdf->SetAutoPageBreak(true, $margin['bottom']);

$pdf->AddPage();


$y0 = $pdf->GetY();
$logoHeight = 0;
$logoWidth = 0;

if ($params['BILL_HEADER_SHOW'] == 'Y')
{
	if ($params['BILL_PATH_TO_LOGO'])
	{
        list($imageHeight, $imageWidth) = $pdf->GetImageSize($params['BILL_PATH_TO_LOGO']);
		list($imageHeight_1, $imageWidth_1) = $pdf->GetImageSize('/local/templates/elektro_flat/images/qr-code.gif');

		if ($imageWidth >= $width)
		{
			$imgDpi = 96 * $imageWidth/($width*0.6 + 5);
			$imgZoom = 96 / $imgDpi;

			$logoHeight = $imageHeight * $imgZoom + 5;
			$logoWidth  = $imageWidth * $imgZoom + 5;
		}
		else
		{
			$imgDpi = intval($params['BILL_LOGO_DPI']) ?: 96;
			$imgZoom = 96 / $imgDpi;

			$logoHeight = $imageHeight * $imgZoom + 5;
			$logoWidth  = $imageWidth * $imgZoom + 5;
		}

		$pdf->Image($params['BILL_PATH_TO_LOGO'], $pdf->GetX(), $pdf->GetY(), -$imgDpi, -$imgDpi);
	}
    
	$pdf->SetFont($fontFamily, 'B', $fontSize);

	$text = CSalePdf::prepareToPdf(str_replace("<br>", " ", $params["SELLER_COMPANY_NAME"]));
	$textWidth = $width - $logoWidth - 60;
	while ($pdf->GetStringWidth($text))
	{
		list($string, $text) = $pdf->splitString($text, $textWidth);
		$pdf->SetX($pdf->GetX() + $logoWidth - 30);
		$pdf->Cell($textWidth, 15, $string, 0, 0, 'L');
		$pdf->Ln();
	}



	if ($params["SELLER_COMPANY_PHONE"])
	{
	$text = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_COMPANY_PHONE', array('#PHONE#' => $params["SELLER_COMPANY_PHONE"])));
	$textWidth = $width - $logoWidth - 60;
	    while ($pdf->GetStringWidth($text)) {
		    list($string, $text) = $pdf->splitString($text, $textWidth);
		    $pdf->SetX($pdf->GetX() + $logoWidth - 30);
		    $pdf->Cell($textWidth, 15, $string, 0, 0, 'L');
		    $pdf->Ln();
	    }
    }
    if ($params["SELLER_COMPANY_ADDRESS"]) {
    $text = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_ADRESS', array('#ADRESS#' => $params["SELLER_COMPANY_ADDRESS"])));
    $textWidth = $width - $logoWidth - 60;
        while ($pdf->GetStringWidth($text)) {
            list($string, $text) = $pdf->splitString($text, $textWidth);
            $pdf->SetX($pdf->GetX() + $logoWidth - 30);
            $pdf->Cell($textWidth, 15, $string, 0, 0, 'L');
            $pdf->Ln();
        }
    }
    
    $pdf->Image('/local/templates/elektro_flat/images/qr-code.gif', 465, 35, -$imgDpi, -$imgDpi);

	$pdf->Ln();
	$pdf->SetY(max($y0 + $logoHeight, $pdf->GetY()));

	if ($params["SELLER_COMPANY_BANK_NAME"])
	{
		$sellerBankCity = '';
		if ($params["SELLER_COMPANY_BANK_CITY"])
		{
			$sellerBankCity = $params["SELLER_COMPANY_BANK_CITY"];
			if (is_array($sellerBankCity))
				$sellerBankCity = implode(', ', $sellerBankCity);
			else
				$sellerBankCity = str_replace(array("\r\n", "\n", "\r"), ', ', strval($sellerBankCity));
		}
		$sellerBank = sprintf(
			"%s %s",
			$params["SELLER_COMPANY_BANK_NAME"],
			$sellerBankCity
		);
		unset($sellerBankCity);
		$sellerRs = $params["SELLER_COMPANY_BANK_ACCOUNT"];
	}
	else
	{
		$rsPattern = '/\s*\d{10,100}\s*/';

		$sellerBank = trim(preg_replace($rsPattern, ' ', $params["SELLER_COMPANY_BANK_ACCOUNT"]));

		preg_match($rsPattern, $params["SELLER_COMPANY_BANK_ACCOUNT"], $matches);
		$sellerRs = trim($matches[0]);
	}

	$pdf->SetFont($fontFamily, '', $fontSize);

	$x0 = $pdf->GetX();
	$y0 = $pdf->GetY();

	$pdf->Cell(
		150, 18,
		($params["SELLER_COMPANY_INN"])
			? CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_INN', array('#INN#' => $params["SELLER_COMPANY_INN"])))
			: ''
	);
	$x1 = $pdf->GetX();
	$pdf->Cell(
		150, 18,
		($params["SELLER_COMPANY_KPP"])
			? CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $params["SELLER_COMPANY_KPP"])))
			: ''
	);
	$x2 = $pdf->GetX();
	$pdf->Cell(50, 18);
	$x3 = $pdf->GetX();
	$pdf->Cell(0, 18);
	$x4 = $pdf->GetX();

	$pdf->Line($x0, $y0, $x4, $y0);

	$pdf->Ln();
	$y1 = $pdf->GetY();

	$pdf->Line($x1, $y0, $x1, $y1);

	$pdf->Cell(300, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_NAME')));
	$pdf->Cell(50, 18);
	$pdf->Cell(0, 18);

	$pdf->Line($x0, $y1, $x2, $y1);

	$pdf->Ln();
	$y2 = $pdf->GetY();
    
    $text = CSalePdf::prepareToPdf($params["BUYER_PERSON_COMPANY_NAME"]);
	while ($pdf->GetStringWidth($text) > 0)
	{
		list($string, $text) = $pdf->splitString($text, 300-5);

	$pdf->Cell(300, 18, $string);
		if ($text)
			$pdf->Ln();
	}
	$pdf->Cell(50, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_ACC')));
	$size = $pdf->GetPageWidth()-$pdf->GetX()-$margin['right'];
	$sellerRs = CSalePdf::prepareToPdf($sellerRs);
	while ($pdf->GetStringWidth($sellerRs) > 0)
	{
		list($string, $sellerRs) = $pdf->splitString($sellerRs, $size-5);

		$pdf->Cell(0, 18, $string);
		if ($sellerRs)
		{
			$pdf->Ln();
			$pdf->Cell(300, 18, '');
			$pdf->Cell(50, 18, '');
		}
	}

	$pdf->Ln();
	$y3 = $pdf->GetY();

	$pdf->Cell(300, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_BANK_NAME')));
	$pdf->Cell(50, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_BANK_BIK')));
	$pdf->Cell(0, 18, CSalePdf::prepareToPdf($params["SELLER_COMPANY_BANK_BIC"]));

	$pdf->Line($x0, $y3, $x4, $y3);

	$pdf->Ln();
	$y4 = $pdf->GetY();

	$text = CSalePdf::prepareToPdf($sellerBank);
	while ($pdf->GetStringWidth($text) > 0)
	{
		list($string, $text) = $pdf->splitString($text, 300-5);

		$pdf->Cell(300, 18, $string);
		if ($text)
			$pdf->Ln();
	}
	$pdf->Cell(50, 18, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SELLER_ACC_CORR')));

	$bankAccountCorr = CSalePdf::prepareToPdf($params["SELLER_COMPANY_BANK_ACCOUNT_CORR"]);
	while ($pdf->GetStringWidth($bankAccountCorr) > 0)
	{
		list($string, $bankAccountCorr) = $pdf->splitString($bankAccountCorr, $size-5);

		$pdf->Cell(0, 18, $string);
		if ($bankAccountCorr)
		{
			$pdf->Ln();
			$pdf->Cell(300, 18, '');
			$pdf->Cell(50, 18, '');
		}
	}

	$pdf->Ln();
	$y5 = $pdf->GetY();

	$pdf->Line($x0, $y5, $x4, $y5);

	$pdf->Line($x0, $y0, $x0, $y5);
	$pdf->Line($x2, $y0, $x2, $y5);
	$pdf->Line($x3, $y0, $x3, $y5);
	$pdf->Line($x4, $y0, $x4, $y5);

	$pdf->Ln();
	$pdf->Ln();
}
if ($params['BILL_HEADER'])
{
	$pdf->SetFont($fontFamily, '', $fontSize * 2);
	$billNo_tmp = CSalePdf::prepareToPdf(
		$params['BILL_HEADER'].' '.Loc::getMessage('SALE_HPS_BILL_SELLER_TITLE', array('#PAYMENT_NUM#' => $params["ACCOUNT_NUMBER"],'#YEAR#' => date('y'), '#DAY#' => date('z'), '#PAYMENT_DATE#' => $params["PAYMENT_DATE_INSERT"]))
	);

	$billNo_width = $pdf->GetStringWidth($billNo_tmp);
	$pdf->Cell(0, 20, $billNo_tmp, 0, 0, 'C');
	$pdf->Ln();
}
$pdf->SetFont($fontFamily, '', $fontSize);

if ($params["BILL_ORDER_SUBJECT"])
{
	$pdf->Cell($width/2-$billNo_width/2-2, 15, '');
	$pdf->MultiCell(0, 15, CSalePdf::prepareToPdf($params["BILL_ORDER_SUBJECT"]), 0, 'L');
}

if ($params["PAYMENT_DATE_PAY_BEFORE"])
{
	$pdf->Cell($width/2-$billNo_width/2-2, 15, '');
	$pdf->MultiCell(0, 15, CSalePdf::prepareToPdf(
			Loc::getMessage('SALE_HPS_BILL_SELLER_DATE_END', array('#PAYMENT_DATE_END#' => ConvertDateTime($params["PAYMENT_DATE_PAY_BEFORE"], FORMAT_DATE) ?: $params["PAYMENT_DATE_PAY_BEFORE"]))), 0, 'L');
}

$pdf->Ln();
$arOrder = CSaleOrder::GetByID($_REQUEST["ORDER_ID"]);

if ($params['BILL_PAYER_SHOW'] == 'Y')
{
	if ($params["BUYER_PERSON_COMPANY_NAME"])
	{
		$pdf->Write(15, CSalePdf::prepareToPdf(Loc::getMessage('_NAME', array('#BUYER_NAME#' => ''))));

        if($arOrder["PAY_SYSTEM_ID"] != PAY_SISTEM_NDS){
            $pdf->Write(15, CSalePdf::prepareToPdf( $params["SELLER_COMPANY_DIRECTOR_POSITION"].' '));
        }
            $pdf->Write(15, CSalePdf::prepareToPdf( $params["BUYER_PERSON_COMPANY_NAME_CONTACT"].', '));
        if ($params["SELLER_COMPANY_INN"]){
            $pdf->Write(15, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_BUYER_INN', array('#INN#' => $params["SELLER_COMPANY_INN"]))));
        }
        if ($params["SELLER_COMPANY_KPP"]){
            $pdf->Write(15, CSalePdf::prepareToPdf(sprintf(", %s", Loc::getMessage('SALE_HPS_BILL_KPP', array('#KPP#' => $params["SELLER_COMPANY_KPP"]))."\n")));
        } else {
            $pdf->Write(15, CSalePdf::prepareToPdf("\n"));
        }
        
		if ($params["BUYER_PERSON_COMPANY_ADDRESS"]) {
            $pdf->Write(15, CSalePdf::prepareToPdf("\n"));
            $pdf->Write(15, Loc::getMessage('SALE_HPS_BILL_ADRESS', array('#ADRESS#' => $params["BUYER_PERSON_COMPANY_ADDRESS"])));
		}
		if ($params["BUYER_PERSON_COMPANY_PHONE"])
			$pdf->Write(15, CSalePdf::prepareToPdf(sprintf(", %s", $params["BUYER_PERSON_COMPANY_PHONE"])));
		if ($params["BUYER_PERSON_COMPANY_FAX"])
			$pdf->Write(15, CSalePdf::prepareToPdf(sprintf(", %s", $params["BUYER_PERSON_COMPANY_FAX"])));
		$pdf->Ln();
	}
}     

CModule::IncludeModule('sale');   
CModule::IncludeModule('iblock');
            
$pdf->Write(15, CSalePdf::prepareToPdf("\n"));
// Выведем все свойства заказа с кодом $ID, сгруппированые по группам свойств
$db_props = CSaleOrderPropsValue::GetOrderProps($_REQUEST["ORDER_ID"]);
$iGroup = -1;
$element = 0;

$pdf->Write(15, CSalePdf::prepareToPdf(GetMessage('SALE_HPS_BILL_BUYER'). ' '));
while ($arProps = $db_props->Fetch()) {
   if ($iGroup!=IntVal($arProps["PROPS_GROUP_ID"])) {
      $iGroup = IntVal($arProps["PROPS_GROUP_ID"]);
   }
  if($iGroup == 5 || $iGroup == 3){
    $element++;
  }
  
   if(($iGroup == 5 || $iGroup == 3) && 
       (!empty($arProps["VALUE"]) || $arProps["CODE"] == 'COMPANY_ADR_ACT') && 
       $arProps["CODE"] != 'LOCATION' && 
       $arProps["CODE"] != 'stock' && 
       $arProps["CODE"] != 'FAX' && 
       $arProps["CODE"] != 'organization' && 
       $arProps["CODE"] != 'delivery_type' && 
       $arProps["CODE"] != 'agreement'){ 
          if($element > 1){
             
              if($arProps["CODE"] == 'COMPANY_ADR'){
                  $str = "\n";
                  $value_company = $arProps["VALUE"];
              } else {
                //  $str = ', <br>';
              }
          }
          if($arProps["CODE"] == 'COMPANY_ADR_ACT' && empty(trim($arProps["VALUE"]," "))){
            $str = "\n";
            $arProps["VALUE"] = $value_company;
          }
          $element++;

          if($arProps["CODE"] == "COMPANY"){      
            $pdf->Write(15, CSalePdf::prepareToPdf(sprintf(" %s", $str. htmlspecialchars($arProps["VALUE"]))));         
          } else {      
            $pdf->Write(15, CSalePdf::prepareToPdf(sprintf(", %s", $str. $arProps["NAME"].": ". htmlspecialchars($arProps["VALUE"]))));
          }
   } 
}
$pdf->Write(15, CSalePdf::prepareToPdf("\n"));

$pdf->SetFont($fontFamily, '', 7);
if($arOrder["PAY_SYSTEM_ID"] == PAY_SISTEM_NDS){
    echo GetMessage('');
    $pdf->Write(10, CSalePdf::prepareToPdf(Loc::getMessage('PAY_SISTEM_TEXT_NDS')));
} else {
    $pdf->Write(10, CSalePdf::prepareToPdf(Loc::getMessage('PAY_SISTEM_TEXT_NO_NDS')));

}
$pdf->Write(15, CSalePdf::prepareToPdf("\n"));
         
$pdf->SetFont($fontFamily, 'B', 13);
//выборка по нескольким свойствам (TERMINAL_DL):
$dbOrderProps = CSaleOrderPropsValue::GetList(
        array("SORT" => "ASC"),
        array("ORDER_ID" => $_REQUEST["ORDER_ID"], "CODE"=>array("LOCATION"))
    );
    while ($arOrderProps = $dbOrderProps->GetNext()){
        $adress_dekivery = $arOrderProps;
        $location_adress = CSaleLocation::GetByID($adress_dekivery["VALUE"]);
    };
$arDeliv = CSaleDelivery::GetByID($arOrder["DELIVERY_ID"]);

if($params["DELIVERY_NAME"] != 'Самовывоз'){
    $pdf->Write(15, CSalePdf::prepareToPdf(sprintf("%s", Loc::getMessage('SALE_HPS_BILL_DELIVERY_NAME'). ' '. $arDeliv["NAME"]."\n")));
    $pdf->Write(15, Loc::getMessage('SALE_HPS_BILL_DELIVERY_POST').', '.$location_adress["COUNTRY_NAME"].', '.$location_adress["REGION_NAME"].', '.$location_adress["CITY_NAME"]);
    
} else { 
//выборка по нескольким свойствам (TERMINAL_DL):
    $dbOrderProps = CSaleOrderPropsValue::GetList(
        array("SORT" => "ASC"),
        array("ORDER_ID" => $_REQUEST["ORDER_ID"], "CODE"=>array("stock"))
    );
    if ($arOrderProps = $dbOrderProps->GetNext()){
        $arVal = CSaleOrderPropsVariant::GetByValue($arOrderProps["ORDER_PROPS_ID"], $arOrderProps["VALUE"]);
    };
  
$order = \Bitrix\Sale\Order::load($_REQUEST['ORDER_ID']);

/** @var \Bitrix\Sale\ShipmentCollection $shipmentCollection */
$shipmentCollection = $order->getShipmentCollection();
/** @var \Bitrix\Sale\Shipment $shipment */

foreach ($shipmentCollection as $key => $shipment) {
   
    $ship_id = $shipment->getStoreId();
        if($ship_id != 0){
            $rsStore = CCatalogStore::GetList(array(), array('ID' => $ship_id), false, false, array()); 
            if ($arStore = $rsStore->Fetch()){   
                $store = $arStore;
            }

        }
  
}    
      
    $pdf->Write(15, Loc::getMessage('DELIVERY_S').' '.$store["ADDRESS"]);

}               
$pdf->Write(15, CSalePdf::prepareToPdf("\n"));
$pdf->SetFont($fontFamily, '', 10);
$arCurFormat = CCurrencyLang::GetCurrencyFormat($params['CURRENCY']);
$currency = preg_replace('/(^|[^&])#/', '${1}', $arCurFormat['FORMAT_STRING']);
	$currency = strip_tags($currency);

$columnList = array('NUMBER', 'NAME', 'QUANTITY', 'MEASURE', 'PRICE', 'VAT_RATE', 'SUM');
$arCols = array();
$vatRateColumn = 0;
foreach ($columnList as $column)
{
	if ($params['BILL_COLUMN_'.$column.'_SHOW'] == 'Y')
	{
		$caption = $params['BILL_COLUMN_'.$column.'_TITLE'];
		if (in_array($column, array('PRICE', 'SUM')))
			$caption .= ', '.$currency;

		$arCols[$column] = array(
			'NAME' => CSalePdf::prepareToPdf($caption),
			'SORT' => $params['BILL_COLUMN_'.$column.'_SORT']
		);
	}
}
if ($params['USER_COLUMNS'])
{
	$columnList = array_merge($columnList, array_keys($params['USER_COLUMNS']));
	foreach ($params['USER_COLUMNS'] as $id => $val)
	{
		$arCols[$id] = array(
			'NAME' => CSalePdf::prepareToPdf($val['NAME']),
			'SORT' => $val['SORT']
		);
	}
}

uasort($arCols, function ($a, $b) {return ($a['SORT'] < $b['SORT']) ? -1 : 1;});
$arColumnKeys = array_keys($arCols);
$columnCount = count($arColumnKeys);

if (count($params['BASKET_ITEMS']) > 0)
{
	$arCells = array();
	$arProps = array();

	$n = 0;
	$sum = 0.00;
	$vat = 0;
	foreach ($params['BASKET_ITEMS'] as $basketItem)
	{
        if( $arOrder["PAY_SYSTEM_ID"] == PAY_SISTEM_NDS){
            $basketItem['IS_VAT_IN_PRICE'] = 0;
            $basketItem['VAT_RATE'] = 0;
        } else {
            $basketItem['IS_VAT_IN_PRICE'] = 1;
            $basketItem['VAT_RATE'] = 0.1800;
            unset($params['TAXES']);
        }
		$productName = $basketItem["NAME"];
		if ($productName == "OrderDelivery")
			$productName = Loc::getMessage('SALE_HPS_BILL_DELIVERY');
		else if ($productName == "OrderDiscount")
			$productName = Loc::getMessage('SALE_HPS_BILL_DISCOUNT');

		if ($basketItem['IS_VAT_IN_PRICE'])
			$basketItemPrice = $basketItem['PRICE'];
		else
			$basketItemPrice = round($basketItem['PRICE']*(1 + $basketItem['VAT_RATE']));

		$arCells[++$n] = array();
		foreach ($arCols as $columnId => $col)
		{
			$data = null;

			switch ($columnId)
			{
				case 'NUMBER':
					$data = CSalePdf::prepareToPdf($n);
					$arCols[$columnId]['IS_DIGIT'] = true;
					break;
                case 'VAT_RATE':
                    $data = CSalePdf::prepareToPdf($n);
                    $arCols[$columnId]['IS_DIGIT'] = true;
                    break;
				case 'NAME':
					$data = CSalePdf::prepareToPdf($productName);
					break;
                case 'MEASURE':
                    $data = CSalePdf::prepareToPdf($basketItem["MEASURE_NAME"] ? $basketItem["MEASURE_NAME"] : Loc::getMessage('SALE_HPS_BILL_BASKET_MEASURE_DEFAULT'));
                    $arCols[$columnId]['IS_DIGIT'] = true;
                    break;
				case 'QUANTITY':
					$data = CSalePdf::prepareToPdf(roundEx($basketItem['QUANTITY'], SALE_VALUE_PRECISION));
					$arCols[$columnId]['IS_DIGIT'] = true;
					break;
				case 'PRICE':
                    if( $arOrder["PAY_SYSTEM_ID"] == PAY_SISTEM_NDS){
                        $data = CSalePdf::prepareToPdf(SaleFormatCurrency(round($basketItem['PRICE']*(1 + $basketItem['VAT_RATE'])), $basketItem['CURRENCY'], true));
                    } else {
                        $data = CSalePdf::prepareToPdf(SaleFormatCurrency($basketItem['PRICE'], $basketItem['CURRENCY'], true));
                    }
                    $arCols[$columnId]['IS_DIGIT'] = true;
                    break;
				case 'SUM':
					$data = CSalePdf::prepareToPdf(SaleFormatCurrency($basketItemPrice * $basketItem['QUANTITY'], $basketItem['CURRENCY'], true));
					$arCols[$columnId]['IS_DIGIT'] = true;
					break;
				default:
					if (preg_match('/[^0-9 ,\.]/', $basketItem[$columnId]) === 0)
					{
						if (!array_key_exists('IS_DIGIT', $arCols[$columnId]))
							$arCols[$columnId]['IS_DIGIT'] = true;
					}
					else
					{
						$arCols[$columnId]['IS_DIGIT'] = false;
					}
					$data = ($basketItem[$columnId]) ? CSalePdf::prepareToPdf($basketItem[$columnId]) : '';
			}
			if ($data !== null)
				$arCells[$n][$columnId] = $data;
		}

		$arProps[$n] = array();
		foreach ($basketItem['PROPS'] as $basketPropertyItem)
		{
			if ($basketPropertyItem['CODE'] == 'CATALOG.XML_ID' || $basketPropertyItem['CODE'] == 'PRODUCT.XML_ID')
				continue;

			$arProps[$n][] = $pdf::prepareToPdf(sprintf("%s: %s", $basketPropertyItem["NAME"], $basketPropertyItem["VALUE"]));
		}

		$sum += doubleval($basketItem['PRICE'] * $basketItem['QUANTITY']);
		$vat = max($vat, $basketItem['VAT_RATE']);
	}

	if ($vat <= 0)
	{
		unset($arCols['VAT_RATE']);
		$columnCount = count($arCols);
		$arColumnKeys = array_keys($arCols);
		foreach ($arCells as $i => $cell)
			unset($arCells[$i]['VAT_RATE']);
	}

	if ($params['DELIVERY_PRICE'] > 0)
	{
		$sDeliveryItem = Loc::getMessage('SALE_HPS_BILL_DELIVERY');
		if ($params['DELIVERY_NAME'])
			$sDeliveryItem .= sprintf(" (%s)", $params['DELIVERY_NAME']);
		$arCells[++$n] = array();
		foreach ($arCols as $columnId => $col)
		{
			$data = null;

			switch ($columnId)
			{
				case 'NUMBER':
					$data = CSalePdf::prepareToPdf($n);
					break;
                case 'VAT_RATE':
                    $data = CSalePdf::prepareToPdf($n);
                    break;
				case 'NAME':     
					$data = CSalePdf::prepareToPdf($sDeliveryItem);
					break;
                case 'MEASURE':
                    $data = CSalePdf::prepareToPdf('');
                    break;
				case 'QUANTITY':
					$data = CSalePdf::prepareToPdf(1);
					break;
				case 'PRICE':
					$data = CSalePdf::prepareToPdf(SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true));
					break;
				case 'SUM':
					$data = CSalePdf::prepareToPdf(SaleFormatCurrency($params['DELIVERY_PRICE'], $params['CURRENCY'], true));
					break;
				default:
					$data = '';
			}
			if ($data !== null)
				$arCells[$n][$columnId] = $data;
		}

		$sum += doubleval($params['DELIVERY_PRICE']);
	}

	$cntBasketItem = $n;
	if ($params['BILL_TOTAL_SHOW'] == 'Y')
	{
		if ($sum < $params['SUM'])
		{
			$arCells[++$n] = array();
			for ($i = 0; $i < $columnCount; $i++)
				$arCells[$n][$arColumnKeys[$i]] = null;

			$arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_SUBTOTAL'));
			$arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($sum, $params['CURRENCY'], true));
		}

        if ($params['SUM_PAID'] > 0)
        {
            $arCells[++$n] = array();
            for ($i = 0; $i < $columnCount; $i++)
                $arCells[$n][$arColumnKeys[$i]] = null;

            $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_PAID'));
            $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['SUM_PAID'], $params['CURRENCY'], true));
        }
       
        if ($params['DISCOUNT_PRICE'] > 0)
        {
            $arCells[++$n] = array();
            for ($i = 0; $i < $columnCount; $i++)
                $arCells[$n][$arColumnKeys[$i]] = null;

            $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_DISCOUNT'));
            $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['DISCOUNT_PRICE'], $params['CURRENCY'], true));
        }
         
		if ($params['TAXES'])
		{
			foreach ($params['TAXES'] as $tax)
			{
				$arCells[++$n] = array();
				for ($i = 0; $i < $columnCount; $i++)
					$arCells[$n][$arColumnKeys[$i]] = null;

				$arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(sprintf(
					"%s%s%s:",
					($tax["IS_IN_PRICE"] == "Y") ? Loc::getMessage('SALE_HPS_BILL_INCLUDING') : "",
					$tax["TAX_NAME"],
					($vat <= 0 && $tax["IS_PERCENT"] == "Y") ? sprintf(' (%s%%)', roundEx($tax["VALUE"], SALE_VALUE_PRECISION)) : ""
				));
				$arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($tax["VALUE_MONEY"], $params['CURRENCY'], true));
			}

        $arCells[++$n] = array();
        for ($i = 0; $i < $columnCount; $i++)
            $arCells[$n][$arColumnKeys[$i]] = null;

        $params['SUM'] += round($nds_price);
        $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_SUM_PRICE_NDS'));
        $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['SUM'], $params['CURRENCY'], true));
        
		}

		if (!$params['TAXES'])
		{
			$arCells[++$n] = array();
			for ($i = 0; $i < $columnCount; $i++)
				$arCells[$n][$arColumnKeys[$i]] = null;

			$arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE'));
			$arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_VAT_RATE_NO'));
            
        $arCells[++$n] = array();
        for ($i = 0; $i < $columnCount; $i++)
            $arCells[$n][$arColumnKeys[$i]] = null;

        $arCells[$n][$arColumnKeys[$columnCount-2]] = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_TOTAL_SUM'));
        $arCells[$n][$arColumnKeys[$columnCount-1]] = CSalePdf::prepareToPdf(SaleFormatCurrency($params['SUM'], $params['CURRENCY'], true));
   
		}
 


	}

	$rowsInfo = $pdf->calculateRowsWidth($arCols, $arCells, $cntBasketItem, $width);
	$arRowsWidth = $rowsInfo['ROWS_WIDTH'];
	$arRowsContentWidth = $rowsInfo['ROWS_CONTENT_WIDTH'];
}
$pdf->Ln();

$x0 = $pdf->GetX();
$y0 = $pdf->GetY();

$k = 0;
do
{
	$newLine = false;
	foreach ($arCols as $columnId => $column)
	{
		list($string, $arCols[$columnId]['NAME']) = $pdf->splitString($column['NAME'], $arRowsContentWidth[$columnId]);
		if ($vat > 0 || $columnId !== 'VAT_RATE')
			$pdf->Cell($arRowsWidth[$columnId], 20, $string, 0, 0, $k ? 'L' : 'C');

		if ($arCols[$columnId]['NAME'])
		{
			$k++;
			$newLine = true;
		}

		$i = array_search($columnId, $arColumnKeys);
		${"x".($i+1)} = $pdf->GetX();
	}

	$pdf->Ln();
}
while($newLine);

$y5 = $pdf->GetY();

$pdf->Line($x0, $y0, ${"x".$columnCount}, $y0);
for ($i = 0; $i <= $columnCount; $i++)
{
	if ($vat > 0 || $arColumnKeys[$i] != 'VAT_RATE')
		$pdf->Line(${"x$i"}, $y0, ${"x$i"}, $y5);
}
$pdf->Line($x0, $y5, ${'x'.$columnCount}, $y5);

$rowsCnt = count($arCells);
for ($n = 1; $n <= $rowsCnt; $n++)
{
	$arRowsWidth_tmp = $arRowsWidth;
	$arRowsContentWidth_tmp = $arRowsContentWidth;
	$accumulated = 0;
	$accumulatedContent = 0;
	foreach ($arCols as $columnId => $column)
	{
		if (is_null($arCells[$n][$columnId]))
		{
			$accumulated += $arRowsWidth_tmp[$columnId];
			$arRowsWidth_tmp[$columnId] = null;
			$accumulatedContent += $arRowsContentWidth_tmp[$columnId];
			$arRowsContentWidth_tmp[$columnId] = null;
		}
		else
		{
			$arRowsWidth_tmp[$columnId] += $accumulated;
			$arRowsContentWidth_tmp[$columnId] += $accumulatedContent;
			$accumulated = 0;
			$accumulatedContent = 0;
		}
	}

	$x0 = $pdf->GetX();
	$y0 = $pdf->GetY();

	$pdf->SetFont($fontFamily, '', $fontSize);

	$l = 0;
	do
	{
		$newLine = false;
		foreach ($arCols as $columnId => $column)
		{
			$string = '';
			if (!is_null($arCells[$n][$columnId]))
				list($string, $arCells[$n][$columnId]) = $pdf->splitString($arCells[$n][$columnId], $arRowsContentWidth_tmp[$columnId]);

			$rowWidth = $arRowsWidth_tmp[$columnId];

			if (in_array($columnId, array('QUANTITY', 'MEASURE', 'PRICE', 'SUM')))
			{
				if (!is_null($arCells[$n][$columnId]))
				{
					$pdf->Cell($rowWidth, 15, $string, 0, 0, 'R');
				}
			}
			elseif ($columnId == 'NUMBER')
			{
				if (!is_null($arCells[$n][$columnId]))
					$pdf->Cell($rowWidth, 15, ($l == 0) ? $string : '', 0, 0, 'C');
			}
			elseif ($columnId == 'NAME')
			{
				if (!is_null($arCells[$n][$columnId]))
					$pdf->Cell($rowWidth, 15, $string, 0, 0,  ($n > $cntBasketItem) ? 'R' : '');
			}
			elseif ($columnId == 'VAT_RATE')
			{
				if (!is_null($arCells[$n][$columnId]))
					$pdf->Cell($rowWidth, 15, $string, 0, 0, 'R');
			}
			else
			{
				if (!is_null($arCells[$n][$columnId]))
				{
					$pdf->Cell($rowWidth, 15, $string, 0, 0,   ($n > $cntBasketItem) ? 'R' : 'L');
				}
			}

			if ($l == 0)
			{
				$pos = array_search($columnId, $arColumnKeys);
				${'x'.($pos+1)} = $pdf->GetX();
			}

			if ($arCells[$n][$columnId])
				$newLine = true;
		}

		$pdf->Ln();
		$l++;
	}
	while($newLine);

	if ($params['BILL_COLUMN_NAME_SHOW'] == 'Y')
	{
		if (isset($arProps[$n]) && is_array($arProps[$n]))
		{
			$pdf->SetFont($fontFamily, '', $fontSize - 2);
			foreach ($arProps[$n] as $property)
			{
				$i = 0;
				$line = 0;
				foreach ($arCols as $columnId => $caption)
				{
					$i++;
					if ($i == $columnCount)
						$line = 1;
					if ($columnId == 'NAME')
						$pdf->Cell($arRowsWidth_tmp[$columnId], 12, $property, 0, $line);
					else
						$pdf->Cell($arRowsWidth_tmp[$columnId], 12, '', 0, $line);
				}
			}
		}
	}

	$y5 = $pdf->GetY();

	if ($y0 > $y5)
		$y0 = $margin['top'];

	for ($i = ($n > $cntBasketItem) ? $columnCount - 1 : 0; $i <= $columnCount; $i++)
	{
		if ($vat > 0 || $arColumnKeys[$i] != 'VAT_RATE')
			$pdf->Line(${"x$i"}, $y0, ${"x$i"}, $y5);
	}

	$pdf->Line(($n <= $cntBasketItem) ? $x0 : ${'x'.($columnCount-1)}, $y5, ${'x'.$columnCount}, $y5);
}
$pdf->Ln();

// Выведем актуальную корзину для текущего пользователя

$WIDTH = 0;
$AMOUNT = 0;
$dbBasketItems = CSaleBasket::GetList(
        array(),
        array( "ORDER_ID" => $_REQUEST["ORDER_ID"]),
        false,
        false,
        array('PRODUCT_ID', "QUANTITY")
    );  
while ($arItems = $dbBasketItems->Fetch()){
    $k = 0;
    while( $k < 12){  // перебираем все свойства с объемами товара
        if($k == 0){
            $params_width = 'VES_KG'; 
        } else {
            $params_width = "VES_KG_".$k;
        }
        $width_number = CIBlockElement::GetProperty(IBCLICK_CATALOG_ID, $arItems["PRODUCT_ID"], array(), array("CODE" => $params_width));
            while ($am = $width_number->Fetch()){
                if(!empty($am["VALUE_ENUM"])){  //  проверим чтобюы они были 
                    $number = floatval(str_replace(",", ".", $am["VALUE_ENUM"]));   
                    $number = $number * $arItems["QUANTITY"];                            
                    $WIDTH += $number;   
                }
            } 
    $k++;
    }
    $i = 0;
    while( $i < 12){  // перебираем все свойства с объемами товара
        if($k == 0){
            $params_amount = 'OBEM_M3'; 
        } else {
            $params_amount = "OBEM_M3_".$i;
        }
        $amount_number = CIBlockElement::GetProperty(IBCLICK_CATALOG_ID, $arItems["PRODUCT_ID"], array(), array("CODE" => $params_amount));
            while ($am = $amount_number->Fetch()){
                if(!empty($am["VALUE_ENUM"])){  //  проверим чтобюы они были 
                    $number_am = floatval(str_replace(",", ".", $am["VALUE_ENUM"])) * 10; 
                    $number_am = $number_am * $arItems["QUANTITY"];                                                          
                    $AMOUNT += $number_am;   
                }
            } 
    $i++;
    }
}  
if($AMOUNT > 0.001){
    $AMOUNT = $AMOUNT / 10;
} else{
    $AMOUNT = 0.001;    
}

if ($params['BILL_TOTAL_SHOW'] == 'Y')
{
	$pdf->SetFont($fontFamily, '', $fontSize);
	$pdf->Write(15, CSalePdf::prepareToPdf(Loc::getMessage(
		'SALE_HPS_BILL_BASKET_TOTAL',
		array(
			'#BASKET_COUNT#' => $cntBasketItem,
			'#BASKET_PRICE#' => strip_tags(SaleFormatCurrency($params['SUM'], $params['CURRENCY'], false))
		)
	)));

    $text = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_WEIGHT'));
    $textWidth = 0;
        while ($pdf->GetStringWidth($text)) {
            list($string, $text) = $pdf->splitString($text, $textWidth);
            $pdf->SetX($pdf->GetX() + 104);
            $pdf->Cell(200, 10, $string, 10, -2, 'L');
            $pdf->Cell($textWidth, 10, $WIDTH, 10, -2, 'R');
            $pdf->Ln();
        }
	$pdf->Ln();

	$pdf->SetFont($fontFamily, 'B', $fontSize);
	if (in_array($params['CURRENCY'], array("RUR", "RUB")))
	{
        $text_width = CSalePdf::prepareToPdf(Number2Word_Rus($params['SUM']));
        list($string, $text_width) = $pdf->splitString($text_width, 305);
        $pdf->SetX($pdf->GetX() );         
        $pdf->Cell(300, 10, $string, 0, 0, 'L');
		//$pdf->Write(15, CSalePdf::prepareToPdf(Number2Word_Rus($params['SUM'])));
	}
	else
	{
		$pdf->Write(15, CSalePdf::prepareToPdf(strip_tags(SaleFormatCurrency(
			$params['SUM'],
			$params["CURRENCY"],
			false
		))));
	}
        $pdf->SetFont($fontFamily, '', $fontSize);

        $text_amount = CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_AMOUNT'));
        $textWidth = 0;
            while ($pdf->GetStringWidth($text_amount)) {
                list($string, $text_amount) = $pdf->splitString($text_amount, 305);
                $pdf->SetX($pdf->GetX() );         
                $pdf->Cell(140, 10, $string, 0, 0, 'R');
                $pdf->Cell($textWidth, 10, $AMOUNT, 0, 0, 'R');
                $pdf->Ln();
            }
	$pdf->Ln();
}



    
//$pdf->Write(15, CSalePdf::prepareToPdf(Loc::getMessage('SALE_HPS_BILL_WEIGHT').' '.$WIDTH));




$pdf->Ln();
$pdf->Ln();
$pdf->Ln();



    
if ($params['BILL_SIGN_SHOW'] == 'Y')
{
	if ($params['BILL_PATH_TO_STAMP'])
	{
		$filePath = $pdf->GetImagePath($params['BILL_PATH_TO_STAMP']);

		if ($filePath != '' && !$blank && \Bitrix\Main\IO\File::isFileExists($filePath))
		{
			list($stampHeight, $stampWidth) = $pdf->GetImageSize($params['BILL_PATH_TO_STAMP']);
			if ($stampHeight && $stampWidth)
			{
				if ($stampHeight > 120 || $stampWidth > 120)
				{
					$ratio = 120 / max($stampHeight, $stampWidth);
					$stampHeight = $ratio * $stampHeight ;
					$stampWidth = $ratio * $stampWidth ;
				}

				if ($pdf->GetY() + $stampHeight > $pageHeight)
					$pdf->AddPage();

				$pdf->Image(
						$params['BILL_PATH_TO_STAMP'],
						$margin['left'] + 50, $pdf->GetY() - 30,
						$stampWidth - 10, $stampHeight - 10
				);
			}
		}
	}

	$pdf->SetFont($fontFamily, 'B', $fontSize);

	if ($params["SELLER_COMPANY_DIRECTOR_POSITION"])
	{
		$isDirSign = false;
		if (!$blank && $params['SELLER_COMPANY_DIR_SIGN'])
		{
			list($signHeight, $signWidth) = $pdf->GetImageSize($params['SELLER_COMPANY_DIR_SIGN']);

			if ($signHeight && $signWidth)
			{
				$ratio = min(37.5/$signHeight, 150/$signWidth);
				$signHeight = $ratio * $signHeight;
				$signWidth  = $ratio * $signWidth;

				$isDirSign = true;
			}
		}
        $sellerDirPos = CSalePdf::prepareToPdf($params["SELLER_COMPANY_DIRECTOR_POSITION"]);

         if ($params["SELLER_COMPANY_DIRECTOR_NAME"] && $arOrder["PAY_SYSTEM_ID"] != PAY_SISTEM_NDS) { 
            $text_width = CSalePdf::prepareToPdf($params["SELLER_COMPANY_DIRECTOR_POSITION"].' '.$params["SELLER_COMPANY_DIRECTOR_NAME"]);
            list($string, $text_width) = $pdf->splitString($text_width, 100);
            $pdf->SetX($pdf->GetX() );         
            $pdf->Cell(150, 10, $string, 0, 0, 'L');
        } else { 
            $pdf->Write(10, CSalePdf::prepareToPdf(Loc::getMessage('RK_MESSAGE').'                     '. $sellerDirPos));
            $pdf->Ln();
            $pdf->SetFont($fontFamily, 'B', 8);
            $pdf->Write(12, CSalePdf::prepareToPdf('                                                             '.Loc::getMessage('POSITION_MESSAGE')));
        }                                                          
		if ($isDirSign && $pdf->GetStringWidth($sellerDirPos) <= 160)
			$pdf->SetY($pdf->GetY() + min($signHeight, 30) - 15);
		//$pdf->MultiCell(150, 15, $sellerDirPos, 0, 'L');
		$pdf->SetXY($margin['left'] + 150, $pdf->GetY() - 15);
        $pdf->SetFont($fontFamily, 'B', $fontSize);
		if ($isDirSign)
		{
			$pdf->Image(
					$params['SELLER_COMPANY_DIR_SIGN'],
				$pdf->GetX() + 80 - $signWidth/2, $pdf->GetY() - $signHeight + 15,
				$signWidth, $signHeight
			);
		}
 
		$x1 = $pdf->GetX() + 20;
		$pdf->Cell(180, 15, '');
		$x2 = $pdf->GetX();

		if ($params["SELLER_COMPANY_DIRECTOR_NAME"])
            $pdf->Write(15, CSalePdf::prepareToPdf('('.$params["SELLER_COMPANY_DIRECTOR_NAME"].')'));
        $pdf->SetFont($fontFamily, 'B', 8); 
        
        $text = CSalePdf::prepareToPdf(Loc::getMessage('FULL_NAME'));
        $textWidth = 0;
            while ($pdf->GetStringWidth($text)) {
                list($string, $text) = $pdf->splitString($text, $textWidth);
                $pdf->SetX($pdf->GetX() );   
                $pdf->Ln();
                $pdf->Cell(420, 3, $string, 10, 0, 'R');
                $pdf->Ln();
            }      
        $pdf->Ln();
        
        $pdf->SetFont($fontFamily, 'B', 8);
		
        $y2 = $pdf->GetY();
		$pdf->Line($x1, $y2, $x2, $y2);
        
        $pdf->SetFont($fontFamily, 'B', $fontSize);
         if ($params["SELLER_COMPANY_DIRECTOR_NAME"] && $arOrder["PAY_SYSTEM_ID"] != PAY_SISTEM_NDS) { 
            $text_width = CSalePdf::prepareToPdf('предприниматель '.$params["SELLER_COMPANY_DIRECTOR_NAME"]);
            list($string, $text_width) = $pdf->splitString($text_width, 250);
            $pdf->SetX($pdf->GetX() );         
            $pdf->Cell(150, 20, $string, 0, 0, 'L');
         }
        $pdf->Ln();
        $pdf->Ln();
        $pdf->Write(15, CSalePdf::prepareToPdf("\n"));
        $pdf->Write(15, CSalePdf::prepareToPdf("\n"));
	}
    $pdf->SetFont($fontFamily, 'B', $fontSize);
	if ($params["SELLER_COMPANY_ACCOUNTANT_POSITION"])
	{
		$isAccSign = false;
		if (!$blank && $params['SELLER_COMPANY_ACC_SIGN'])
		{
			list($signHeight, $signWidth) = $pdf->GetImageSize($params['SELLER_COMPANY_ACC_SIGN']);

			if ($signHeight && $signWidth)
			{
				$ratio = min(37.5/$signHeight, 150/$signWidth);
				$signHeight = $ratio * $signHeight;
				$signWidth  = $ratio * $signWidth;

				$isAccSign = true;
			}
		}

		$sellerAccPos = CSalePdf::prepareToPdf($params["SELLER_COMPANY_ACCOUNTANT_POSITION"]);
        
         if ($params["SELLER_COMPANY_DIRECTOR_NAME"] && $arOrder["PAY_SYSTEM_ID"] != PAY_SISTEM_NDS) { 
            $pdf->Write(10, CSalePdf::prepareToPdf($params["SELLER_COMPANY_DIRECTOR_POSITION"]));; 
            $pdf->Write(10, CSalePdf::prepareToPdf($params["SELLER_COMPANY_DIRECTOR_NAME"]));; 
        } else { 
            $pdf->Write(10, CSalePdf::prepareToPdf(Loc::getMessage('GL_MESSAGE').'            '. $sellerDirPos));
            $pdf->Ln();
            $pdf->SetFont($fontFamily, 'B', 8);
            $pdf->Write(12, CSalePdf::prepareToPdf('                                                             '.Loc::getMessage('POSITION_MESSAGE')));
        }   
        
		if ($isAccSign && $pdf->GetStringWidth($sellerAccPos) <= 160)
			$pdf->SetY($pdf->GetY() + min($signHeight, 30) - 15);
		//$pdf->MultiCell(150, 15, $sellerAccPos, 0, 'L');
		$pdf->SetXY($margin['left'] + 150, $pdf->GetY() - 15);

		if ($isAccSign)
		{
			$pdf->Image(
				$params['SELLER_COMPANY_ACC_SIGN'],
				$pdf->GetX() + 80 - $signWidth/2, $pdf->GetY() - $signHeight + 15,
				$signWidth, $signHeight
			);
		}
        $pdf->SetFont($fontFamily, 'B', $fontSize);
		$x1 = $pdf->GetX() + 20;
		$pdf->Cell(180, 15, '');
		$x2 = $pdf->GetX();

		if ($params["SELLER_COMPANY_ACCOUNTANT_NAME"])
			$pdf->Write(15, CSalePdf::prepareToPdf('('.$params["SELLER_COMPANY_ACCOUNTANT_NAME"].')'));
        $pdf->SetFont($fontFamily, 'B', 8); 
        
        $text = CSalePdf::prepareToPdf(Loc::getMessage('FULL_NAME'));
        $textWidth = 0;
            while ($pdf->GetStringWidth($text)) {
                list($string, $text) = $pdf->splitString($text, $textWidth);
                $pdf->SetX($pdf->GetX() );   
                $pdf->Ln();
                $pdf->Cell(420, 3, $string, 10, 0, 'R');
                $pdf->Ln();
            }      
        $pdf->Ln();
        
        $pdf->SetFont($fontFamily, 'B', 8);

		$y2 = $pdf->GetY();
		$pdf->Line($x1, $y2, $x2, $y2);
	}
}

$dest = 'I';
if ($_REQUEST['GET_CONTENT'] == 'Y')
	$dest = 'F';
else if ($_REQUEST['DOWNLOAD'] == 'Y')
	$dest = 'D';
           
return $pdf->Output(
	sprintf(
		$_SERVER["DOCUMENT_ROOT"].'/upload/order_%s.pdf',
		str_replace(
			array(
				chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10), chr(11),
				chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20), chr(21), chr(22),
				chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30), chr(31),
				'"', '*', '/', ':', '<', '>', '?', '\\', '|'
			),
			'_',
			strval($params["ACCOUNT_NUMBER"])
		),
		ConvertDateTime($params['PAYMENT_DATE_INSERT'], 'YYYY-MM-DD')
	), $dest
);  
?>