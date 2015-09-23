<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Создание типа почтового события");
?>
<?

// Создание типа почтового события
//ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД
$et = new CEventType;
$et->Delete("UG_EVENT");
//ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД

function UET($EVENT_NAME, $NAME, $LID, $DESCRIPTION)
{
    $et = new CEventType;
    return $et->Add(array(
        "LID"           => $LID,
        "EVENT_NAME"    => $EVENT_NAME,
        "NAME"          => $NAME,
        "DESCRIPTION"   => $DESCRIPTION
        ));	
}

$UGEventTypeID = UET("UG_EVENT","Регулярная отправка информации о непродаваемых товарах","s1","Описание");

// Создание почтового шаблона
$arr["ACTIVE"] = "Y";
$arr["EVENT_NAME"] = "UG_EVENT";
$arr["LID"] = "s1";
$arr["EMAIL_FROM"] = "#DEFAULT_EMAIL_FROM#";
$arr["EMAIL_TO"] = "#EMAIL_TO#";
$arr["SUBJECT"] = "#SITE_NAME#: Напоминание о непродаваемых товарах";
$arr["BODY_TYPE"] = "html";
$arr["MESSAGE"] = "
<p>Вы получили это письмо, потому что принадлежите на сайте #SITE_NAME# группе Руководителей.</p>
<p>Данный товар за последние #TIME_INTERVAL_UG# дней мало продавался.</p>
<h2>#PRODUCT_NAME#</h2>
<p><b>Артикул: #PRODUCT_ID#</b></p>
<p><b>Цена: #PRODUCT_PRICE#</b></p>
<p><b>Количество продаж - #PRODUCT_QUANTITY#</b></p>

Ознакомиться с полным списком непродаваемых товаров можно
<a href=\"#DETAIL_PAGE_URL#\">в личном кабинете</a>.</p>
";

$emess = new CEventMessage;
$UGEventMessageID = $emess->Add($arr);

//ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД
$etmp = new CEventMessage;
$etmp->Delete($UGEventMessageID-1);
//ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД ВРЕМЕННЫЙ КОД		

//Создание почтового события

/*
function UGEventSend(){
	$arUsers = CUser::GetList($by="email",$order = "ASC",array("GROUPS_ID" => "8"));
	while($tmp = $arUsers->Fetch())
		$EMAIL_TO[]=$tmp["EMAIL"];
	
	$rsSites = CSite::GetByID(SITE_ID);
	$arSite = $rsSites->Fetch();
	$arEventFields = array(
    ["EMAIL_TO"]            => implode(",", $EMAIL_TO),
	["SITE_NAME"] 			=> $arSite["NAME"]
	["TIME_INTERVAL_UG"]	=>
	["DETAIL_PAGE_URL"]		=> 
	["PRODUCT_NAME"]		=>
	["PRODUCT_ID"]          =>
	["PRODUCT_PRICE"]       =>
	["PRODUCT_QUANTITY"]    =>
    );                      

$arEventFields = array(
    ["EMAIL_TO"]            => implode(",", $EMAIL_TO),
	["SITE_NAME"] 			=> 
	["TIME_INTERVAL_UG"]	=>
	["DETAIL_PAGE_URL"]		=> 
	["PRODUCT_NAME"]		=>
	["PRODUCT_ID"]          =>
	["PRODUCT_PRICE"]       =>
	["PRODUCT_QUANTITY"]    =>
    );                     
$arrSITE =  CAdvContract::GetSiteArray($CONTRACT_ID);
$UGEventSend = CEvent::Send("UG_EVENT","s1", $arEventFields);
print_r($UGEventSend);
*/
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>