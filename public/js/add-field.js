//Add Input Fields
$(document).ready(function() {
    var max_fields = 10; //Maximum allowed input fields 
    var wrapper    = $(".wrapper"); //Input fields wrapper
    var add_button = $(".add_fields"); //Add button class or ID
    var x = 1; //Initial input field is set to 1

//- Using an anonymous function:

  
 //When user click on add input button
 $(add_button).click(function(e){
    alert('aaaa');
        e.preventDefault();
 //Check maximum allowed input fields
        if(x < max_fields){ 
            x++; //input field increment
 //add input field
            $(wrapper).append('<div class="mb-3 remove-listinput"><select class="form-select me-2"><option>Select a Service </option><option>Select a Service </option><option>Select a Service </option></select> <a href="javascript:void(0);" class="remove_field rem-btn"><img src="images/minus-circle.svg"/></a></div>');
        }
    });
 
    //when user click on remove button
    $(wrapper).on("click",".remove_field", function(e){ 
        e.preventDefault();
 $(this).parent('div').remove(); //remove inout field
 x--; //inout field decrement
    })
});


$(".information-tabs").click(function() {
 
    $("#product-box-tabs").show();
    $("#product-desc-tabs").hide();
    $(".information-tabs").addClass("btn-product");
	$(".description-product").removeClass("btn-product");
	
  });

  $(".description-product").click(function(){
    $("#product-box-tabs").hide();
    $("#product-desc-tabs").show();
    $(".information-tabs").removeClass("btn-product");
    $(".description-product").addClass("btn-product");
	
  });

  $(document).on('click','.information-tabs-1',function(e) {
    $("#product-box-tabs-1").show();
    $("#product-desc-tabs-1").hide();
    $(".information-tabs-1").addClass("btn-product");
    $(".description-product-1").removeClass("btn-product");
  });
  $(document).on('click','.description-product-1',function(e) {
    $("#product-box-tabs-1").hide();
    $("#product-desc-tabs-1").show();
    $(".information-tabs-1").removeClass("btn-product");
    $(".description-product-1").addClass("btn-product");
  });
  
 $(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
    
    var x = 1; //initlal text box count
    $(add_button).on("click",function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div class="mb-3 remove-listinput"><input type="text" class="form-control me-2" placeholder="Customer Name"><a href="#" class="remove_field"><img src="images/minus-circle.svg"/></a></div>'); //add input box
        }
    });
    
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});





function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#imagePreview').css('background-image', 'url('+e.target.result +')');
            $('#imagePreview').hide();
            $('#imagePreview').fadeIn(650);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$("#imageUpload").change(function() {
    readURL(this);
});

$(function() {
  $( "#datepicker" ).datepicker({ firstDay: 1});
  
});




$('.gallery-responsive').slick({
  dots: false,
  infinite: true,
  arrows: false,
  speed: 300,
  autoplay:true,
  autoplaySpeed: 2000,
  slidesToShow: 4,
  slidesToScroll: 1,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 1,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
});




		
		
		$(document).ready(function(){
  $(".edit-td").click(function(){
    $(".input-editable").show();
    $(".date-edit").hide();
    $(".edit-td").hide();
    $(".save-td").show();
  });
  $(".save-td").click(function(){
    $(".input-editable").hide();
    $(".date-edit").show();
    $(".edit-td").show();
    $(".save-td").hide();
  });
  
  
  
  
});
$(document).ready(function() {
// $('.select2').select2({
// closeOnSelect: false,
// placeholder : "Placeholder",
// 			allowHtml: true,
// 			allowClear: true,
// 			tags: true,
				
// });
// $('.select3').select2({
// 	 dropdownParent: $('#add-personnel'),
// closeOnSelect: false,
// placeholder : "Placeholder",
// 			allowHtml: true,
// 			allowClear: true,
// 			tags: true,
				
// });
});

  	