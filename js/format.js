String.prototype.format = function() {
  var args = arguments[0];
//  console.log(args);
  return this.replace(/{([a-z|A-Z|-]+)}/g, function(match, key) { 
    return typeof args[key] != 'undefined'
      ? args[key]
      : ''
    ;
  });
};