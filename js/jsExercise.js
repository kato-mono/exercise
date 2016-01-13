function sayHello() {
  if (document.getElementById('#hello_label') == null) {
    $('#hello_button').after('<p id="hello_label">hello world</p>');
  }
}
