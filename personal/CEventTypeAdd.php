<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Создание типа почтового события");?>
<?// Создание типа почтового события
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

if ($UGEventTypeID)
	echo "Успешно создан новый тип почтового события. ID: ".$UGEventTypeID;
else
	echo "Ошибка при создании типа почтового события";
echo "</br>";
COption::SetOptionString("main", "check_agents", "Y"); 
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

if ($UGEventMessageID)
	echo "Успешно создан новый почтовый шаблон. ID: ".$UGEventMessageID;
else
	echo "Ошибка при создании почтового шаблона";
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
