# Bitrix напоминание о забытых корзинах



Напоминалка о забытой корзине на bitrix

Рассылка зарегистрированным пользователям о забытом
товаре в корзине (повторяется 3  раза с интервалом  в 2 дня)


Создается функция ForgottenCartEmail() в init.php
которую мы запускаем с помощью cron или агентами

Установка
1) Заходим в редактирование пользователя, вкладка дополнительно, там создаем пользовательское поле UF_CARTEMAIL 
UF_CARTDATE

2) Делаем почтовое событие MY_REMIND_CART и почтовый шаблон для него 
 &#x23;EMAIL_TO# - в поле получатель письма

3) Код вставляем в init.php переправляем "s1" на код вашего сайта

4) ForgottenCartEmail добавляем в агент в битриксе

Радуемся уведомлениям о забытой корзине

по вопросам:
email: iravenss@gmail.com
skype: iravenss

