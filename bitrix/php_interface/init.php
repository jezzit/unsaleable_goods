<?
function SendMessagesWithUG()
{
	$APPLICATION->IncludeComponent(
    "my_namespace:unsaleable_goods",
    "",
    Array(		
        "IS_AGENT" => true       
    )
);
	return "SendMessagesWithUG();";
} 
