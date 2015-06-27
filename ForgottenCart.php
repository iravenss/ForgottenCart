<? 
/*
iravenss (c) 2015 
iravenss@gmail.com

Забытая корзина на bitrix

Рассылка зарегистрированным пользователям о забытом
товаре в корзине (повторяется 3  раза с интервалом  в 2 дня)


Создается функция ForgottenCartEmail() в init.php
которую мы запускаем с помощью cron или агентами
1) Создаем пользовательское поле UF_CARTEMAIL 
UF_CARTDATE

2) Делаем почтовое событие MY_REMIND_CART и почтовый шаблон для него 
#EMAIL_TO# - в поле получатель письма

3) Этот код вставляем в init.php переправляем s1 на код вашего сайта

4) ForgottenCartEmail добавляем в агент в битриксе


*/
//При добавлении в корзину меняет UF_CARTEMAIL = 1 
AddEventHandler("sale", "OnBasketAdd", "AddPresentToBasket");
AddEventHandler("sale", "OnBasketUpdate", "AddPresentToBasket"); 
 
function AddPresentToBasket($ID, $arFields) 
   {
 		global $USER;
   		

        	$user = new CUser;
        	$userid = $user->GetID();
$fields = Array( 
"UF_CARTEMAIL" => "1",
"UF_CARTDATE" => date('Y-m-d H:i:s'), 
); 
$user->Update($userid, $fields);
   
}




//Функция забытых корзин

 function ForgottenCartEmail(){
$cUser = new CUser; 
$sort_by = "ID";
$sort_ord = "ASC";
$arFilter = array(
	//Ищем юзеров с UF_CARTEMAIL = 1 2 или 3
	"UF_CARTEMAIL" => array(1,2,3)
   
);
$dbUsers = $cUser->GetList($sort_by, $sort_ord, $arFilter,array("SELECT"=>array("EMAIL","UF_*")));
while ($arUser = $dbUsers->Fetch()) 
{

	//print_r($arUser);
	//Если дата текущаю больше даты добавления + 2 дня 
if(strtotime(date('Y-m-d H:i:s')) > strtotime($arUser['UF_CARTDATE'] . ' + 2 days')){
//echo "ok";
	//Добавляем в +1 к UF_CARTEMAIL меняем дату UF_CARTDATE
$arUser['UF_CARTEMAIL']++;
$user = new CUser;
        	$userid = $user->GetID();
$fields = Array( 
"UF_CARTEMAIL" => $arUser['UF_CARTEMAIL'],
"UF_CARTDATE" => date('Y-m-d H:i:s'), 
); 
$user->Update($arUser['ID'], $fields);

//значения для почтового шаблона
$arFields = array(
    "ID"          => 124,
    "CONTRACT_ID" => 1,
    "TYPE_SID"    => "LEFT",
   "EMAIL_TO" => $arUser['EMAIL']
    );
echo $arUser['EMAIL'];

//Количество товаров в корзине сейчас, если корзина пуста, то письма не будет
$cntBasketItems = CSaleBasket::GetList(
   array(),
   array( 
      "USER_ID" => $arUser['ID'],
      "LID" => "s1",
      "ORDER_ID" => "NULL"
   ), 
   array()
);
if($cntBasketItems > 0){
//Шлем письмо почтовое событие MY_REMIND_CART
if(CEvent::Send("MY_REMIND_CART", "s1", $arFields)){
	echo "- ok<br>";
}
}

}
else{
	//echo "wait";
}

 //echo $arUser['UF_CARTDATE'].'/'.$arUser["ID"]." ".$arUser["DATE_REGISTER"]."<br>"; 
}

}
?>