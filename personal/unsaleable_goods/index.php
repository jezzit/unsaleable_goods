<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");
$APPLICATION->SetTitle("Непродаваемые товары");
// Разрешаем доступ к странице только группе руководителей (ID = 8)
if (in_array(8, CUser::GetUserGroup($USER->GetID()))){?><?$APPLICATION->IncludeComponent(
	"my_namespace:unsaleable_goods",
	".default",
	Array(
		"COMPONENT_TEMPLATE" => ".default",
		"MIN_COUNT_UG" => "5",
		"TIME_INTERVAL_UG" => "7"
	)
);?><br>
<br>
<?}
else die('Access denied');
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>