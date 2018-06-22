
<!-- content begins -->

<!-- master and detail ajax starts here-->
   
<div class="table-responsive">
<table class="lightbluetone table-responsive" width="100%" border="1">
	
		<tr>
			<td width="100%" colspan="5" class="lighterbluetone"><strong>Search Purchase Request By: </strong></td>
		</tr>
		<tr>
			
			<td width="5%" class="lighterbluetone"></td>
			<td width="95%" class="lighterbluetone">
				<select name="searchby" id="searchby" class="selectsearchby">
					<option value ="PODate">PO Date</option>	
					<option value ="DueDate">Due Date</option>
					<option value ="Requested By">Requested By</option>
					<option value ="PlayerName">Amount</option>
				</select>
				
				<select name="operator1" id="operator1" class="tdoperator">
					<option value ="like"> like</option>
					<option value ="="> =</option>
					<option value =">="> >=</option>
					<option value ="<="> <=</option>
					<option value ="<"> <</option>
				</select>
				 		 
				<input  type="text" id ="searchkey" name="searchkey" tabindex="1" autofocus 
				onclick="" 
				onload="document.getElementById('searchinvoicebtn').click()" 
				onkeydown="if (event.keyCode == 13) document.getElementById('searchinvoicebtn').click()"
				class="searchkey" 
				value=""/>
				<input id="searchinvoicebtn" name="searchinvoicebtn"
				type="image" src="img/view_item.png" 
				width="25px" height="25px" align="center" 
				title="Search <?php echo $_REQUEST['process'] ?>"
				onclick=" "/>
				
			</td>
			
			
				
		</tr>	
		<tr>
			<td class="lighterbluetone normalright">
				<select name="operand" id="operand" class="tdoperator">
					<option value ="And"> And</option>
					<option value ="Or"> Or</option>
				</select>
			</td>
			<td class="lighterbluetone">
				<select name="searchby2" id="searchby2" class="selectsearchby">
					<option value ="PODate">PO Date</option>	
					<option value ="DueDate">Due Date</option>
					<option value ="Requested By">Requested By</option>
					<option value ="PlayerName">Amount</option>>
				</select>
				
				<select name="operator2" id="operator2" class="tdoperator">
					<option value ="like"> like</option>
					<option value ="="> =</option>
					<option value =">="> >=</option>
					<option value ="<="> <=</option>
					<option value ="<"> <</option>
				</select>
				 		 
				<input  type="text" id ="searchkey2" name="searchkey2" class="searchkey"  value=""
				/>
			</td>
				
			
		</tr>	
		<tr>
			<td class="lighterbluetone normalright">
				<select name="operand2" id="operand2" class="tdoperator">
					<option value ="And"> And</option>
					<option value ="Or"> Or</option>
				</select>
			</td>
			<td class="lighterbluetone">
				<select name="searchby3" id="searchby3" class="selectsearchby">	
					<option value ="PODate">PO Date</option>	
					<option value ="DueDate">Due Date</option>
					<option value ="Requested By">Requested By</option>
					<option value ="PlayerName">Amount</option>
				</select>
				<select name="operator3" id="operator3" class="tdoperator">
					<option value ="like"> like</option>
					<option value ="="> =</option>
					<option value =">="> >=</option>
					<option value ="<="> <=</option>
					<option value ="<"> <</option>
				</select>
				
				<input  id="searchkey3" name="searchkey3" type="text" class="searchkey"  />
			</td>
			
			
			
			<input type="hidden" id="orderby1" name="orderby1" 
			value="Duedate,PODate"/>
			<input type="hidden" id="sortby1" name="sortby1" 
			value="DESC"/> 	
				
			
			
		</tr>
	
	
</table>	
</div>
	
<div id="txtMainRoute"></div>
<ul class="list" id="listview">
</ul>
