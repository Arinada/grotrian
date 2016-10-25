function  spectrum(spectr){
	
	var maxAvalibleLength=MaxLengthFrom(spectr,0);
	var MinLength=0;
	var MaxLength=30000;
	var ScaleValue=1;	
	var Center=4800;
	var initialCenter = Center;	
	var maxX;
	var leftLim;
	var rightLim;
	var mainslider;	
	

	
	$('#spectrum_holder').svg({onLoad: drawInitial});	//������ ������ � �������������� ���������
				
	$("#Btn").click(function(){
		// �������� �������� �����
		ScaleValue = $("#scaleValue").val();
		MinLength = $("#minLength").val();		
		MaxLength = Number($("#maxLength").val());
		Center = $("#centerValue").val();

		drawspectralines(spectr,MinLength,MaxLength,Center,ScaleValue);// ������ �����	
	});

		//������� ���������� � ��������
	$("#spectrum_holder").mousewheel(function(objEvent, delta){

		ScaleValue=Number(ScaleValue)+Number(delta);							//���������� � �������� ����������, �������� ��������� ������� 
		
		if (ScaleValue>=1) {
			$("#scaleSlider").slider({value:ScaleValue});							// ���������� ������� ����������

			drawspectralines(spectr,MinLength,MaxLength,Center,ScaleValue);			// ������ ����������� ������
			UpdateFormValues();														// ��������� ������ �����
		} else ScaleValue=1;
				
	},true); 

	// ���������� ������� �������������� �����
	$("#spectrum_holder").drag();
	
	// ������� ��� �������������� �����
	$("#spectrum_holder").ondrag(function(e, element){ 
		$("#spectrum_holder").css("cursor", "move");					// ������ ���� move

		var delta = $.updatePosition(e);					// �������� �������� �������� ���������� ������� ���� ������������ ��������� ���������� �������
	    Center = initialCenter - delta.x*10/ScaleValue;   	// ��������� ����� �������� ����� ������ ��������
	    UpdateFormValues();									// ��������� ������ �����
	});

	$("#spectrum_holder").ondrop(function(e, element){
		$("#spectrum_holder").css("cursor", "default");		// ������ �������� ����
		initialCenter = Center;								// ��������� ����� ��������������� � �������� ���������� ������ 


		drawspectralines(spectr,MinLength,MaxLength,Center,ScaleValue);		// ������ �����	
		UpdateFormValues();													// ��������� ������ �����

    });

	
	var initialDelta=0;
	var initialSecondaryDelta=0;
	var deltax=0;
	var initialPosition=-480;//$('#spectrum_holder').position().left;
	
	//{#*������� ������ �� ��������� �������� ���������*#}

	$("#positionSlider").slider({ 
		step: 1,				//{#*��� ��������*#}	
			
		slide: function( event, ui ) {
		
			deltax = initialDelta-ui.value;								// ����������� �������� ��������
			Center =initialCenter-deltax*10/ScaleValue;					//	����������� �������� ������	
			$("#spectrum_holder").css("left", initialPosition+deltax);	// ������� div
			UpdateFormValues();											// ��������� ������ �����	
		},

		stop: function(event, ui) {
			initialCenter = Center;											// ������������� ��������� �����	
			drawspectralines(spectr,MinLength,MaxLength,Center,ScaleValue);	// ������ �����			
			UpdateFormValues();												// ��������� ������ �����
		}
			
	}); 

	//{#*������� ������ �� ��������� �������������� ���������*#}
	$("#secondaryPositionSlider").slider({ 
		step: 1,												//{#*��� ��������*#}	
	
		slide: function( event, ui ) {

			deltax = initialSecondaryDelta-ui.value;				//������ ��������� �������� ��������

			var move = initialPosition+deltax;						// ����������� �������� ��������
		
			if (move<leftLim && move>-rightLim){					//���� �������� �������� ������ ����������� ������� � ����� ������������ 
				Center =initialCenter-deltax*10/ScaleValue;			//����������� ����� 
				$("#spectrum_holder").css("left", move);						// ���������� ��������
				mainslider = initialDelta+ui.value;					// ����������� �������� ��������� ��������
				$("#positionSlider").slider({value:mainslider});	// ���������� �������� �������
				UpdateFormValues();									// ��������� �����
			}			
		},

		stop: function(event, ui) {
			initialCenter=Center;										// ������������� ��������� �����
			initialPosition =$('#spectrum_holder').position().left;		// ������������� ��������� �������
			initialSecondaryDelta=ui.value;								// ������������� ��������� �������� ��������
			initialDelta = mainslider;									// ������������� ��������� �������� ��������� ��������
		}
			
	}); 

	// ������� ���������� ��������
	$("#scaleSlider").slider({
		//orientation: "vertical",
		value: 1, 
		min: 1,															//{#*���������� �������� �������� *#}
		max: 500,														//{#*����������� �������� �������� *#}
		step: 1,														//{#*��� ��������*#}	
	
		slide: function( event, ui ) {
			$("#scaleValue").val(ui.value);								// ���������� ��������� ��������	
			//$("#ScaleValue").val(ui.value);
		},
		
		stop:function( event, ui ) {
			ScaleValue=ui.value;												// ������������� �������� ��������
			initialCenter = Center;												// ������������� ��������� �����
			drawspectralines(spectr,MinLength,MaxLength,Center,ScaleValue);		// ������ �����	
			UpdateFormValues();													// ��������� �����
		}
	
	});

	// ������� ���������� �����
	function UpdateFormValues(){

	var	Max= (Center + 4800/ScaleValue).toFixed();
	var	Min= (Center - 4800/ScaleValue).toFixed();
	var fixedCenter = Center.toFixed();
	
		$("#centerValue").val(fixedCenter);
		$("#centerRulerValue").text(fixedCenter);
		$("#positionMinValue").text(Min);
		$("#positionMaxValue").text(Max);
		$("#scaleValue").val(ScaleValue);
	}


	// ������� ��������� �����

	function drawspectralines(spectr,MinLength,MaxLength,Center,ScaleValue){
		
		if (MaxLength<maxAvalibleLength) maxAvalibleLength = MaxLength;
		
		maxX=(maxAvalibleLength/10*ScaleValue).toFixed();								// ��������� ���������� ������������ ����� �����

		WidthX=Number(maxX)+960;														// ��������� ������ div � svg

		leftLim =(Number(initialCenter))/10*ScaleValue-480;								//��������� ����� �������
		rightLim=(Number(maxAvalibleLength)-Number(initialCenter))/10*ScaleValue+480;	//��������� ������ �������
		
		$("#spectrum_holder").dragLimits(leftLim,rightLim);								// ������������� ������� ���������		
			
		var Xcenter=480+(Center/10)*ScaleValue;											// ���������� ������ svg
			
		
		var svg = $('#spectrum_holder').svg('get');										//�������� svg ������
		//svg.style('#test { fill: blue }');
				
		svg.configure({width:WidthX,height:100}, true);									// ������������� ������ svg
		
		if (g = svg.getElementById("lines_group")) svg.remove(g);						//������� ������ ���� ����� ���� ��� ����������

		var g = svg.group(null,"lines_group",{'stroke-width': 1, 'shape-rendering':"optimizeSpeed" });		// ������ ������ �����

		svg.rect(g,0, 0, (WidthX), 100,{fill: '#000'});														// ������ �������� ��� �����

		for (var key in  spectr){
			if (key>MinLength && key<MaxLength){			// ���� ����� ����� ��������� � ��������� �� ��������� � ���������� svg � ������			

				if (key<Center) x= Xcenter - ((Center - key)/10*ScaleValue);	// ����������� ���������� ������������ ������ svg
				if (key>Center) x= Xcenter + ((key - Center)/10*ScaleValue);
				svg.line(g, x, 0, x, 100,{stroke: ""+spectr[key]+"",class_: 'spectrline',length:key});	//������ �����
			}
		}	

		$('.spectrline', svg.root).bind('mouseover', lineOver).bind('mouseout', lineOut);	//����������� ������� mouseover/mouseout � ������ �����				
					
		var sliderValue=Number(Center/10*ScaleValue)-480;									// ��������� �������� �������� ���������
		$("#positionSlider").slider({min:-480,max:Number(maxX-480),value:sliderValue});		// ������������� �������� ��������� ��������
		initialDelta=sliderValue;															// ������������� ��������� �������� ��������� ��������
		$("#secondaryPositionSlider").slider({min:-480,max:480,value:0});					// ������������� �������� ��������������� �������� 0
		initialSecondaryDelta=0;															// ������������� ��������� �������� ��� �������� 0		
		
		$(g).attr({transform:"translate("+(480-(Number((Center/10)*ScaleValue)))+")"});		// ������� ������ �����
		$("#spectrum_holder").css("left", -480);											// ���������� div �� �����
		initialPosition =-480;																// ������������� ��������� ��������
	}

	// ������� ������ ���������
	function drawInitial(svg) {		
		drawspectralines(spectr,0,30000,4800,1);
		$("#positionMinValue").text(0);
		$("#positionMaxValue").text(9600);
		$("#centerRulerValue").text(4800);
	}			


	function MaxLengthFrom(ar,maxAvalibleLength) {
		for (var key in  ar) if (Number(key)>Number(maxAvalibleLength)) maxAvalibleLength = Number(key);		//����� ����� ������� �������� ����� �����
		return maxAvalibleLength;
		}
}

function lineOver() { 	
	    $(this).attr('stroke-width', '2'); 
	    $("#LengthValue").html($(this).attr('length'));
} 
	 
function lineOut() { 
	    $(this).attr('stroke-width', '1'); 
}