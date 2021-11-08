(function ($) {
  "use strict";

  $(document).ready(function () {
    //select location click
    $("a." + window.wsl.name + "-button").click((e) => {
      e.preventDefault();
      $("." + window.wsl.name + "-selector.modal").fadeIn();

      //
      let button = $("." + window.wsl.name + "-save");
      let content = $("." + window.wsl.name + "-selector-content");

      let height = content.position().top - button.position().top;

      if (height < 150) {
        content.height(Math.abs(height) - 10);
      } else {
        $("." + window.wsl.name + "-selector.modal").resize();
      }
    });

    $("." + window.wsl.name + "-selector.modal").resize(() => {
      let button = $("." + window.wsl.name + "-save");

      $("." + window.wsl.name + "-selector .modal-content > div").css(
        "max-height",
        button.position().top
      );
    });

    //select location close
    $("button." + window.wsl.name + "-close").click((e) => {
      e.preventDefault();
      $(".stock-locations-selector.modal").fadeOut();
    });
  });
})(jQuery);
