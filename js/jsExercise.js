/**
* ページにp要素を設置して文字列を表示する(既に設置してあれば上書きする)
* 依存するhtml要素：#show_button, #output_label
*/
function show(str) {
  if (document.getElementById('output_label') == null) {
    $('#show_button').after('<p id="output_label">' + str + '</p>');
  } else {
    $('#output_label').text(str);
  }
}

/**
* Datetime形式の文字列をページ内に表示する
*/
function showDatetime(url) {
  try {
    var request = new XMLHttpRequest();
    request.open('GET', url, true);

    request.onload = function(e) {
      if (request.status === 200) {
        show(request.responseText);
      } else {
        alert('error. status: ' + request.statusText);
      }
    };

    request.onerror = function(e) {
      alert('error. status: ' + request.statusText);
    };

    request.send(null);

  } catch (e) {
    alert('exception: ' + e.stack);
  } finally {
    return false;
  }
}

/**
* テキストボックスに入力された値をページ内に表示する
* 依存するhtml要素：input[name=input_text]
*/
function showText() {
  try {
    var input = escapeSequence($('input[name=input_text]').val());

    show(input);

    $('input[name=input_text]').val('');

  } catch (e) {
    alert('exception: ' + e.stack);
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
