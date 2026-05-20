/* ALERT AUTO CLOSE */

setTimeout(function(){

let alerts =
document.querySelectorAll(

'.alert-danger, .alert-success, .alert-warning'

);

alerts.forEach(function(alert){

alert.style.transition =
"0.5s";

alert.style.opacity="0";

setTimeout(function(){

alert.remove();

},500);

});

},3000);

/* CONFIRM DELETE */

function confirmDelete(){

return confirm(
"Yakin ingin menghapus data?"
);

}