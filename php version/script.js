// Generated by CoffeeScript 1.6.3
(function() {
  var calculate;

  calculate = function() {
    var amount, current_material_prices, eyelets_option, length, material, options, price, pricelist, quality, time, total_meterage, total_perimeter, width;
    pricelist = {
      material_360_ifl: [220, 400, 430, 350, 220, 180, 200, 320, 220, '-', 90, 90, 428, 370],
      material_360_ifg: [200, 350, 410, 330, 200, 160, 180, 300, 200, '-', 80, 80, 408, 350],
      material_720_ifl: [375, 900, 680, 560, 340, 300, 330, 505, 400, 435, 240, 230, 650, 590],
      material_720_ifg: [310, 850, 630, 510, 290, 255, 280, 455, 350, 385, 200, 190, 630, 540],
      material_1440_ifl: [475, 1100, 835, 680, 430, 380, 400, 620, '-', 530, 305, 290, 835, 725],
      material_1440_ifg: [380, 1060, 785, 640, 360, 330, 350, 570, '-', 480, 255, 240, 785, 675],
      postprint: {
        cut_perimeter: 10,
        cut_outline: 312,
        lamination: 220,
        eyelet: 10,
        gluing: 10,
        rolling: 500
      }
    };
    material = Number($("select[name='material']").val());
    if (material === 9) {
      if (Number($("select[name='quality']").val()) === 360) {
        $("select[name='quality']").val('720');
      }
      $("option[value='360']").attr('disabled', 'disabled').siblings().removeAttr('disabled');
    } else if (material === 8) {
      if (Number($("select[name='quality']").val()) === 1440) {
        $("select[name='quality']").val('720');
      }
      $("option[value='1440']").attr('disabled', 'disabled').siblings().removeAttr('disabled');
    } else {
      $("option[value='360']").removeAttr('disabled');
      $("option[value='1440']").removeAttr('disabled');
    }
    width = Number($("input[name='width']").val());
    length = Number($("input[name='length']").val());
    amount = Number($("input[name='amount']").val());
    quality = Number($("select[name='quality']").val());
    options = {
      cut_perimeter: $("input[name='cut_perimeter']").is(':checked'),
      cut_outline: $("input[name='cut_outline']").is(':checked'),
      lamination: $("input[name='lamination']").is(':checked'),
      eyelets: $("input[name='eyelets']").is(':checked'),
      gluing: $("input[name='gluing']").is(':checked'),
      rolling: $("input[name='rolling']").is(':checked')
    };
    eyelets_option = Number($("input[name='eyelets_radio']:checked").val());
    total_perimeter = (width + length) * 2 * amount;
    total_meterage = width * length * amount;
    if (quality === 360) {
      if (total_meterage < 100) {
        current_material_prices = pricelist.material_360_ifl;
      } else {
        current_material_prices = pricelist.material_360_ifg;
      }
    } else if (quality === 720) {
      if (total_meterage < 100) {
        current_material_prices = pricelist.material_720_ifl;
      } else {
        current_material_prices = pricelist.material_720_ifg;
      }
    } else if (quality === 1440) {
      if (total_meterage < 100) {
        current_material_prices = pricelist.material_1440_ifl;
      } else {
        current_material_prices = pricelist.material_1440_ifg;
      }
    }
    price = total_meterage * current_material_prices[material];
    if (options.cut_perimeter) {
      price = price + total_perimeter * pricelist.postprint.cut_perimeter;
    }
    if (options.cut_outline) {
      price = price + total_meterage * pricelist.postprint.cut_outline;
    }
    if (options.lamination) {
      price = price + total_meterage * pricelist.postprint.lamination;
    }
    if (options.eyelets) {
      if (eyelets_option === 4) {
        price = price + 4 * pricelist.postprint.eyelet;
      } else if (eyelets_option === 30) {
        price = price + Math.floor(total_perimeter / 0.3) * pricelist.postprint.eyelet;
      } else if (eyelets_option === 50) {
        price = price + Math.floor(total_perimeter / 0.5) * pricelist.postprint.eyelet;
      }
    }
    if (options.gluing) {
      price = price + total_perimeter * pricelist.postprint.gluing;
    }
    if (options.rolling) {
      price = price + total_meterage * pricelist.postprint.rolling;
    }
    if (isNaN(price)) {
      price = "Несоответствующее качество печати";
    } else {
      price = price.toFixed(2) + " руб.";
    }
    if (quality === 360) {
      time = Math.ceil(total_meterage / (65 * 8));
    } else {
      time = Math.ceil(total_meterage / (20 * 8));
    }
    if (total_meterage > 100) {
      time = time + 1;
    }
    if (time % 10 === 1) {
      if (time % 100 !== 11) {
        time = time + " день";
      } else {
        time = time + " дней";
      }
    } else if (time % 10 > 1 && time % 10 < 5) {
      if (!(time % 100 > 11 && time % 100 < 15)) {
        time = time + " дня";
      } else {
        time = time + " дней";
      }
    } else {
      time = time + " дней";
    }
    $("#price").html(price);
    return $("#time").html(time);
  };

  jQuery(function() {
    $("form.calc").change(function() {
      return calculate();
    });
    $("input[name='width']").on('input', calculate);
    $("input[name='height']").on('input', calculate);
    return $("input[name='number']").on('input', calculate);
  });

}).call(this);