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
// Дополняем $arResult ни разу некупленными товарами	
while (($resIDs = $db_res->Fetch()))
{
	$tmp["PRODUCT_ID"]=$resIDs["ID"];
	$tmp["QUANTITY"]="0";
	$tmp["NAME"]=$resIDs["ELEMENT_NAME"];
	
	
	// Получение цены
	$rsPrices = CPrice::GetList(array(), array('PRODUCT_ID' => $resIDs["ID"])); 
	if ($arPrice = $rsPrices->Fetch()) 
		$tmp["PRICE"] = $arPrice["PRICE"]; 
		
	// Получение адреса страницы
	$res = CIBlockElement::GetByID($resIDs["ID"]); 
	if($ar_res = $res->GetNext()) 
		$tmp["DETAIL_PAGE_URL"] = $ar_res['DETAIL_PAGE_URL'];
	
	$arResult[] = $tmp;
	unset($tmp);
}

$this->includeComponentTemplate();
?>