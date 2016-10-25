//������������� ����������
var mapApp;
var waveText;
var SVGDoc;
var scaleValue=1;		// ������� ���������� ��-���������	
var	positionMaxValue;	// ��� �������� ��-���������
var	positionMinValue;
var positionStep=500;			
			
function init(LoadEvent) {
	top.SVGcontentMove	= contentMove;
	top.SVGdocumentScale	= documentScale;				
	SVGDoc = LoadEvent.target.ownerDocument;			
	myMapApp = new mapApp(false,undefined);
	waveText = document.getElementById("waveText");	
	hideCoords();
}
		
//������� ��������������� �����			
function linesScale(id,scale){
	var lineElement = document.getElementById(""+id+"");
			
	var child = lineElement.firstChild;
	
	while (child != null) {
		if (child.nodeName == "path") {
			child.setAttribute("stroke-width", 0.4/scale);
		}
		child = child.nextSibling;
	} 			
}
		
//������� ��������������� �������		
function rulerScale(scale){
	var rulerRect = document.getElementById("rulerRect");
	rulerRect.setAttribute("height", 11/scale);			
	var rulerLayer = document.getElementById("rulerLayer");
	var child = rulerLayer.firstChild;
	
	if (scale<3) positionStep=500;
	if (scale>=3 && scaleValue<7) positionStep=250;
	if (scaleValue>=5 && scaleValue<11) positionStep=100;
	if (scaleValue>=11 && scaleValue<25) positionStep=50;
	if (scaleValue>=25 && scaleValue<40) positionStep=25;
	if (scaleValue>=40 && scaleValue<50) positionStep=10;
	if (scaleValue>=60 && scaleValue<100) positionStep=5;

	// ������������ �������� ���� 
	positionX =	positionMinValue/4;
	positionMaxValue=((positionX+960/scaleValue)/2.5)*10;
	
	// �������������� ����� �������			
	drawRulerText(positionMinValue,positionMaxValue,positionStep);
}

//������� ��������� ������ �� �������		
function drawRulerText(MinValue,MaxValue,step){
	var svgns = "http://www.w3.org/2000/svg";		
	var rulerText = document.getElementById("rulerText");			//�������� ��������� ����
	var rulerLayer = document.getElementById("rulerLayer");			//�������� ���� �������
	
	rulerText.parentNode.removeChild(rulerText);					//������� ������������ �������
	var newRulerText = SVGDoc.createElementNS(svgns, "g");			//������ ��������� ���� ������
	newRulerText.setAttributeNS(null, "id", "rulerText");			
	newRulerText.setAttributeNS(null, "font-size", 10/scaleValue);
	newRulerText.setAttributeNS(null, "font-family", "Arial");
		
	rulerLayer.appendChild(newRulerText);							// ��������� ��������� ���� �� ���� �������
	var textX =0;
	
	for (var i=MinValue;i<MaxValue;i+=step) {						//��������� ��������� ������ � �����			
		var data = SVGDoc.createTextNode(i.toFixed());				//�������� = �������� ����� ����� ������������ ���
		var text = SVGDoc.createElementNS(svgns, "text");
			
		strLength=""+i.toFixed();
		//(wavelength.length*3))/scaleValue
		text.setAttributeNS(null, "x", (textX/4)-(strLength.length*3/scaleValue));		//����������� ���������� � ������ 
		text.setAttributeNS(null, "y", 9/scaleValue);									//����������� ���������� � ������ 
			
		text.appendChild(data);			
		document.getElementById("rulerText").appendChild(text);							// ��������� ��������� �������
		textX+=step;
	}		
} 
	
// ������� ��������������� svg		
function documentScale(scale) {	
	var width=SVGDoc.documentElement.getAttribute("width")/scale;						//����������� �������� ��������
	var height=SVGDoc.documentElement.getAttribute("height")/scale;	
	scaleValue = scale;
			
	width=960/scale;	
	height=100/scale;	
	SVGDoc.documentElement.setAttribute("viewBox", "0 0 "+width+" "+height+"");			//������������� �������� viewbox

	linesScale("lines",scale);															//������������ �����
	rulerScale(scale);																	//������������ �������	
}		
		
// ������� ����������� svg		
function contentMove(x) {
	var content = document.getElementById("content1");					
	positionMinValue=(x/2.5)*10;
	positionMaxValue=((x+960/scaleValue)/2.5)*10;						//����������� �������� ��������
	x=-x;
	content.setAttributeNS(null,"transform","translate("+x+")");		// ������� svg
	drawRulerText(positionMinValue,positionMaxValue,positionStep);		// �������������� ����� �������			
}
		
// ������� ����������� ���������		
function showCoords(evt) {					
	var coords = myMapApp.calcCoord(evt);
	//var wavelength = ""+coords.x.toFixed(1)*10;						
	//var wavelength = ""+((coords.x*10).toFixed());					//����������� �������� ���������� � �� svg
	var wavelength = ""+((coords.x*10).toFixed(1));						//����������� �������� ���������� � �� svg � ����� ������ ����� �������
	var waveTextRect = document.getElementById("waveTextRect");			//�������� �������� ����������� ���������
				
	waveTextRect.setAttributeNS(null, "style", "display: visible;");											//������ ���� ������� �������
	waveTextRect.setAttributeNS(null, "x", Math.round(evt.clientX-((wavelength.length-1)*3)-5)/scaleValue);		//������������� �������� x �������� ���, ����� ��� ���� ���������� ��� ������							
	waveTextRect.setAttributeNS(null, "width", (wavelength.length*6+5)/scaleValue);								//������������� ������ �������� � ����������� �� ���������� ���� � ����� � ��������
	waveTextRect.setAttributeNS(null, "height", 11/scaleValue);													//������������� ������ � ����������� �� ��������
	
	waveText.setAttribute("font-size", 10/scaleValue);				//������������� ������ ������ � ����������� �� ��������
	waveText.setAttribute("y", 9/scaleValue);						//������������� ������ ������ � ����������� �� ��������
	waveText.setAttributeNS(null,"x",Math.round(evt.clientX-(wavelength.length*3))/scaleValue);	//������������� ������ �������� � ����������� �� ���������� ���� � ����� � ��������
	waveText.firstChild.nodeValue = wavelength;						//����� �������
				
}

// ������� ������ ����������			
function hideCoords() {
	var waveTextRect = document.getElementById("waveTextRect");		//�������� �������� ����������� ���������
	waveTextRect.setAttributeNS(null, "style", "display: none;");   //������ ���� ������� ���������             
	waveText.firstChild.nodeValue = "";								//������� �����
	}