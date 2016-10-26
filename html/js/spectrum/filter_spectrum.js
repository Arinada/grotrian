					$(document).ready(function() {  						
						sliderMax = 18750; //{#*������������ �������� ��������*#}
						sliderMin = 0;  //{#*����������� �������� ��������*#}		

							
					
						//{#*������� ������ �� ���������� ���������*#}
						$("#positionSlider").slider({ 
							value:948.0,			//{#*�������� �������� = �������� ��������*#}
							min: sliderMin,			//{#*���������� �������� �������� = ��� �������� ��������� �������*#}
							max: sliderMax,			//{#*����������� �������� �������� = (���� �������� ��������� �������/10)*2.5 (����������� ���������������) *#}	
							step: 0.1,				//{#*��� ��������*#}	
						
							slide: function( event, ui ) {			
								var scale=$( "#scaleSlider" ).slider( "value" ); 	//{#*��������� �������� ���������� ��������*#}	
								window.SVGcontentMove(ui.value);					//{#*������� � ������ svg �������� ���������� �� ���. ���-�� �������*#}	
						
								$("#positionMinValue").text(Math.round((ui.value/2.5)*10)); 				//{#*������������� �������� ���*#}		
								$("#positionMaxValue").text(Math.round(((ui.value + 960/scale)/2.5)*10)); 	//{#*������������� �������� ���� (960 - ������ ������� ����� �����)*#}	
							}
						});
					
						//{#*������� Drag ���������*#}						
						$("#bgRect").mousedown(function(){
						      $(this).append('<span style="color:#00F;">Mouse down.</span>');
						    });	
						
						//{#*������� ������ �� ���������� ���������������*#}
						
						$("#scaleSlider").slider({
							orientation: "vertical", 
							min: 1,					//{#*���������� �������� �������� *#}
							max: 100,				//{#*����������� �������� �������� *#}
							step: 1,				//{#*��� ��������*#}	
						
							slide: function( event, ui ) {
								var position=$( "#positionSlider" ).slider( "value" );	//{#*��������� �������� ��������*#}	
								window.SVGdocumentScale(ui.value);						//{#*������� � ������ svg �������� ��������� �� ���. ���-�� �������*#}	
								
								$("#scaleValue").text(ui.value);												//{#*������������� �������� ���������������*#}	
								$("#positionMaxValue").text(Math.round(((position + 960/ui.value)/2.5)*10));	//{#*������������� �������� ����*#}
							}
						});
					
					
						//{#*��������� ��� � ���� �� ���������*#}
						$("#positionMinValue").text(Math.round(($( "#positionSlider" ).slider( "value" )/2.5)*10) );
						$("#positionMaxValue").text(Math.round((($( "#positionSlider" ).slider( "value" ) + 960)/2.5)*10));
						
						waveMin=(sliderMin/2.5)*10;
						waveMax=(sliderMax/2.5)*10+(960/2.5)*10;
						
						$("#filterBtn").click(function(){
							var scale=$( "#scaleSlider" ).slider( "value" );//��������� �������� ���������� ��������
							
							waveMinVal = document.inputform.waveMinVal.value;
							waveMaxVal = document.inputform.waveMaxVal.value;															
							
							if ((waveMinVal=="") || (waveMinVal<waveMin)) waveMinVal=waveMin;
							if ((waveMaxVal=="") || (waveMaxVal>waveMax)) waveMaxVal=waveMax;
							

							if ( ((waveMaxVal/10)*2.5)-((waveMinVal/10)*2.5) > 960/scale ){
								
							if(!isNaN(waveMaxVal)) {															
								var maxVal = (waveMaxVal/10)*2.5;												//��������� �� ���������� � ���������� svg
								var sliderVal = $( "#positionSlider" ).slider( "value" );						//���� ���������� ������� �������� = ������� ������� ��������

								maxVal=maxVal-960/scale;														//����������� ���������� �������� svg
	
								if (sliderVal > maxVal){														//���� ������� ����� ������ ������������� ��������									
									window.SVGcontentMove(maxVal,0);											//������� � ������ svg �������� ���������� �� ���. ���-�� �������							
									$("#positionMinValue").text(Math.round((maxVal/2.5)*10)); 					//������������� �������� ���
									$("#positionMaxValue").text(Math.round(((maxVal + 960/scale)/2.5)*10)); 	//������������� �������� ���� (960 - ������ ������� ����� �����)	
									sliderVal=maxVal;															//���������� ������� �������� �����������  ������������ �������� �� �����
								}
								$("#positionSlider").slider({max:maxVal,value:sliderVal});						//������������� ������� ��������
								
							}
							else waveMaxVal="getMaxWave";
							
							
							if(!isNaN(waveMinVal)) {								 								
								var minVal = (waveMinVal/10)*2.5;												//��������� �� ���������� � ���������� svg
								var sliderVal = $( "#positionSlider" ).slider( "value" );						//���� ���������� ������� �������� = ������� ������� ��������

								
	
								if (sliderVal < minVal){														//���� ������� ����� ����� ������������ ��������									
									window.SVGcontentMove(maxVal,0);											//������� � ������ svg �������� ���������� �� ���. ���-�� �������							
									$("#positionMinValue").text(Math.round((minVal/2.5)*10)); 					//������������� �������� ���
									$("#positionMaxValue").text(Math.round(((minVal + 960/scale)/2.5)*10)); 	//������������� �������� ���� (960 - ������ ������� ����� �����)	
									sliderVal=minVal;															//���������� ������� �������� �����������  ������������ �������� �� �����
								}
								$("#positionSlider").slider({min:minVal,value:sliderVal});						//������������� ������� ��������
								
							}
							else waveMinVal="getMinWave";
							}
							
							
							/*
							$.post('a_spectr.php',{element_id:"2511", maxlength:waveMaxVal, minlength:waveMinVal }, function(data) {
								//$('#svg').html(data);
								$('#spectrum_container').html(data);
								//$('#spectrum').html(data);
							},"string");*/
						
						});

						$("#showAllBtn").click(function(){
							$("#positionSlider").slider({min:sliderMin,max:sliderMax});		
							$("#positionMinValue").text(sliderMin);
							$("#positionMaxValue").text(sliderMax);
						});					

						
					});