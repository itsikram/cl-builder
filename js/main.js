(function ($) {
  $(document).ready((e) => {
    //alert(pricePerSqft);

    $(".form-select option:first-child").attr("selected", true);
    // reset Select
    let resetSelect = (e) => {
      $(".select-face option,.select-lit option,.select-raceway option").prop(
        "selected",
        function () {
          return this.defaultSelected;
        }
      );
    };

    let updatePrice = (subPrice, price) => {
      // display subtotal
      $(".product-pricing-box .price-subtotal-container  .price-subtotal").text(
        subPrice.toFixed(2)
      );
      // display total
      $(".product-pricing-box .price-total-container  .price-total").text(
        price.toFixed(2)
      );
      // set total cost
      $("#totalCost").val(price.toFixed(2));
    };

    let updateDisplay = (height, width) => {
      let totalHeightIn = height * 12;
      let totalWidthIn = width * 12;
      let totalSqft = height * width;
      let pricePerSqft = parseFloat(
        $(".product-attibute-box #pricePerSqft").val()
      );
      let subTotalPrice = pricePerSqft * totalSqft;
      let totalPrice = pricePerSqft * totalSqft;
      let dimentionText = `${totalHeightIn}" x ${totalWidthIn}" = ${totalSqft.toFixed(
        2
      )} ft<sup>2</sup> `;
      // display dimention
      $(".product-attibute-box  .total-size-sqft").html(dimentionText);
      $(".product-attibute-box  .total-size-sqft").attr(
        "data-total-sqft",
        totalSqft
      );
      updatePrice(subTotalPrice, totalPrice);
      // change slelects to default
      $(".product-attibute-box .dynamic-select.dynamic-price").val("0");
      $(".product-attibute-box .dynamic-select").attr("data-cCost", 0);
    };

    let validateCalcInputs = (e) => {
      let minValue = parseInt($(e.currentTarget).attr("min"));
      if (parseInt($(e.currentTarget).val()) < minValue) {
        alert("Mininum: " + minValue);
        e.target.value = minValue;
      }

      let maxValue = parseInt($(e.currentTarget).attr("max"));
      if (parseInt($(e.currentTarget).val()) > maxValue) {
        alert("Maximum: " + maxValue);
        e.target.value = maxValue;
      }
    };

    // change attribute
    $(document).on(
      "change",
      ".product-attibute-box .dynamic-select:not(.avoid-price)",
      (e) => {
        let getAttrCurrentCost = parseFloat(
          $(e.currentTarget).attr("data-cCost")
        );
        let priceBeforeChange = parseFloat($("#totalCost").val());
        updatePrice(
          priceBeforeChange - getAttrCurrentCost,
          priceBeforeChange - getAttrCurrentCost
        );
        priceBeforeChange = parseFloat($("#totalCost").val());
        let costType, selectedAttrVal, priceAfterChange;
        selectedAttrVal = parseFloat(e.target.value);

        if (e.target.value.split("/")[1]) {
          selectedAttrVal = parseFloat(e.target.value.split("/")[0]);
          costType = e.target.value.split("/")[1];
        }

        if (costType == "%") {
          priceAfterChange =
            priceBeforeChange + (priceBeforeChange / 100) * selectedAttrVal;

          $(e.currentTarget).attr(
            "data-cCost",
            (priceBeforeChange / 100) * selectedAttrVal
          );
          //alert(priceAfterChange)
        } else if (costType == "sqft") {
          let totalSqft = $(".product-attibute-box  .total-size-sqft").attr(
            "data-total-sqft",
            totalSqft
          );
          let pricePerSqft = e.target.value;
          let sqftPrice = totalSqft * pricePerSqft;
          priceAfterChange = priceBeforeChange + sqftPrice;
          $(e.currentTarget).attr("data-cCost", sqftPrice);
        } else if (costType == "lft") {
          let sizeValue = $(".select-height").val();
          let sizeInch = $(".select-height").attr("data-selected-size");
          let letterCharacters = $("#letterInput")
            .val()
            .replace(" ", "").length;
          let totalSizeInch = sizeInch * letterCharacters;
          let toalSizeFt = totalSizeInch / 12;
          let totalLftCost = selectedAttrVal * toalSizeFt;

          priceAfterChange = totalLftCost + priceBeforeChange;
          $(e.currentTarget).attr("data-cCost", totalLftCost);
        } else {
          priceAfterChange = priceBeforeChange + selectedAttrVal;

          $(e.currentTarget).attr("data-cCost", selectedAttrVal);
        }
        console.log(selectedAttrVal, priceBeforeChange, priceAfterChange);
        updatePrice(priceAfterChange, priceAfterChange);
      }
    );
    // change dimension inputs
    $(document).on("change", ".product-attibute-box input", (e) => {
      validateCalcInputs(e);

      switch (e.target.name) {
        // Height Feat
        case "height-ft":
          var heigthFt = parseInt(e.target.value) || 0;
          var heightIn =
            parseInt(
              $('.product-attibute-box input[name="height-in"]').val()
            ) || 0;
          var widhtFt =
            parseInt($('.product-attibute-box input[name="width-ft"]').val()) ||
            0;
          var widthIn =
            parseInt($('.product-attibute-box input[name="width-in"]').val()) ||
            0;
          var totalHeight = heightIn / 12 + heigthFt;
          var totalWidth = widthIn / 12 + widhtFt;
          var totalSqft = totalHeight * totalWidth;
          updateDisplay(totalHeight, totalWidth);

          break;

        // Height In.
        case "height-in":
          var heigthFt =
            parseInt(
              $('.product-attibute-box input[name="height-ft"]').val()
            ) || 0;
          var heightIn = parseInt(e.target.value) || 0;
          var widhtFt =
            parseInt($('.product-attibute-box input[name="width-ft"]').val()) ||
            0;
          var widthIn =
            parseInt($('.product-attibute-box input[name="width-in"]').val()) ||
            0;
          var totalHeight = heightIn / 12 + heigthFt;
          var totalWidth = widthIn / 12 + widhtFt;
          var totalSqft = totalHeight * totalWidth;
          updateDisplay(totalHeight, totalWidth);
          break;

        // Width Feat
        case "width-ft":
          var heigthFt =
            parseInt(
              $('.product-attibute-box input[name="height-ft"]').val()
            ) || 0;
          var heightIn =
            parseInt(
              $('.product-attibute-box input[name="height-in"]').val()
            ) || 0;
          var widhtFt = parseInt(e.target.value) || 0;
          var widthIn =
            parseInt($('.product-attibute-box input[name="width-in"]').val()) ||
            0;
          var totalHeight = heightIn / 12 + heigthFt;
          var totalWidth = widthIn / 12 + widhtFt;
          var totalSqft = totalHeight * totalWidth;
          updateDisplay(totalHeight, totalWidth);
          break;

        // Width In
        case "width-in":
          var heigthFt =
            parseInt(
              $('.product-attibute-box input[name="height-ft"]').val()
            ) || 0;
          var heightIn =
            parseInt(
              $('.product-attibute-box input[name="height-in"]').val()
            ) || 0;
          var widhtFt =
            parseInt($('.product-attibute-box input[name="width-ft"]').val()) ||
            0;
          var widthIn = parseInt(e.target.value) || 0;
          var totalHeight = heightIn / 12 + heigthFt;
          var totalWidth = widthIn / 12 + widhtFt;
          var totalSqft = totalHeight * totalWidth;
          updateDisplay(totalHeight, totalWidth);
          break;
        default:
      }
    });

    // update display on total cost change

    $("#totalCost").change((e) => {
      alert("change");
      let totalPrice = e.target.value;
      // display subtotal
      $(".product-pricing-box .price-subtotal-container  .price-subtotal").text(
        totalPrice.toFixed(2)
      );
      // display total
      $(".product-pricing-box .price-total-container  .price-total").text(
        totalPrice.toFixed(2)
      );
    });

    // letter input change action
    $("#letterInput").keyup((e) => {
      let updatedText = e.target.value;
      let letterCharacters = e.target.value.replace(" ", "").length;
      //let minInchPrice = $('#select-size option:nth-child(1)').attr('value') || 0;
      $("#letter-output").text(letterCharacters ? updatedText : "ENTER TEXT");
      let letterPricePerInch = $("#select-height").val()
        ? parseFloat($("#select-height").val())
        : 0;
      // if(letterPricePerInch == 0 ) {
      //   letterPricePerInch = minInchPrice
      //   $('#select-size').val(minInchPrice)
      // }

      resetSelect();

      let totalPrice = parseFloat(letterCharacters * letterPricePerInch);
      updatePrice(totalPrice, totalPrice);
    });

    // action select size change
    $("#select-height").change((e) => {
      let letterCharacters = $("#letterInput").val().replace(" ", "").length;
      let pricePerInch = e.target.value;
      let totalPrice = letterCharacters * pricePerInch;
      resetSelect();
      updatePrice(totalPrice, totalPrice);
    });

    // select difault value in size

    let currentSize = $("#select-height option:first-child").attr("data-size");
    $("#select-height").attr("data-selected-size", currentSize);

    $("#select-height option").click((e) => {
      let size = $(e.target).attr("data-size");
      $(e.target).parent().attr("data-selected-size", size);
    });

    // action select font change
    $("#select-font").change((e) => {
      $("#letter-output").css("font-family", e.target.value);
      $(e.target).css("font-family", e.target.value);
    });

    $("#select-font option").each((e) => {
      let fontName = $("#select-font option:nth-child(" + (e + 1) + ")").attr(
        "value"
      );
      $("#select-font option:nth-child(" + (e + 1) + ")").css(
        "font-family",
        fontName
      );
    });

    // action select color change
    $("#select-face").change((e) => {
      $("#letter-output").css("color", e.target.value.split("/")[2]);
    });

    // action select font
    // $('#select-font option').each(e => {
    //   let color = $('#select-color option:nth-child('+(e+1)+')').attr('value');
    //   $('#select-color option:nth-child('+(e+1)+')').css('color', color);
    //   $(e.target).css('font-family',e.target.value);
    // })

    // action change trimcap

    $("#select-trimcap").change((e) => {
      let trimCapColor = e.target.value;

      $("#letter-output").css({
        "text-stroke": "2px " + trimCapColor,
        "-webkit-text-stroke": "2px " + trimCapColor,
      });
    });

    // action change return color
    $("#select-return").change((e) => {
      let returnColor = e.target.value;

      $("#letter-output").css({
        "text-shadow": "3px 3px 2px " + returnColor,
      });
    });

    // product thumbnail on click gallery click

    $(".gallery-image-item").click((e) => {
      let containerHeight = $(".thumbnail-container").innerHeight();

      let imageSrc = $(e.currentTarget).attr("data-image");
      $(".single-product-thumbnail").attr("src", imageSrc);
      $(".thumbnail-container").css("height", containerHeight);
    });
  });

  // handle product categories click

  $(".category-selector").click((e) => {
    $(e.currentTarget).addClass("active");
    $(e.currentTarget).siblings().removeClass("active");
    let category = $(e.currentTarget).attr("data-cat");

    $(".product-box-container .product-box").fadeOut("fast");
    $(
      ".product-box-container .product-box[data-product-category=" +
        category +
        "]"
    ).fadeIn("slow");

    if (category == "all") {
      $(".product-box-container .product-box").fadeIn("fast");
    }
  });
})(jQuery);
