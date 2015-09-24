<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Получаем ID заказов, удовлетворяющим нашему фильтру
// Фильтр - неотменённые(то есть в процессе выполнения или выполнены) + заказы за последний промежуток времени
$arOrderIDs = Array();

$arParamsTime =$arParams["TIME_INTERVAL_UG"];
$ordersFilter = array(
		"!CANCELED" => "Y",
		'>=DATE_INSERT' => date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")), strtotime("-$arParamsTime day")));
		
$orders = CSaleOrder::GetList (
	Array(), 
	$ordersFilter,
	false, 
	false, 
	Array ("ID"));
	
while ($arOrder = $orders->Fetch())
{
   $arOrderIDs[] = $arOrder["ID"];
}

// Получаем список товаров по ИД заказов
// Группируем по артикулу и считаем сумму по количеству
$db_res = CSaleBasket::GetList(
	array(),
	array(
		"ORDER_ID" => $arOrderIDs),
	array(
		"PRODUCT_ID",
		"SUM" => "QUANTITY",
		"NAME" => "NAME",
		"PRICE" => "PRICE",
		"DETAIL_PAGE_URL" => "DETAIL_PAGE_URL"),
	false,
	array("PRODUCT_ID")
   );

while ($res = $db_res->Fetch())
{
// Запоминаем ID купленных товаров
$arIDsUnsort[] = $res["PRODUCT_ID"];
// Храним в $arResult информацию об удовлетворяющих фильтру товарах

if($res["QUANTITY"]<$arParams["MIN_COUNT_UG"]) 
	$arResult[] = $res;
}

// Выведем коды всех товаров, которые ни разу не покупались
$db_res = CCatalogProduct::GetList(
        array("ID" => "ASC"),
		array("!ID" => $arIDsUnsort)
    );
while ($res = $db_res->Fetch())
{
	$resIDs[] = $res["ID"];
	$resNames[] = $res["ELEMENT_NAME"];
	// Получение адреса страницы
	$IDs = CIBlockElement::GetByID($res["ID"]); 
	if($ar_res = $IDs->GetNext()) 
		$resPages[]["DETAIL_PAGE_URL"] = $ar_res['DETAIL_PAGE_URL'];
}

// Получение цены
$rsPrices = CPrice::GetList(
		array(), 
		array('PRODUCT_ID' => $resIDs)
	); 
while ($arPrice = $rsPrices->Fetch()) {
	$resPrices[] = $arPrice["PRICE"];
}

// Дополняем $arResult ни разу некупленными товарами	
$i=0;
foreach($resIDs as $resID)
{
	$tmp["PRODUCT_ID"]=$resID;
	$tmp["QUANTITY"]="0";
	$tmp["NAME"]=$resNames[$i];
	$tmp["DETAIL_PAGE_URL"] = $resPages[$i]["DETAIL_PAGE_URL"];
	$tmp["PRICE"] = $resPrices[$i++];
	
	$arResult[] = $tmp;
	unset($tmp);
}

if (!$arParams["IS_AGENT"]) { 
	//Если запуск не из агента, то выводим шаблон компонента
	$this->includeComponentTemplate();
}
else {
	//Если из агента, создаём почтовое событие
	$arUsers = CUser::GetList($by="email",$order = "ASC",array("GROUPS_ID" => "8"));
	while($tmp = $arUsers->Fetch())
		$EMAIL_TO[]=$tmp["EMAIL"];
	
	$rsSites = CSite::GetByID(SITE_ID);
	$arSite = $rsSites->Fetch();
	
	$arEventFields = array(
		"EMAIL_TO"           	=> 	implode(",", $EMAIL_TO),
		"SITE_NAME" 			=> 	$arSite["NAME"],
		"TIME_INTERVAL_UG"		=> 	$arParams["TIME_INTERVAL_UG"],
		"DETAIL_PAGE_URL"		=> 	$arResult[0]["DETAIL_PAGE_URL"],
		"PRODUCT_NAME"			=> 	$arResult[0]["NAME"],
		"PRODUCT_ID"          	=> 	$arResult[0]["PRODUCT_ID"],
		"PRODUCT_PRICE"       	=> 	$arResult[0]["PRICE"],
		"PRODUCT_QUANTITY"    	=> 	$arResult[0]["QUANTITY"],
    );
	
	$arrSITE =  CAdvContract::GetSiteArray($CONTRACT_ID);
	$tmp = CEvent::Send("UG_EVENT", $arrSITE, $arEventFields);
}
?>