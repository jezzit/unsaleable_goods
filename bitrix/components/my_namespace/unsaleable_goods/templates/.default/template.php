<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<table class="UG">
	<tr>
		<td><b>Название товара</b></td>
		<td><b>Артикул</b></td>
		<td><b>Цена</b></td>
		<td><b>Ссылка</b></td>
		<td><b>Количество продаж</b></td>
	</tr>
<?
foreach($arResult as $item) {
	echo "<tr>";
	echo "<td class=\"UG_TD_NAME\">".$item["NAME"]."</td>";
	echo "<td>".$item["PRODUCT_ID"]."</td>";
	echo "<td>".$item["PRICE"]."</td>";
	echo "<td><a href=\"".$item["DETAIL_PAGE_URL"]."\">Страница товара</a></td>";
	echo "<td>".round($item["QUANTITY"])."</td>";
	echo "</tr>";
}
echo "</table>";
