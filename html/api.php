<?
	$xsams = isset($_GET['xsams']) ? $_GET['xsams'] : 0;
    $experimental = isset($_GET['experimental']) ? $_GET['experimental'] : 0;

    if ($xsams) {
		include 'includes/spectrum.php';
		$spectrum = new Spectrum();

		if (!$xsams = $spectrum->parse_file($xsams))
			exit('�������� ������ XSAMS �����');

		if ($experimental) {
			if (!$experimental = $spectrum->parse_file($experimental))
				exit('�������� ������ ������������������ �����');
		}
	}else
		exit('XSAMS ���� �� �������');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv='Content-Type' content='text/html; charset=Windows-1251'>
		<title>API</title>
		<link rel='stylesheet' href='css/spectrum2.css'>
	</head>
	<body>
		<div>
        	<div id='toolbar'>
            	<div id='range'>
                	<div id='min_container'>
                    	<b>�������</b><br>
                        <input type='text' id='min' value='0'>
                    </div>
                    <div id='max_container'>
                    	<b>��������</b><br>
                        <input type='text' id='max' value='30000'>
                    </div>
                </div>
                <div id='zoom_container'>
                	<b>�������</b><br>
                    <input type='button' value='1' class='base active'>
                   	<input type='button' value='10' class='base'>
                    <input type='button' value='100' class='base'>
                    <br><br>
                   	<input type='button' value='x2'>
                    <input type='button' value='x5'>
                </div>
                <input type='button' id='filter' value='���������'>                            
            </div>
        </div>
        <div id='line_info'></div>
        <div id="svg_wrapper"></div>
        <div id='map'>
        	<div id='preview'></div>
           	<div id='map_now'></div>
        </div>

        <script src='js/jquery-1.5.2.min.js'></script>
        <script src='js/spectrum2/init.js'></script>
        <script>
		$(document).ready(function() {
		    var xsams = <?=$xsams?>;
		    var experimental = <?=$experimental?>;

		    init_all();

		    $('#filter').live('click', function() {
		    	init_all();
			});


		    function init_all() {
		    	init(xsams);

		    	if (experimental)
		    		init(experimental, 2);	
			}
		});
        </script>
	</body>
</html>