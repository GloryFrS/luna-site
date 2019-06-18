$(document).ready(function() {
	$("#circle1").circleProgress({
    value: 0.5,
    animation: false,
    size: 132,
    thickness: 4,
    startAngle: -Math.PI / 2,
    fill: {gradient: ['#507ab2', '#507ab2']}
  });
	$("#circle2").circleProgress({
    value: 0.5,
    animation: false,
    size: 132,
    thickness: 4,
    startAngle: -Math.PI / 2,
    fill: {gradient: ['#507ab2', '#507ab2']}
  });
	$("#circle3").circleProgress({
    value: 0.25,
    animation: false,
    size: 132,
    thickness: 4,
    startAngle: -Math.PI / 2,
    fill: {gradient: ['#507ab2', '#507ab2']}
  });
	$("#circle4").circleProgress({
    value: 0.25,
    animation: false,
    size: 132,
    thickness: 4,
    startAngle: -Math.PI / 2,
    fill: {gradient: ['#507ab2', '#507ab2']}
  });
    $('.slider_big_slides').slick({
      infinite: true,
      arrows: false,
      dots: true,
      appendDots: $(".slider_big_controls"),
    });
    $('.etaps_slider').slick({
      infinite: true,
      arrows: true,
      dots: true,
      appendDots: $(".etaps_controls"),
        prevArrow: $('.etaps_arrow.left'),
        nextArrow: $('.etaps_arrow.right'),
    });
    $('.portfolio_slider').slick({
      infinite: true,
      arrows: true,
      dots: false,
        prevArrow: $('.portfolio_slider_arrow.left'),
        nextArrow: $('.portfolio_slider_arrow.right'),
    });
    $('.portfolio_slider').on('afterChange', function(event, slick, currentSlide){
      $(".portfolio_description").hide();
      $(".portfolio_description:nth-child("+(currentSlide+1)+")").show();
    });
    $(".header_contacts_button, .slider_big_slide_button").click(function() {
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
        return false;
    });
    $('.image_slider_wrapper').slick({
      infinite: true,
      arrows: true,
      dots: false,
        prevArrow: $('.image_slider_arrow.left'),
        nextArrow: $('.image_slider_arrow.right'),
    });
    $(".callback_form_submit_button").click(function() {
        var th = $(".callback form"), name = th.find("input[name=name]").val(), phone = th.find("input[name=phone]").val(), agree = th.find("input[name=agree]");
        th.find("input[name=name]").css('border', 'none');
        th.find("input[name=phone]").css('border', 'none');
        if (name == '') {
          th.find("input[name=name]").css('border', '1px solid red');
        }
        if (phone == '') {
          th.find("input[name=phone]").css('border', '1px solid red');
        }
        if (!agree.is(':checked')) alert('Вы не дали согласие на обработку персональных данных!');
        if (name !== '' && phone !== '' && agree.is(':checked')) $.ajax({
            type: "POST",
            url: "mail.php", //Change
            data: th.serialize()
        }).done(function(result) {
            $(".callback").html('<h2>Спасибо за заявку! Скоро мы с вами свяжемся.</h2>');
        });
        return false;
    });
});