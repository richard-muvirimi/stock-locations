(($) => {
  "use strict";

  $(document).ready(() => {
    $(".stock_fields input[name='_stock']").attr("readonly", "readonly");

    $(".location_stock_fields .location_stock_qty_field input[name^='" + window.wsl.field_id + "']")
      .change(() => {
        let sum = 0;
        $(".location_stock_fields .location_stock_qty_field input[name^='" + window.wsl.field_id + "']").each(
          (index, element) => {
            sum += parseInt($(element).val());
          }
        );
        $(".stock_fields input[name='_stock']").val(sum);
      })
      .change();

    let stock = $(".stock_fields");
    stock.remove();
    $(".location_stock_fields:last").after(stock);
  });
})(jQuery);
