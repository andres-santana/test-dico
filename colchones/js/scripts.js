jQuery(document).ready(function($){
	var name = null;
	var coupleHas = false;
	var coupleName = null;
	var age = null;
	var coupleAge = null;
	var gender = null;
	var coupleGender = null;
	var position = null;
	var couplePosition = null;
	var height =  null;
	var coupleHeight = null;
	var weight =  null;
	var coupleWeight = null;
	var pant = null;
	var couplePant = null;
	var shirt = null;
	var coupleShirt = null;
	var finalTest = 0;
	var sumaTest = 0;
	var coupleSumaTest = 0;

	$(".cBtnq1").click(function(){
		var name = $("#name").val();
		if (name!="") {
			$("#q1").css("display","none");
			$("#q2").css("display","block");
			console.log("bt1:name: "+name);
			$(".coupleName1").html(name);
		};
	});
	$(".cBtnq2").click(function(){
		var couple = $("input[name='radioInputCouple']:checked").val();
		coupleName = $(".coupleName").val();

		if (couple=="2" && coupleName!="") {
			coupleHas = true;
			$("#q2").css("display","none");
			$("#q3").css("display","block");
			$(".coupleName2").html(coupleName);
			$("#logo").addClass("marginleft15");
			$("#menu").css("display","block");
			$("#content").removeClass("welcome-bg-color");
			$("#content").addClass("gender-bg-color");
			$("#bg").removeClass("welcome-bg-color");
			$("#bg").addClass("gender-bg-color");
			
			coupleSumaTest += parseInt(couple);  
			console.log("bt2:coupleHas:"+coupleHas+"-> coupleSumaTest:"+coupleSumaTest);
		}
		else if(couple=="1"){
			$("#q2").css("display","none");
			$("#q3").css("display","block");
			$("#logo").addClass("marginleft15");
			$("#menu").css("display","block");
			$("#content").removeClass("welcome-bg-color");
			$("#content").addClass("gender-bg-color");
			$("#bg").removeClass("welcome-bg-color");
			$("#bg").addClass("gender-bg-color");
			sumaTest += parseInt(couple);
			console.log("bt2:coupleHas:"+coupleHas+" -> sumaTest:"+sumaTest);
		}
	});
	$(".cBtnq3").click(function(){
		if (coupleHas) {
			gender = $("input[name='radioInputGender']:checked").val();
			if (gender) {
				$("#q3").css("display","none");
				$("#q3couple").css("display","block");
				sumaTest += parseInt(gender);
				console.log("btn3:coupleHas:"+coupleHas);
				console.log("btn3:gender:"+gender+" -> sumaTest:"+sumaTest);
			}
		}
		else{
			gender = $("input[name='radioInputGender']:checked").val();
			if (gender) {
				$("#q3").css("display","none");
				$("#q4").css("display","block");
				$("#content").removeClass("gender-bg-color");
				$("#content").addClass("age-bg-color");
				$("#bg").removeClass("gender-bg-color");
				$("#bg").addClass("age-bg-color");
				$("#AgeHex").css("fill","#c3d52e");
				sumaTest += parseInt(gender);
				console.log("btn3:coupleHas:"+coupleHas);
				console.log("btn3:gender:"+gender+" -> sumaTest:"+sumaTest);
			}
		}

	});
	$(".cBtnq3couple").click(function(){
			coupleGender = $("input[name='radioInputGender2']:checked").val();
			console.log("btn3Couple:gender:"+coupleGender+" -> sumaTest:"+sumaTest);
			if (coupleGender) {
				$("#q3couple").css("display","none");
				$("#q4").css("display","block");
				$("#content").removeClass("gender-bg-color");
				$("#content").addClass("age-bg-color");
				$("#bg").removeClass("gender-bg-color");
				$("#bg").addClass("age-bg-color");
				$("#AgeHex").css("fill","#c3d52e");
				coupleSumaTest += parseInt(coupleGender);
				console.log("cBtnq3couple:coupleHas:"+coupleHas);
				console.log("btn3:gender:"+coupleGender+" -> sumaTest:"+sumaTest);
			}
	});
	$(".cBtnq4").click(function(){
		if (coupleHas) {
			age = $("input[name='radioInputAge']:checked").val();
			if (age) {
				$("#q4").css("display","none");
				$("#q4couple").css("display","block");
				sumaTest += parseInt(age);
				console.log("btn4:coupleHas:"+coupleHas);
				console.log("btn4:age:"+age+" -> sumaTest:"+sumaTest);
			}
			console.log("btn4:age:"+age);
		}
		else{
			age = $("input[name='radioInputAge']:checked").val();
			if (age) {
				$("#q4").css("display","none");
				$("#q5").css("display","block");
				$("#content").removeClass("age-bg-color");
				$("#content").addClass("position-bg-color");
				$("#bg").removeClass("age-bg-color");
				$("#bg").addClass("position-bg-color");
				$("#PositionHex").css("fill","#ee7622");
				sumaTest += parseInt(age);
				console.log("btn4:coupleHas:"+coupleHas);
				console.log("btn4:age:"+age+" -> sumaTest:"+sumaTest);
			}
		}
	});
	$(".cBtnq4couple").click(function(){
			coupleAge = $("input[name='radioInputAge2']:checked").val();
			console.log("btn4Couple:age:"+coupleGender+" -> sumaTest:"+sumaTest);
			if (coupleAge) {
				$("#q4couple").css("display","none");
				$("#q5").css("display","block");
				$("#content").removeClass("age-bg-color");
				$("#content").addClass("position-bg-color");
				$("#bg").removeClass("age-bg-color");
				$("#bg").addClass("position-bg-color");
				$("#PositionHex").css("fill","#ee7622");
				coupleSumaTest += parseInt(coupleAge);
				console.log("cBtnq4couple:coupleHas:"+coupleHas);
				console.log("btn4:age:"+coupleAge+" -> sumaTest:"+sumaTest);
			}
	});
	$(".cBtnq5").click(function(){
		if (coupleHas) {
			position = $("input[name='radioInputPosition']:checked").val();
			if (position) {
				$("#q5").css("display","none");
				$("#q5couple").css("display","block");
				sumaTest += parseInt(position);
				console.log("btn5:coupleHas:"+coupleHas);
				console.log("btn5:position:"+position+" - sumaTest:"+sumaTest);
			}
			console.log("btn5:coupleHas:"+coupleHas);
		}
		else{
			position = $("input[name='radioInputPosition']:checked").val();
			if (position) {
				$("#q5").css("display","none");
				$("#q6").css("display","block");
				$("#content").removeClass("position-bg-color");
				$("#content").addClass("height-bg-color");
				$("#bg").removeClass("position-bg-color");
				$("#bg").addClass("height-bg-color");
				$("#HeightHex").css("fill","#9bb8bf");
				sumaTest += parseInt(position);
				console.log("btn5:coupleHas:"+coupleHas);
				console.log("btn5:position:"+position+" - sumaTest:"+sumaTest);
			}
		}
	});
	$(".cBtnq5couple").click(function(){
			couplePosition = $("input[name='radioInputPosition2']:checked").val();
			console.log("btn5Couple:position:"+couplePosition+" -> sumaTest:"+sumaTest);
			if (couplePosition) {
				$("#q5couple").css("display","none");
				$("#q6").css("display","block");
				$("#content").removeClass("position-bg-color");
				$("#content").addClass("height-bg-color");
				$("#bg").removeClass("position-bg-color");
				$("#bg").addClass("height-bg-color");
				$("#HeightHex").css("fill","#9bb8bf");
				coupleSumaTest += parseInt(couplePosition);
				console.log("cBtnq5couple:coupleHas:"+coupleHas);
				console.log("btn5:position:"+couplePosition+" -> sumaTest:"+sumaTest);
			}
	});
	$(".cBtnq6").click(function(){
		if (coupleHas) {
			height = $("input[name='radioInputHeight']:checked").val();
			if (height) {
				$("#q6").css("display","none");
				$("#q6couple").css("display","block");
				sumaTest += parseInt(height);
				console.log("btn6:coupleHas:"+coupleHas);
				console.log("btn6:height:"+height+" - sumaTest:"+sumaTest);
			}
			console.log("btn6:coupleHas:"+coupleHas);
		}
		else{
			height = $("input[name='radioInputHeight']:checked").val();
			if (height) {
				$("#q6").css("display","none");
				$("#q7").css("display","block");
				$("#content").removeClass("height-bg-color");
				$("#content").addClass("weight-bg-color");
				$("#bg").removeClass("height-bg-color");
				$("#bg").addClass("weight-bg-color");
				$("#WeightHex").css("fill","#ee7622");
				sumaTest += parseInt(height);
				console.log("btn6:coupleHas:"+coupleHas);
				console.log("btn6:height:"+height+" - sumaTest:"+sumaTest);
			}
		}
	});
	$(".cBtnq6couple").click(function(){
			coupleHeight = $("input[name='radioInputHeight2']:checked").val();
			console.log("btn6Couple:height:"+coupleHeight+" -> sumaTest:"+sumaTest);
			if (coupleHeight) {
				$("#q6couple").css("display","none");
				$("#q7").css("display","block");
				$("#content").removeClass("height-bg-color");
				$("#content").addClass("weight-bg-color");
				$("#bg").removeClass("height-bg-color");
				$("#bg").addClass("weight-bg-color");
				$("#WeightHex").css("fill","#ee7622");
				coupleSumaTest += parseInt(coupleHeight);
				console.log("cBtnq6couple:coupleHas:"+coupleHas);
				console.log("btn6:height:"+coupleHeight+" -> sumaTest:"+sumaTest);
			}
	});
	$(".cBtnq7").click(function(){
		if (coupleHas) {
			weight = $("input[name='radioInputWeight']:checked").val();
			if (weight) {
				$("#q7").css("display","none");
				$("#q7couple").css("display","block");
				sumaTest += parseInt(weight);
				console.log("btn7:coupleHas:"+coupleHas);
				console.log("btn7:weight:"+weight+" - sumaTest:"+sumaTest);
			}
			console.log("btn7:coupleHas:"+coupleHas);
		}
		else{
			weight = $("input[name='radioInputWeight']:checked").val();
			if (weight) {
				$("#q7").css("display","none");
				$("#q8").css("display","block");
				$("#content").removeClass("weight-bg-color");
				$("#content").addClass("sizes-bg-color");
				$("#bg").removeClass("weight-bg-color");
				$("#bg").addClass("sizes-bg-color");
				$("#SizesHex").css("fill","#c3d52e");
				sumaTest += parseInt(weight);
				console.log("btn7:coupleHas:"+coupleHas);
				console.log("btn7:weight:"+weight+" - sumaTest:"+sumaTest);
			}
		}
	});
	$(".cBtnq7couple").click(function(){
			coupleWeight = $("input[name='radioInputWeight2']:checked").val();
			console.log("btn7Couple:coupleweight:"+coupleWeight+" -> sumaTest:"+sumaTest);
			if (coupleWeight) {
				$("#q7couple").css("display","none");
				$("#q8").css("display","block");
				$("#content").removeClass("weight-bg-color");
				$("#content").addClass("sizes-bg-color");
				$("#bg").removeClass("weight-bg-color");
				$("#bg").addClass("sizes-bg-color");
				$("#SizesHex").css("fill","#c3d52e");
				coupleSumaTest += parseInt(coupleWeight);
				console.log("cBtnq6couple:coupleHas:"+coupleHas);
				console.log("btn6:height:"+coupleWeight+" -> sumaTest:"+sumaTest);
			}
	});
	$(".cBtnq8").click(function(){
		if (coupleHas) {
			pant = $("input[name='pantInput']:checked").val();
			shirt = $("input[name='shirtInput']:checked").val();
			console.log("btn8:pant:"+pant);
			console.log("btn8:shirt:"+shirt);
			if (pant && shirt) {
				$("#q8").css("display","none");
				$("#q8couple").css("display","block");
				sumaTest += parseInt(pant);
				sumaTest += parseInt(shirt);
				console.log("btn8:coupleHas:"+coupleHas);
				console.log("btn8:pant:"+pant+" - sumaTest:"+sumaTest);
				console.log("btn8:shirt:"+shirt+" - sumaTest:"+sumaTest);
			}
			console.log("btn8:coupleHas:"+coupleHas);
		}
		else{
			pant = $("input[name='pantInput']:checked").val();
			shirt = $("input[name='shirtInput']:checked").val();
			console.log("btn8:pant:"+pant);
			console.log("btn8:shirt:"+shirt);
			if (pant && shirt) {
				$("#q8").css("display","none");
				$("#q9").css("display","block");
				$("#content").removeClass("sizes-bg-color");
				$("#content").addClass("pain-bg-color");
				$("#bg").removeClass("sizes-bg-color");
				$("#bg").addClass("pain-bg-color");
				$("#PainHex").css("fill","#37929a");
				sumaTest += parseInt(pant);
				sumaTest += parseInt(shirt);
				console.log("btn8:coupleHas:"+coupleHas);
				console.log("btn8:pant:"+pant+" - sumaTest:"+sumaTest);
				console.log("btn8:shirt:"+shirt+" - sumaTest:"+sumaTest);
			}
		}
	});
	$(".cBtnq8couple").click(function(){
			couplePant = $("input[name='pantInput2']:checked").val();
			coupleShirt = $("input[name='shirtInput2']:checked").val();
			console.log("btn8:pant:"+couplePant);
			console.log("btn8:shirt:"+coupleShirt);
			if (couplePant && coupleShirt) {
				$("#q8couple").css("display","none");
				$("#q9").css("display","block");
				$("#content").removeClass("sizes-bg-color");
				$("#content").addClass("pain-bg-color");
				$("#bg").removeClass("sizes-bg-color");
				$("#bg").addClass("pain-bg-color");
				$("#PainHex").css("fill","#37929a");
				coupleSumaTest += parseInt(couplePant);
				coupleSumaTest += parseInt(coupleShirt);
				console.log("btn8:coupleHas:"+coupleHas);
				console.log("btn8:couplepant:"+couplePant+" - sumaTest:"+sumaTest);
				console.log("btn8:coupleshirt:"+coupleShirt+" - sumaTest:"+sumaTest);
			}
	});
	$(".cBtnq9").click(function(){
		if (coupleHas) {
			$("#q9").css("display","none");
			$("#q9couple").css("display","block");
		}
		else{
			$("#q9").css("display","none");
			$("#q10").css("display","block");
			$("#logo").removeClass("marginleft15");
			$("#menu").css("display","none");
			$("#content").removeClass("pain-bg-color");
			$("#content").addClass("welcome-bg-color");
			$("#bg").removeClass("pain-bg-color");
			$("#bg").addClass("welcome-bg-color");
		}
	});
	$(".cBtnq9couple").click(function(){
			$("#q9couple").css("display","none");
			$("#q10").css("display","block");
			$("#logo").removeClass("marginleft15");
			$("#menu").css("display","none");
			$("#content").removeClass("pain-bg-color");
			$("#content").addClass("welcome-bg-color");
			$("#bg").removeClass("pain-bg-color");
			$("#bg").addClass("welcome-bg-color");
	});
	$(".cBtnq10").click(function(){
		if (coupleHas) {
			$("#single1").removeClass("singleon");
			$("#single1").addClass("singleoff");
			$("#couple1").removeClass("coupleoff");
			$("#couple1").addClass("coupleon");
			$("#single2").removeClass("singleon");
			$("#single2").addClass("singleoff");
			$("#couple2").removeClass("coupleoff");
			$("#couple2").addClass("coupleon");
			$("#single3").removeClass("singleon");
			$("#single3").addClass("singleoff");
			$("#couple3").removeClass("coupleoff");
			$("#couple3").addClass("coupleon");
			var sumaFinal = (coupleSumaTest+sumaTest)/2;
			console.log("sumaTest: "+sumaTest);
			console.log("coupleSumaTest: "+coupleSumaTest);
			console.log("sumaFinal: "+sumaFinal);
			if(sumaFinal<=13){
				$("#q10").css("display","none");
				$("#flex").css("display","block");	
			}
			if(sumaFinal<=19 && sumaTest>13){
				$("#q10").css("display","none");
				$("#semiflex").css("display","block");
				$("#bg").removeClass("welcome-bg-color");
				$("#bg").addClass("age-bg-color");
			}
			if(sumaFinal>19){
				$("#q10").css("display","none");
				$("#firme").css("display","block");
				$("#bg").removeClass("welcome-bg-color");
				$("#bg").addClass("firme-bg-color");
			}
			console.log("btn10:coupleHas:"+coupleHas);
		}
		else{
			if(sumaTest<=13){
				$("#q10").css("display","none");
				$("#flex").css("display","block");	
			}
			if(sumaTest<=19 && sumaTest>13){
				$("#q10").css("display","none");
				$("#semiflex").css("display","block");
				$("#bg").removeClass("welcome-bg-color");
				$("#bg").addClass("age-bg-color");
			}
			if(sumaTest>19){
				$("#q10").css("display","none");
				$("#firme").css("display","block");
				$("#bg").removeClass("welcome-bg-color");
				$("#bg").addClass("firme-bg-color");
			}
			console.log("sumaTest: "+sumaTest);
			
		}
	});
	$(".cBtnq11").click(function(){
			location.reload();
	});
});