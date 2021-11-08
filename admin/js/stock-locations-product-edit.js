(($) => {
  "use strict";

  $(document).ready(() => {
    $("#inventory_product_data ._stock_field .wc_input_stock").attr("readonly", "readonly");

    $(".stock_fields input[id^='" + window.wsl.field_id + "']")
      .change(() => {
        let sum = 0;
        $(".stock_fields input[id^='" + window.wsl.field_id + "']").each(
          (index, element) => {
            sum += parseInt($(element).val());
          }
        );
        $(".stock_fields input#_stock").val(sum);
      })
      .change();
  });
})(jQuery);
