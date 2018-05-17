$('#btnshow').click(function(e){
  e.preventDefault();
  var input_ayat = $('#txtayat').val();

  $.ajax({
     url: '<?php echo $this->Html->url(array('controller' => 'alkitab', 'action'=>'baca')); ?>/' + input_ayat,
     success: function(data) {
        var obj = jQuery.parseJSON(data);
        var arrayLength = obj.length;
        var string = '';
	if (obj.code !== undefined && obj.code !== null) {
	  string = obj.message;
	} else {
	  for (var i = 0; i < arrayLength; i++) {
	    string = string + '<p class="lead">' + obj[i].Alkitab.kitab + ' ' + obj[i].Alkitab.pasal +  ':' + obj[i].Alkitab.ayat +'</p>' +
	                      '<p>' +  obj[i].Alkitab.firman + '</p>';
          }
        }
	$('#ayat').html(string);
     },
     error: function(err) {
        $('#ayat').html('<p>An error has occurred</p>');
     }
  });
});
