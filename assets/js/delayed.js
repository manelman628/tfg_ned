let delayed = (function(){
    let timer = 0;
    return function(callback, ms){
      clearTimeout(timer); //reset timeout
      timer = setTimeout(callback, ms);
    };
  })(); //closure. will execute function when defined. Emulating private methods - Module pattern
