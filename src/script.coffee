calculate = ->
  # ifl = "if less"
  # ifg = "if greater"
  pricelist = {
    material_360_ifl: [
      220, 400, 430, 350,
      220, 180, 200, 320, 220,
      '-', 90, 90,
      428, 370
    ],
    material_360_ifg: [
      200, 350, 410, 330,
      200, 160, 180, 300, 200,
      '-', 80, 80,
      408, 350
    ],
    material_720_ifl: [
      375, 900, 680, 560,
      340, 300, 330, 505, 400,
      435, 240, 230,
      650, 590
    ],
    material_720_ifg: [
      310, 850, 630, 510,
      290, 255, 280, 455, 350,
      385, 200, 190,
      630, 540
    ],
    material_1440_ifl: [
      475, 1100, 835, 680,
      430, 380, 400, 620, '-',
      530, 305, 290,
      835, 725
    ],
    material_1440_ifg: [
      380, 1060, 785, 640,
      360, 330, 350, 570, '-',
      480, 255, 240,
      785, 675
    ]

    postprint: {
      cut_perimeter: 10
      cut_outline: 312
      lamination: 220
      eyelet: 10
      gluing: 10
      rolling: 500
    }
  }

  material = Number($("select[name='material']").val())
  width = Number($("input[name='width']").val())
  length = Number($("input[name='length']").val())
  amount = Number($("input[name='amount']").val())
  quality = Number($("select[name='quality']").val())
  options = {
    cut_perimeter: $("input[name='cut_perimeter']").is(':checked')
    cut_outline:   $("input[name='cut_outline']").is(':checked')
    lamination:    $("input[name='lamination']").is(':checked')
    eyelets:       $("input[name='eyelets']").is(':checked')
    gluing:        $("input[name='gluing']").is(':checked')
    rolling:       $("input[name='rolling']").is(':checked')
  }
  eyelets_option = Number($("input[name='eyelets_radio']:checked").val())
  
  total_perimeter = (width + length) * 2 * amount
  total_meterage = width * length * amount

  if quality == 360
    if total_meterage < 100
      current_material_prices = pricelist.material_360_ifl
    else
      current_material_prices = pricelist.material_360_ifg
  else if quality == 720
    if total_meterage < 100
      current_material_prices = pricelist.material_720_ifl
    else
      current_material_prices = pricelist.material_720_ifg
  else if quality == 1440
    if total_meterage < 100
      current_material_prices = pricelist.material_1440_ifl
    else
      current_material_prices = pricelist.material_1440_ifg


  price = total_meterage * current_material_prices[material]

  if options.cut_perimeter
    price += total_perimeter * pricelist.postprint.cut_perimeter
  if options.cut_outline
    price += total_meterage * pricelist.postprint.cut_outline
  if options.lamination
    price += total_meterage * pricelist.postprint.lamination
  if options.eyelets
    if eyelets_option == 4
      price += 4 * pricelist.postprint.eyelet
    else if eyelets_option == 30
      price += Math.round(total_perimeter * 0.3) * pricelist.postprint.eyelet
    else if eyelets_option == 50
      price += Math.round(total_perimeter * 0.5) * pricelist.postprint.eyelet
  if options.gluing
    price += total_perimeter * pricelist.postprint.gluing
  if options.rolling
    price += total_meterage * pricelist.postprint.rolling

  if isNaN(price)
    price = "Несоответствующее качество печати"
  else
    price += " руб."


  if quality == 360
    time = Math.ceil(total_meterage / 65)
  else
    time = Math.ceil(total_meterage / 10)

  if time % 100 == 1
    time += " день"
  else if time % 100 > 1 and time % 100 < 5
    time += " дня"
  else
    time += " дней"

  
  $("#price").html(price)
  $("#time").html(time)
  

jQuery ->
  $("form.calc").change ->
    calculate()