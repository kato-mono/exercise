function showText() {
  try {
    var input = escapeSequence($('input[name=input_text]').val());

    if (document.getElementById('output_label') == null) {
      $('#show_button').after('<p id="output_label">' + input + '</p>');
    } else {
      $('#output_label').text(input);
    }

    $('input[name=input_text]').val('');

  } catch (e) {
    alert('[exception]: ' + e.stack);
  } finally {
    return false;
  }
}

function escapeSequence(str) {
  str = str.toString();
  str = str.replace(/&/g, '&amp;');
  str = str.replace(/</g, '&lt;');
  str = str.replace(/>/g, '&gt;');
  str = str.replace(/"/g, '&quot;');
  str = str.replace(/'/g, '&#39;');
  return str;
}
