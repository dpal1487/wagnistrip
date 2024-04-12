function openNav() {
    document.getElementById("mySidenav").style.width = "100%";
  }
  
  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
  }


  $('#offtab1').click(function(){
    $('#TRENDING').show();
    $('#FLIGHTS').hide();
    $('#HOTELS').hide();
    $('#VILLA').hide();
  });

  $('#offtab2').click(function(){
    $('#FLIGHTS').show();
    $('#TRENDING').hide();
    $('#HOTELS').hide();
    $('#VILLA').hide();
  });

  $('#offtab3').click(function(){
    $('#HOTELS').show();
    $('#TRENDING').hide();
    $('#FLIGHTS').hide();
    $('#VILLA').hide();
  });

  $('#offtab4').click(function(){
    $('#VILLA').show();
    $('#TRENDING').hide();
    $('#FLIGHTS').hide();
    $('#HOTELS').hide();
  });

  

// code by vikas
var activeElement = null;

function rotriprice() {
  if (activeElement !== '.price') {
    hideActiveElement();
    $('.price').toggle();
    activeElement = '.price';
  } else {
    hideActiveElement();
  }
}

function Airlines() {
  if (activeElement !== '.Airlines') {
    hideActiveElement();
    $('.Airlines').toggle();
    activeElement = '.Airlines';
  } else {
    hideActiveElement();
  }
}

function Time() {
  if (activeElement !== '.Time') {
    hideActiveElement();
    $('.Time').toggle();
    activeElement = '.Time';
  } else {
    hideActiveElement();
  }
}

function Duration() {
  if (activeElement !== '.Duration') {
    hideActiveElement();
    $('.Duration').toggle();
    activeElement = '.Duration';
  } else {
    hideActiveElement();
  }
}

function Stops() {
  if (activeElement !== '.Stops') {
    hideActiveElement();
    $('.Stops').toggle();
    activeElement = '.Stops';
  } else {
    hideActiveElement();
  }
}

function hideActiveElement() {
  if (activeElement) {
    $(activeElement).toggle();
    activeElement = null;
  }
}
// \\\\\\\\\\\\\\\\\\\\\\\\


//   function rotriprice(){
    
//     $('.price').toggle();
//   }

//   function Airlines(){
//     $('.Airlines').toggle();
//   }

//   function Time(){
//     $('.Time').toggle();
//   }

//   function Duration(){
//     $('.Duration').toggle();
//   }

//   function Stops(){
//     $('.Stops').toggle();
//   }


  

  $('#UPI').click(function(){
    $(this).addClass("activePay"); 
    $('#CREADITATM').removeClass("activePay"); 
    $('#PAYLATER').removeClass("activePay"); 
    $('#upi').show();
    $('#creditAtm').hide();
    $('#paylater').hide();
  });

  $('#CREADITATM').click(function(){
    $(this).addClass("activePay");
    $('#UPI').removeClass("activePay"); 
    $('#PAYLATER').removeClass("activePay");
    $('#creditAtm').show();
    $('#upi').hide();
    $('#paylater').hide();
 
  });

  $('#PAYLATER').click(function(){
    $(this).addClass("activePay");
    $('#CREADITATM').removeClass("activePay"); 
    $('#UPI').removeClass("activePay"); 
    $('#paylater').show();
    $('#upi').hide();
    $('#creditAtm').hide();
   
  });

 
