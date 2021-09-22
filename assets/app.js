/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import "./styles/app.scss";

const $ = require("jquery");
global.$ = global.jQuery = $;

import { Tooltip, Toast, Popover } from "bootstrap";

// start the Stimulus application
import "./bootstrap";

require("jquery-ui/ui/widgets/autocomplete");

document.addEventListener("DOMContentLoaded", function () {
  var links = document.querySelectorAll("[data-href]");
  for (let i = 0; i < links.length; i++) {
    links[i].addEventListener("click", function (event) {
      window.location.href = this.getAttribute("data-href");
    });
  }
});

$(document).ready(function () {
  var sourceUrl = $('[type="search"]').data("source");
  $('input[type="search"]').autocomplete({
    source: function (request, response) {
      $.getJSON(sourceUrl + "?term=" + request.term, function (data) {
        response(
          $.map(data, function (value, key) {
            return {
              label: value,
              value: key,
            };
          })
        );
      });
    },
    minLength: 2,
    appendTo: ".searchResults",
    select: function (event, ui) {
      window.location.href = "/matches/team/" + ui.item.value;
      return false;
    },
  });
});
