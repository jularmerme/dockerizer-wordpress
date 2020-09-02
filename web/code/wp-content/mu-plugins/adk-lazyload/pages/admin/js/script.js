window.addEventListener('load', readyFunction, false);

function readyFunction() {
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    if (inputs.item(i).type == 'checkbox') {
      inputs.item(i).addEventListener('click', function (e) {
        var inputText = locateTextbox(this.value);
        if (inputText) {
          if (this.checked) {
            inputText.disabled = false;
            inputText.focus();
          } else {
            inputText.disabled = true;
          }
        }
        displayOutput();
      });
    }
    else if (inputs.item(i).type == 'text' || inputs.item(i).type == 'number') {
      inputs.item(i).addEventListener('change', displayOutput);
    }
  }
  if (document.getElementById('adkll_output_shortcode')) {
    document.getElementById('adkll_output_shortcode').addEventListener('dblclick', function (e) {
      this.select();
    });
  }
  displayOutput();
}

function locateTextbox(attribute) {
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    var input = inputs.item(i);
    if (input.type == 'text' || input.type == 'number') {
      if (input.name == attribute) {
        return input;
      }
    }
  }
  return false;
}

function displayOutput() {
  var output = "[adk-lazyload";
  var inputs = document.getElementsByTagName('input');
  for (var i = 0; i < inputs.length; i++) {
    var input = inputs.item(i);
    if (input.type == 'checkbox') {
      if (input.checked) {
        var val = "";
        var inputText = locateTextbox(input.value);
        if (inputText) {
          val = inputText.value;
        }
        if (input.value == 'load_pagination' || input.value == 'load_scroll' || input.value == 'load_button' || input.value == 'autoload' || input.value == 'display_query' || input.value == 'pagination_url' || input.value == 'use_elasticPress') {
          val = 'true';
        }
        output += " " + input.value + "='" + val + "'";
      }
      else {
        if (input.value == 'load_scroll' || input.value == 'load_button' || input.value == 'autoload') {
          output += " " + input.value + "='false'";
        }
      }
    }
  }
  output += "]";
  if (document.getElementById('adkll_output_shortcode')) {
    document.getElementById('adkll_output_shortcode').value = output;
  }
}