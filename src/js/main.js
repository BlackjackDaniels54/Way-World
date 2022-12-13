var screen = window.innerWidth;
console.log(screen);

setInterval(function(){
  modalcontent = document.getElementById('modal-content'); 
  if(screen < 850) {
    modalcontent.style.display="block";
  } else {
  modalcontent.style.display="flex";
  }
  bgcolor = document.getElementById('gray-bg-color');
  bgcolor.style.display="block";
  
  document.getElementById('modal-close').addEventListener("click",function(){
    modalcontent.style.display="none";
  })
}, 120000);



function onEntry(entry) {
  entry.forEach(change => {
    if (change.isIntersecting) {
     change.target.classList.add('element-show');
    } 
    // else {
    //  change.target.classList.remove('element-show');
    // }
  });
}

let options = {
  threshold: [0.5] };
let observer = new IntersectionObserver(onEntry, options);

let elementsLeft = document.querySelectorAll('.element-animation-left-to-right');
let elementsRight = document.querySelectorAll('.element-animation-right-to-left');
let elementUpToDown = document.querySelectorAll('.element-animation-up-to-down');
let elementDownToUp = document.querySelectorAll('.element-animation-down-to-up');

for (let elm of elementDownToUp) {
  observer.observe(elm);
}
for(let elm of elementUpToDown) {
  observer.observe(elm);
}
for (let elm of elementsRight) {
  observer.observe(elm);
}
for (let elm of elementsLeft) {
  observer.observe(elm);
}


$(function() {
  $('#grid-item-one').hover(
         function(){ 
             $(this).removeClass('element-animation-left-to-right');   
    }        
  );
  $('#grid-item-two').hover(
         function(){ 
             $(this).removeClass('element-animation-down-to-up');   
    }
  );
$('#grid-item-three').hover(
          function(){ 
              $(this).removeClass('element-animation-right-to-left');   
    }
  );
});








$(document).ready(function() {
	
  //Contact form!
    
  $('#contact_form').on('submit', function (e) { 
    $.ajax({
      type: "POST",
      url: "/handler.php",
      data: $(this).serialize(),
      success: function (response) {				
        var message_type = 'alert-' + response.type;
        var message_text = response.message;
        var message = '<div class="alert ' + message_type + ' alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + message_text + '</div>';
        if (message_type && message_text) {
          $('#contact_form').find('.messages').html(message);
          $('#contact_form')[0].reset();
          grecaptcha.reset();
        }
      }
    });
    return false;       
  })        
});