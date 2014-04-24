<?php
$pricelist = array(
  '360_ifl' => array(
    245, 620, 470, 580,
    255, 190, 210, 330, 250,
    '-', 180, 105,
    '-', '-', '-', '-',
  ),
  '360_ifg' => array(
    210, 540, 410, 505,
    220, 165, 180, 300, 215,
    '-', 155, 90,
    '-', '-', '-', '-',
  ),
  '720_ifl' => array(
    385, 955, 700, 895,
    390, 310, 330, 525, 400,
    455, 280, 160,
    700, 1740, 635, 790,
  ),
  '720_ifg' => array(
    320, 830, 610, 775,
    340, 265, 280, 455, 350,
    385, 240, 140,
    610, 1510, 550, 685,
  ),
  '1440_ifl' => array(
    485, 1200, 875, 1115,
    490, 380, 375, 600, '-',
    555, 345, 200,
    875, 2175, 790, 985,
  ),
  '1440_ifg' => array(
    400, 1040, 760, 970,
    425, 330, 325, 520, '-',
    480, 300, 175,
    760, 1890, 685, 855,
  ),

  postprint => array(
    cut_perimeter => 10,
    cut_outline => 312,
    lamination => 220,
    eyelet => 10,
    gluing => 10,
    rolling => 500,
  ),
);

$material_name = array(
  "Пленка ORAJET 3640",
  "Пленка ORAJET 3850 Транслюцентная",
  "Бэклит",
  "Перфорированная плёнка",
  "Баннер 510 г/м2",
  "Баннер 440 г/м2",
  "Баннер транслюцентный",
  "Баннер двухсторонний",
  "Баннерная сетка",
  "Фотобумага",
  "Постерная бумага",
  "Бумага (blue back)",
  "Холст",
  "Фотообои флизелиновые 330 г/м2 (Германия)",
  "Фотообои бумажные 275 г/м2 (Китай)",
  "Фотообои бумажные 275 г/м2 (Нидерланды)"
);

$material = $_POST['material'];
$width = $_POST['width'];
$length = $_POST['length'];
$amount = $_POST['amount'];
$quality = $_POST['quality'];

$options = array(
  cut_perimeter => $_POST['cut_perimeter'],
  cut_outline => $_POST['cut_outline'],
  lamination => $_POST['lamination'],
  gluing => $_POST['gluing'],
  rolling => $_POST['rolling'],
);
$eyelets_option = $_POST['eyelets_radio'];

if ($eyelets_option != 0) {
  $options[gluing] = true;
}

$email = $_POST['email'];
$phone = $_POST['phone'];
$comment = $_POST['comment'];

switch ($material) {
  case 0: case 1: case 3:
    $options[eyelets] = '';
    $options[gluing] = '';
    break;
  case 2: case 11: case 12: case 13: case 14: case 15:
    $options[cut_outline] = '';
    $options[lamination] = '';
    $options[eyelets] = '';
    $options[gluing] = '';
    break;
  case 4: case 5: case 6: case 7: case 8:
    $options[cut_outline] = '';
    $options[lamination] = '';
    break;
  case 9: case 10:
    $options[cut_outline] = '';
    $options[eyelets] = '';
    $options[gluing] = '';
    break;
}

$total_perimeter = ($width + $length) * 2 * $amount;
$total_meterage = $width * $length * $amount;

$current_material_prices = '';
if ($quality == 360) {
  if ($total_meterage < 100) {
    $current_material_prices = '360_ifl';
  } else {
    $current_material_prices = '360_ifg';
  }
} elseif ($quality == 720) {
  if ($total_meterage < 100) {
    $current_material_prices = '720_ifl';
  } else {
    $current_material_prices = '720_ifg';
  }
} elseif ($quality == 1440) {
  if ($total_meterage < 100) {
    $current_material_prices = '1440_ifl';
  } else {
    $current_material_prices = '1440_ifg';
  }
}

if (isset($_POST['width']) && isset($_POST['length']) && isset($_POST['amount'])){
  if ($pricelist[$current_material_prices][$material] == '-') {

    $price = 'Несоответствующее качество печати';

  } else {

    $price = $total_meterage * $pricelist[$current_material_prices][$material];

    
    if ($options[cut_perimeter] == 'on')
      $price = $price + $total_perimeter * $pricelist[postprint][cut_perimeter];
    if ($options[cut_outline] == 'on')
      $price = $price + $total_meterage * $pricelist[postprint][cut_outline];
    if ($options[lamination] == 'on')
      $price = $price + $total_meterage * $pricelist[postprint][lamination];
    if ($eyelets_option == 4)
      $price = $price + 4 * $pricelist[postprint][eyelet] * $amount;
    elseif ($eyelets_option == 30)
      $price = $price + $total_meterage / 0.3 * $pricelist[postprint][eyelet];
    elseif ($eyelets_option == 50)
      $price = $price + $total_meterage / 0.5 * $pricelist[postprint][eyelet];
    if ($options[gluing] == 'on')
      $price = $price + $total_perimeter * $pricelist[postprint][gluing];
    if ($options[rolling] == 'on')
      $price = $price + $total_meterage * $pricelist[postprint][rolling];
    $price = ((string)number_format($price, 2, '.', '')) . ' руб.';

    $time = 0;
    if ($quality == 360)
      $time = ceil($total_meterage / (65 * 8));
    else
      $time = ceil($total_meterage / (20 * 8));
    if ($total_meterage > 100)
      $time = $time + 1;

    if ($time%10 == 1)
      if ($time%100 != 11)
        $time = (string)$time . ' день';
      else
        $time = (string)$time . ' дней';
    elseif ($time%10 > 1 && $time%10 < 5)
      if ($time%100 > 11 && $time%100 < 15)
        $time = (string)$time . ' дней';
      else
        $time = (string)$time . ' дня';
    else
      $time = (string)$time . ' дней';
  }
} else {
  $price = "0.00 руб.";
  $time = "0 дней";
}


$message = "";

$message .= "Почта: " . $email . "\n";
$message .= "Телефон: " . $phone . "\n\n";

$message .= "Материал: " . $material_name[$material] . "\n";
$message .= "Ширина: " . $width . " м\n";
$message .= "Длина: " . $length . " м\n";
$message .= "Количество: " . $amount . " шт\n\n";
$message .= "Качество печати: " . $quality . " dpi\n";
$message .= "Постпечатная обработка:\n";
if ($options[cut_perimeter] == 'on')
  $message .= "-- Резка по периметру\n";
if ($options[cut_outline] == 'on')
  $message .= "-- Контурная резка\n";
if ($options[lamination] == 'on')
  $message .= "-- Ламинирование\n";
if ($options[eyelets] == 'on')
  if ($eyelets_option == 4)
    $message .= "-- Люверсы по углам\n";
  elseif ($eyelets_option == 30)
    $message .= "-- Люверсы каждые 30 см\n";
  elseif ($eyelets_option == 50)
    $message .= "-- Люверсы каждые 50 см\n";
if ($options[gluing] == 'on')
  $message .= "-- Проклейка\n";
if ($options[rolling] == 'on')
  $message .= "-- Накатывание\n";

$message .= "\nРасчетная стоимость: " . $price;
$message .= "\nРасчетное время: " . $time;
$message .= "\n\nКомментарий клиента: " . $comment;

$headers = "From: 1a24\r\n".
           "MIME-Version: 1.0\r\n".
           "Content-type: text/plain; charset=UTF-8\r\n";

mail("info@1a24.ru", "Заказ широкоформатной печати (сайт)", $message, $headers);

$url = "http://1a24.ru/?page_id=143";
header('Location: ' . $url);
die();
?>