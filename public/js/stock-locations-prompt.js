(function ($) {
  "use strict";

  $(document).ready(function () {
    //save
    $("button." + window.wsl.name + "-save").click((e) => {
      let location = "";

      switch ($(e.target).data("type")) {
        case "prompt":
          location = $("select[name='" + window.wsl.name + "-prompt']")
            .first()
            .val();
          break;
        case "selector":
          location = $("input[name='" + window.wsl.name + "-location']:checked")
            .first()
            .val();
          break;
        default:
          location = window.wsl.default;
          break;
      }

      let url = new URL(window.location.href);
      let params = new URLSearchParams(url.searchParams);

      //Add a second foo parameter.
      params.append("action", window.wsl.name);
      params.append(window.wsl.slug, location);
      params.append("redirect", window.location.href);

      url.search = params.toString();

      window.location.href = url.toString();
    });
  });
})(jQuery);
