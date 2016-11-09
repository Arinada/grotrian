  	<div class="container_12">
		<div class="grid_12" id="main">
			{#if ($transition_count != 0)#}
			<h4>{#$l10n.Load_file#}</h4>
			<form id='compare' method='POST' enctype='multipart/form-data'>
				<input type='file' name='file' id='file'>
				<select name='standard_file' id='standard_file'>
					<option value=0>---
					<option value=1>{#$l10n.Mercury_lamp_spectrum#}
				</select>
			</form>  
				  	<div>
            			<div id='toolbar'>
                			<div id='range'>
                    			<div id='min_container'>
                        			<b>{#$l10n.MinLength#}</b><br>
                        			<input type='text' id='min' value='0'>
                    			</div>
                    			<div id='max_container'>
                        			<b>{#$l10n.MaxLength#}</b><br>
                        			<input type='text' id='max' value='30000'>
                    			</div>
                			</div>
                			<div id='zoom_container'>
                    			<b>{#$l10n.Scale#}</b><br>
                    			<input type='button' value='1' class='base active'>
                    			<input type='button' value='10' class='base'>
                    			<input type='button' value='100' class='base'>
                    			<br><br>
                    			<input type='button' value='x2'>
                    			<input type='button' value='x5'>
                			</div>
                			<input type='button' id='filter' value='{#$l10n.Apply#}'><br><br>
							<input type='button' id='barchart' value='{#$l10n.BarChart#}'>
            			</div>
        			</div>
					<div style="margin: auto; margin-top: 10px; width: 520px;">
						<div id="info_intensity"><b>{#$l10n.Sensibility#}</b><!-- <input type="number" min=0 id="value"> <div id="value" style="display: inline-block;"></div> --></div><div id="range_intensity"></div>
					</div>
        			<div id='line_info'>
					</div>
        			<div id="svg_wrapper">
        			</div>
        			<div id='map'>
            			<div id='preview'></div>
            			<div id='map_now'></div>
        			</div>

		<br/><br/>       

		</div>
		{#else#}
			<div class="brake"></div>
			<div>
				{#$l10n.No_transitions#}
			</div>
	{#/if#}
		</div>
	    <div class="clear"></div>
    </div>
<!--End of Main -->
	
	<div class="clear"></div>
		<div id="empty"></div> 
	</div>
<!--End of wrapper--> 
