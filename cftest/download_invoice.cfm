<!--- added so that we can scope all form and url vars to attribute site wide --->
<cfscript>
if (NOT IsDefined("attributes"))
    attributes=structNew();
StructAppend(attributes, url, "no");
StructAppend(attributes, form, "no");
</cfscript>

<cfparam name="attributes.con_id" default="9">

<cfquery name="selectRegistrationInformation" datasource="roseocon01">
	SELECT (select end_time from slots where slot_number = (s.slot_number - 1 + g.slot_length) and slots.con_id = #attributes.con_id# and slots.track_id = t.track_id) as slot_end,g.name, g.slot_length, s.*, c.character_name, g.game_code, r.event_id, p.*
    FROM game g
        INNER JOIN event_schedule e ON g.game_id = e.game_id
        INNER JOIN slots s ON e.slot_id = s.slot_id
		INNER JOIN track t on s.track_id = t.track_id
        INNER JOIN player_reg r ON e.event_id = r.event_id
		INNER JOIN players p ON r.player_id = p.player_id
        INNER JOIN characters c ON r.character_id = c.character_id
		AND e.con_id =#attributes.con_id#
		ORDER BY p.last_name,p.player_id,s.slot_number
</cfquery>
<cfquery name="selectConvention" datasource="roseocon01">
	SELECT	* FROM convention where con_id = #attributes.con_id#
</cfquery>
<!--- 
<cfdump var="#selectRegistrationInformation#"> --->

<cfdocument 
   format = "PDF">
<cfoutput query="selectRegistrationInformation" group="player_id"> 
<div style="margin: 10pt;">

<div align="center" class="Print" style="font-size:14pt"><strong>
#selectConvention.con_name# Registration for #first_name# #last_name#
<cfif len(rpga_number) gt 0>
RPGA## #rpga_number#
</cfif>
</strong></div>
<div align="center">
<table border="1" cellspacing="0" cellpadding="2" bordercolor="##000000">
<tr>
	<td class="print"><strong>Slot</strong></td>
	<td class="print"><strong>Game</strong></td>
	<td class="print" align="right"><strong>Character</strong></td>
</tr>

<cfoutput>
<tr>
	<td class="print">#dateformat(slot_date,"mm/dd/yyyy")# #timeformat(start_time,"h:MM TT")#-#timeformat(slot_end,"h:MM TT")#</td>
	<td class="print">#game_code# #name#</td>
	<td class="print" align="right">#character_name#</td>
	
</tr>
</cfoutput>
<tr><td colspan="3" align="right">
<table border="0" cellspacing="1" cellpadding="1">
<tr>
	<td class="Print" colspan=2 align="Left">
		<p><strong>Cost:</strong> <br>
		<p>3 Day Weekend Badge: Register at CodCon</p>


<p>RPG Event fees: $5 per event (walk ins pay $6, please register)</p>


 <p>Preregistration is over April 3, 2017.</p>
		<!--
		 Preregistration Fees:
  <p>3 Day Weekend Badge: Check at <a href="http://draxtargames.com/polar-vortex-convention/">http://draxtargames.com/polar-vortex-convention/</a></p>
<p>Pre-registration Prices: $5 per slot played. Preregistered space is given priority for a seat at an event. Walk-Ins: $6 per slot and will be seated at open tables.</p>
 
<p>Roleplaying Game Judging Benefits</p>

<p>Prizes - chosen at random but every GM typically ends up with something.</p>

<p>You will recieve a free drink/snack for every slot your GM!</p>

<p>Free con badge if you judge one slot then it is $5 off for each additional slot judged. Judge 3 and play 3 for free! Similar to how CODCON is run.</p> -->
	</td>
</tr>
</table>
</div>
</td></tr>
</table></div>
</div><cfdocumentitem type = "pagebreak"/>
</cfoutput>
</cfdocument>