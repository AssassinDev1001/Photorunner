var abc = 0; //Declaring and defining global increement variable

$(document).ready(function() {
	
	//To add new input file field dynamically, on click of "Add More Files" button below function will be executed
	$('#add_more').click(function() {
		//var filedivid = 'filediv'+abc;
		var appendHTML = '<div class="col-xs-3"><label for="exampleInputEmail1" style="padding-left:5px;">Point</label> <input type="text" class="form-control" id="points" name="points[]" placeholder="Points" required="required"> </div> <div class="col-xs-3"> <label for="exampleInputEmail1" style="padding-left:5px;">Price in $</label> <input type="text" class="form-control" id="priceusd" name="priceusd[]" placeholder="Price in $" required="required"> </div><div class="col-xs-3"> <label for="exampleInputEmail1" style="padding-left:5px;">Price in €</label> <input type="text" class="form-control" id="priceeuro" name="priceeuro[]2" placeholder="Price in €" required="required"> </div> <div class="col-xs-3"> <button type="button" onclick="test('+abc+');" style="margin-top:28px;">Remove</button> </div>';
		$(this).before($("<div/>", {id: abc, class: 'row'}).fadeIn('slow').append(appendHTML));
		abc += 1;
	});

	


});

function test(appendid)
{
	$('#'+appendid).hide();
}
