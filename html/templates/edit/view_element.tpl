 	   <div id="panel" >
    				<div class="container_12">        
						<div class="grid_12">
            				
            				
            				<form id="inputElementform" action="">
	                		<table class="search_form">
								<tbody>
									
                                    <tr>
										<td class="name">�������� ��������:</td>
										<td>
											<input type="hidden" id="element_id" name="element_id" value="{#$atom.ELEMENT_ID#}"/>
											<input type="hidden" id="atom_id" name="atom_id" value="{#$layout_element_id#}"/>
											<input type="hidden" name="action" value="saveElement"/>
				                        	<input  size="12" type="text" name="Name_ru" value="{#$atom.NAME_RU#}"/>				                        																							
										</td>
										<td>&nbsp;&nbsp;</td>
										<td class="name">������� ���������:</td>
										<td>
				                        	<input  size="12" type="text" name="Name_ru_alt" value="{#$atom.NAME_RU_ALT#}"/>																						
										</td>										
										<td colspan="4">&nbsp;</td>

									</tr>			

									 <tr>									
										<td class="name">Element name:</td>
										<td>
				                        	<input  size="12" type="text" name="Name_en" value="{#$atom.NAME_EN#}"/>																						
										</td>	                        			
										<td colspan="5">&nbsp;</td>
									</tr> 	
																		
									 <tr>									
										<td class="name">������������:</td>
										<td>
				                        	<input  size="1" type="text" name="Abbr" value="{#$atom.ABBR#}"/>																						
										</td>	                        			
      									<td colspan="5">&nbsp;</td>
									</tr> 
									<tr>									
										<td class="name">Z:</td>
										<td>
				                        	<input id="z"  size="1" type="text" name="Z" value="{#$atom.Z#}"/>																						
										</td>	                        			
										<td colspan="5">&nbsp;</td>
									</tr> 
									<tr>									
										<td class="name">������� �����:</td>
										<td>
				                        	<input  size="1" type="text" name="atom_Mass" value="{#$atom.ATOM_MASS#}"/>																						
										</td>	                        			
										<td colspan="5">&nbsp;</td>
									</tr> 
									<tr><td colspan="11">&nbsp;</td></tr>
									<tr>									
										<td class="name">������ ��������</td>
										<td>
				                        	<select size="1" name="tablePeriod">
				                        	{#section name=period start=1 loop=9#}    											
    											
    											{#if $atom.TABLEPERIOD==$smarty.section.period.index#}
    											<option selected value="{#$smarty.section.period.index#}">{#$smarty.section.period.index#}</option>
    											{#else#}
    											<option value="{#$smarty.section.period.index#}">{#$smarty.section.period.index#}</option>
    											{#/if#}
    											
											{#/section#}
											</select>																																	
										</td>
										<td>&nbsp;&nbsp;</td>
											                        			
										<td class="name">������ ��������</td>
										<td>
				                        	<select size="1" name="tableGroup">
				                        	{#section name=group start=1 loop=19#}    											
    											
    											{#if $atom.TABLEGROUP==$smarty.section.group.index#}
    											<option selected value="{#$smarty.section.group.index#}">{#$smarty.section.group.index#}</option>
    											{#else#}
    											<option value="{#$smarty.section.group.index#}">{#$smarty.section.group.index#}</option>
    											{#/if#}
    											
											{#/section#}
											</select>																																	
										</td>
										<td class="name">��� ��������</td>
										<td>
				                        	<select size="1" name="Type">
				                        	
				                        	
				                        	{#foreach from=$elemet_types key=type item=elemet_type #}			
    											
    											{#if $atom.ELEMENT_TYPE==$type#}
    											<option selected value="{#$type#}">{#$elemet_type.ru#}</option>
    											{#else#}
    											
    											<option value="{#$type#}">{#$elemet_type.ru#}</option>
    											{#/if#}
    											
											{#/foreach#}
											</select>																																	
										</td>
									</tr> 
									
									<tr><td colspan="11">&nbsp;</td></tr>
									<tr>
										<td colspan="6">
				                        	<input class="button white" id="saveElement" value="���������" type="button"/>
				                        	<!--  <input class="button white" id="deleteElement" value="�������" type="button"/>
				                        	<input class="button white" id="newElement" value="��������" type="button"/>-->																						
										</td>										
										<td >&nbsp;</td>

									</tr>
									
                  
								</tbody>
							</table>
							</form> 
	 					</div>	               
					</div>	        		
                    <div class="clear">  </div>          
				</div>
				 
<!--END of Panel-->
 	        
				<div class="slide">
					<a href="#" class="btn-slide"></a>
			    </div>

<!--End of Slide-->	 
        
	<div class="container_12">    
		<div class="grid_12 toolbar" id="main">   
     	
			<p>&nbsp;</p>
        	    <h3>���� {#$atom.NAME_RU_ALT#}</h3>
        	    
        	    <form id="inputAtomform" action="">
        	    	<input type="hidden" name="action" value="saveAtom"/>        	    	
        	    	������� ���������: <input id="atomIonization" size="1" maxlength="3" type="text" name="atomIonization" value="{#$atom.IONIZATION#}"/>
        	    	��������� ���������: <input maxlength="20" type="text" name="atomIonizationPotencial" value="{#$atom.IONIZATION_POTENCIAL#}"/>
        	    	
        	   		<input class="button white" id="saveAtom" value="���������" type="button"/>
					<input class="button white" id="deleteAtom" value="�������" type="button"/>

        	     	<p>&nbsp;</p>
        	     		<h4>�������� �� ������� �����</h4>				
					
						<textarea class="jquery_ckeditor" rows="40" cols="115" name="atomDescription_ru">{#$atom.CONTAINMENT_RUS#}</textarea>
						<br/>
					<input class="button white" id="saveAtom" value="���������" type="button"/>
					<p>&nbsp;</p>
					
						<h4>�������� �� ���������� �����</h4>		
						<textarea  class="jquery_ckeditor" rows="40" cols="115" name="atomDescription_en">{#$atom.CONTAINMENT_ENG#}</textarea>
						<br/>
						<input class="button white" id="saveAtom" value="���������" type="button"/>	
					<p>&nbsp;</p>
					</form>
				
				<form id="inputAtomDiagramForm" action="">
					<input type="hidden" id="element_id" name="element_id" value="{#$atom.ELEMENT_ID#}"/>
					<input type="hidden" id="atom_id" name="atom_id" value="{#$layout_element_id#}"/>
					<input type="hidden" name="action" value="makeDiagramm"/> 
        	     	<h4>�������</h4>				
						<textarea rows="6" cols="115" id="atomLimits">{#$atom.LIMITS#}</textarea>
        	     	<p>&nbsp;</p>
        	     	<h4>�������</h4>				
					<textarea rows="6" cols="115" id="atomBreaks">{#$atom.BREAKS#}</textarea>
					<p>&nbsp;</p>
					<input class="button white" id="makeDiagramm" value="��������� ���������" type="button"/>	
				</form>
								
				 
			
			
			{#if ($book_count != 0)#}
				<p>            
           			<h4>{#$l10n.Bibliographic_references#}</h4>
                		<ul>
                		{#foreach from=$book_list item=book#}
                    		<li>{#$book.NAME#}"</li>
                		{#/foreach#}
                		</ul>
        		</p>
			{#/if#}    
        
		<br/><br/>       

		</div>
	    <div class="clear"></div>
    </div>
<!--End of Main -->
	
	<div class="clear"></div>
		<div id="empty"></div> 
	</div>
<!--End of wrapper--> 
