/***********************
* Adobe Edge Animate Composition Actions
*
* Edit this file with caution, being careful to preserve 
* function signatures and comments starting with 'Edge' to maintain the 
* ability to interact with these actions from within Adobe Edge Animate
*
***********************/
(function($, Edge, compId){
var Composition = Edge.Composition, Symbol = Edge.Symbol; // aliases for commonly used Edge classes

   //Edge symbol: 'stage'
   (function(symbolName) {
      
      
      Symbol.bindElementAction(compId, symbolName, "document", "compositionReady", function(sym, e) {
	    
	    //accessible variables
	    var body, stage, stageWidth, stageHeight, stageParent, isDevice, interactionUp, interactionDown, currentImage, squareArray, stripsArray;

	    //path to your images
	    var IMAGE_PATH = 'images/';
	    //Set your images in an array
	    var IMAGES_ARRAY = ['page0.jpg', 'page1.jpg', 'page2.jpg', 'page3.jpg', 'page4.jpg', 'page5.jpg', 'page6.jpg', 'page7.jpg'];
	  
	    //set the transition types for each image - this number must match the number of images (obviously)
	    //choices so far are rushTransition, cityLightsTransition and simpleTransition
	    var TRANSITION_TYPE_ARRAY = [rushTransition, cityLightsTransition, simpleTransition, rushTransition, cityLightsTransition, simpleTransition, cityLightsTransition, simpleTransition];

	    //transition in type - play around!
	    //var TRANSITION_EASE_IN = Back.easeInOut.config(3);
	    //var TRANSITION_EASE_IN = RoughEase.ease;
	    //var TRANSITION_EASE_IN = SteppedEase.config(2);
	    //var TRANSITION_EASE_IN = Power3.easeInOut;
	    //var TRANSITION_EASE_IN = Power4.easeIn;
	    var TRANSITION_EASE_IN = Power2.easeIn;

	    //transition out type - play around!
	    //var TRANSITION_EASE_OUT = Back.easeOut.config(3);
	    //var TRANSITION_EASE_OUT = Back.easeOut.config(2);
	    var TRANSITION_EASE_OUT = Power4.easeIn;
	    var TRANSITION_EASE_OUT = Power2.easeOut;

	    //how long the transition lasts
	    var TRANSITION_IN_DURATION = 2;
	    var TRANSITION_OUT_DURATION = 2;

	    //how long the image stays visible for
	    var TRANSITION_DELAY = 3;
	    //time between each individual square transitioning
	    var TRANSITION_STAGGER = 0.0082;

	    //keep these as multiples of your stage width and height
		var SQUARE_SIZE = 50;
		var RECT_WIDTH = 100;
		var RECT_HEIGHT = 50;
		
		//local variables for the creation of the squares
		var numSquares, container, numColumns, numRows

		//if you want the transition of each square to be random (as opposed to row by row)
		var RANDOM_SEQUENCE = true;

		//randomise the images?
		var RANDOMISE_ARRAY = true;

		//if you want the transitions to start form the end
		var REVERSE_ARRAY = false;



        function init (){

            //reference to the stage, body and stage parent
            body = $("body");
         	stage = $("#Stage");
         	stageParent = stage.parent();

         	//stage dimensions
         	stageWidth = stage.width();
         	stageHeight = stage.height();
			numRows = stageHeight/SQUARE_SIZE;
			numColumns = stageWidth/SQUARE_SIZE;

			numSquares = numRows * numColumns;
			container = $('<div>');
			squareArray = [];
			stripsArray = [];


         	TweenMax.set(body, {
				
				backgroundColor:'rgba(51,51,51,1)'

         	})

         	currentImage = 0;

         	//this is called once and sets up the squares and the first image 
         	createSquares(0);


		}//end of init function


		function createSquares(id){

			if(RANDOMISE_ARRAY){

				IMAGES_ARRAY = shuffle(IMAGES_ARRAY);
			}


			TweenMax.set(container, {
				position:'absolute',
				width:stageWidth,
				height:stageHeight,
				zIndex:1
			});



			var i = numSquares;
			var rowCount = 0;
			var columnCount = 0;
			var tempArray = [];

			for(var i = 0; i < numSquares; i++){

				var initData = {};
				initData.posX = columnCount * SQUARE_SIZE;
				initData.posY = rowCount * SQUARE_SIZE;
				initData.transformOrigin = '50% 50%';
				var bpPosX = -(columnCount * SQUARE_SIZE);
				var bpPosY = -(rowCount * SQUARE_SIZE);
				initData.bpPosX = bpPosX + 'px';
				initData.bpPosY = bpPosY + 'px';
				initData.backgroundPosition = bpPosX + 'px ' + bpPosY + 'px';

				var sqr = $('<div>');
				TweenMax.set(sqr, {
				position:'absolute',
					width:SQUARE_SIZE,
					height:SQUARE_SIZE,
					backgroundImage:'url(' + IMAGE_PATH + IMAGES_ARRAY[id] + ')',
					backgroundPosition:initData.backgroundPosition,
					overflow:'hidden',
					x:initData.posX,
					y:initData.posY,
					z:0.01,
					transformOrigin:initData.transformOrigin
				})

				sqr.appendTo(stage);

				sqr.data(initData);

				columnCount ++;

				if(columnCount == numColumns){

					rowCount ++;
					columnCount = 0;
				}

				tempArray.push(sqr);


			}

			//if you set RANDOM_SEQUENCE then it will shuffle the images
			if(RANDOM_SEQUENCE){

				squareArray = shuffle(tempArray);

			} else {

				squareArray = tempArray;
			}

			//if you want the transitions to start form the end
			if(REVERSE_ARRAY){

				squareArray.reverse();
			}

			pauseTransition();


		}


		//this is a transition type - play around with the tween1 and tween2 settings!
		function cityLightsTransition(){
		

			TweenMax.set(squareArray, {

				transformOrigin:'50% 50%'
			})

			var tl = new TimelineMax();
			//tween in
			var tween1 = TweenMax.staggerTo(squareArray, TRANSITION_IN_DURATION, {
				scale:0.3,		
				borderRadius:'50%',
				ease:TRANSITION_EASE_IN

			}, TRANSITION_STAGGER);

			//tween out
			var tween2 = TweenMax.staggerTo(squareArray, TRANSITION_IN_DURATION, {

				scale:2,		
				alpha:0,
				ease:TRANSITION_EASE_IN,
				onComplete:swapSquares,
				onCompleteParams:['{self}']

			}, TRANSITION_STAGGER, pauseTransition);

			tl.add(tween1, 0);
			tl.add(tween2, 1);

			currentImage++;

			if(currentImage >= IMAGES_ARRAY.length){
				currentImage = 0
			}

		}

		//this is a transition type - play around with the tween1 and tween2 settings!
		function rushTransition(){

			TweenMax.set(squareArray, {

				transformOrigin:'50% 100% 0px'
			})

			var tl = new TimelineMax();

			//tween in
			var tween1 = TweenMax.staggerTo(squareArray, TRANSITION_IN_DURATION, {

				scale:0.3,		
				borderRadius:'75%',

				backgroundPosition:-(Math.random() * stageWidth) +'px ' + (Math.random() * stageHeight) + 'px',
				ease:TRANSITION_EASE_IN

			}, TRANSITION_STAGGER);

			//tween out
			var tween2 = TweenMax.staggerTo(squareArray, TRANSITION_IN_DURATION, {

				alpha:0,
				ease:TRANSITION_EASE_IN,
				onComplete:swapSquares,
				onCompleteParams:['{self}']

			}, TRANSITION_STAGGER, pauseTransition);

			tl.add(tween1, 0);
			tl.add(tween2, 0.3);

			currentImage++;

			if(currentImage >= IMAGES_ARRAY.length){
				currentImage = 0
			}

		}	

		//this is a transition type - play around with the tween1 and tween2 settings!
		function simpleTransition(){

			TweenMax.set(squareArray, {

				transformOrigin:'50% 50% 0px'
			})

			var tl = new TimelineMax();

			//tween in
			var tween1 = TweenMax.staggerTo(squareArray, TRANSITION_IN_DURATION, {

				scale:0,		
				borderRadius:'50%',
				ease:TRANSITION_EASE_IN

			}, TRANSITION_STAGGER);

			//tween out
			var tween2 = TweenMax.staggerTo(squareArray, TRANSITION_IN_DURATION, {

				alpha:0,
				ease:TRANSITION_EASE_IN,
				onComplete:swapSquares,
				onCompleteParams:['{self}']

			}, TRANSITION_STAGGER, pauseTransition);

			tl.add(tween1, 0);
			tl.add(tween2, 0.1);

			currentImage++;

			if(currentImage >= IMAGES_ARRAY.length){
				currentImage = 0
			}

		}

		function pauseTransition(){

			TweenMax.delayedCall(TRANSITION_DELAY, TRANSITION_TYPE_ARRAY[currentImage]);
		}

		//called by the transitions when it swaps out the transitionTo image
		function swapSquares(tween){

			var target = tween.target;

			//console.log(target.data().x);

			TweenMax.set(target, {
				backgroundImage:'url(' + IMAGE_PATH + IMAGES_ARRAY[currentImage] + ')'				
			})

			TweenMax.to(target, TRANSITION_OUT_DURATION, {
				borderRadius:'0%',
				x:target.data().posX,	
				y:target.data().posY,
				z:0.01,
				scaleX:1,
				scaleY:1,
				width:SQUARE_SIZE,
				height:SQUARE_SIZE,
				borderRadius:'0%',
				rotationX:'0_cw',
				rotationY:'0_ccw',
				rotation:'0_ccw',
				skewX:0,
				skewY:0,
				alpha:1,
				rotationY:0,
				//border:'0px dotted transparent',
				//backgroundSize:'2000% 1000%',


				backgroundPosition:target.data().backgroundPosition,
				ease:TRANSITION_EASE_OUT
			})


		}


		//array shuffler
		function shuffle(o){ 
		    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
		    return o;
		};


		//start the transitions!
		init();
		


      });
      //Edge binding end

      

      Symbol.bindSymbolAction(compId, symbolName, "creationComplete", function(sym, e) {
         var meta1 = "<meta content=\"minimum-scale=1, width=device-width, maximum-scale=1\, user-scalable=no\" name=\"viewport\" />" ;   
         $(meta1).appendTo("body");

      });
      //Edge binding end

      

   })("stage");
   //Edge symbol end:'stage'

})(jQuery, AdobeEdge, "dico-intro");