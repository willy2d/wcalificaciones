// Internet Explorer
window.onload = function()
{
     document.onselectstart = function()
     {
          return false;
     } 
// Firefox
     document.onmousedown = function()
     {
          return false;
     }
}