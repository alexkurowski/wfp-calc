<?php
$pricelist = array(
  '360_ifl' => array(
    220, 400, 430, 350,
    220, 180, 200, 320, 220,
    '-', 90, 90,
    428, 370,
  ),
  '360_ifg' => array(
    200, 350, 410, 330,
    200, 160, 180, 300, 200,
    '-', 80, 80,
    408, 350,
  ),
  '720_ifl' => array(
    375, 900, 680, 560,
    340, 300, 330, 505, 400,
    435, 240, 230,
    650, 590,
  ),
  '720_ifg' => array(
    310, 850, 630, 510,
    290, 255, 280, 455, 350,
    385, 200, 190,
    630, 540,
  ),
  '1440_ifl' => array(
    475, 1100, 835, 680,
    430, 380, 400, 620, '-',
    530, 305, 290,
    835, 725,
  ),
  '1440_ifg' => array(
    380, 1060, 785, 640,
    360, 330, 350, 570, '-',
    480, 255, 240,
    785, 675,
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

$material = $_POST['material'];
$width = $_POST['width'];
$length = $_POST['length'];
$amount = $_POST['amount'];
$quality = $_POST['quality'];

$options = array(
  cut_perimeter => $_POST['cut_perimeter'],
  cut_outline => $_POST['cut_outline'],
  lamination => $_POST['lamination'],
  eyelets => $_POST['eyelets'],
  gluing => $_POST['gluing'],
  rolling => $_POST['rolling'],
);

switch ($material) {
  case 0: case 1: case 3:
    $options[eyelets] = '';
    $options[gluing] = '';
    break;
  case 2: case 11: case 12: case 13:
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
      $price = $price + floor($total_meterage / 0.3) * $pricelist[postprint][eyelet];
    elseif ($eyelets_option == 50)
      $price = $price + floor($total_meterage / 0.5) * $pricelist[postprint][eyelet];
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

// restore parameters
$select = array(
  0 => '',
  1 => '',
  2 => '',
  3 => '',
  4 => '',
  5 => '',
  6 => '',
  7 => '',
  8 => '',
  9 => '',
  10 => '',
  11 => '',
  12 => '',
  13 => '',

  width => $width,
  length => $length,
  amount => $amount,
  '360' => '',
  '720' => '',
  '1440' => '',

  cut_perimeter => '',
  cut_outline => '',
  lamination => '',
  eyelets => '',
  gluing => '',

  eye4 => '',
  eye30 => '',
  eye50 => '',
);

if (!$width) $select[width] = 1;
if (!$length) $select[length] = 1;
if (!$amount) $select[amount] = 1;
$select[$material] = 'selected = "selected"';
$select[$quality] = 'selected = "selected"';

if ($options[cut_perimeter] == 'on') $select[cut_perimeter] = 'checked = "checked"';
if ($options[cut_outline] == 'on') $select[cut_outline] = 'checked = "checked"';
if ($options[lamination] == 'on') $select[lamination] = 'checked = "checked"';
if ($options[eyelets] == 'on') $select[eyelets] = 'checked = "checked"';
if ($options[gluing] == 'on') $select[gluing] = 'checked = "checked"';

if ($eyelets_option == 4) $select[eye4] = 'checked = "checked"';
elseif ($eyelets_option == 30) $select[eye30] = 'checked = "checked"';
elseif ($eyelets_option == 50) $select[eye50] = 'checked = "checked"';
else $select[eye4] = 'checked = "checked"';
?>