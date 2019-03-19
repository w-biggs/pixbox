if (document.readyState != 'loading'){
  passcheck();
} else {
  document.addEventListener("DOMContentLoaded", function() {
    passcheck();
  });
}

function passcheck(){
  var passcheck = document.getElementById('passcheck');
  var pass = document.getElementById('album_pass');
  passcheck.addEventListener('change', function(event){
    checkPass(event);
  });
  checkPass();
  function checkPass(event){
    var target;
    if(typeof event === "undefined"){
      target = passcheck;
    } else {
      target = event.target;
    }
    if(target.checked){
      pass.removeAttribute('disabled');
    } else {
      pass.setAttribute('disabled', '');
    }
  }
}