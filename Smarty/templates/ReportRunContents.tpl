<table align="center" border="0" cellpadding="5" cellspacing="0" width="95%">
<tr>
<td>
	{$GRAPH}
</td>
</tr>
</table>
<table align="center" border="0" cellpadding="5" cellspacing="0" width="95%">
	<tbody><tr>
	<td align="left" width="10%"><input class="classBtn" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTPDF_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreatePDF',{$REPORTID}));" title="{$MOD.LBL_EXPORTPDF_BUTTON}"></td>
	<td align="left" width="20%"><input class="classBtn" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTXL_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreateXL',{$REPORTID}));" title="{$MOD.LBL_EXPORTXL_BUTTON}" ></td>
	<td align="left" width="70%"><input name="print" value=" Print Report " class="classBtn" type="button"></td>
	</tr>
	</tbody>
</table>
<br>
<table style="border: 1px solid rgb(0, 0, 0);" align="center" cellpadding="0" cellspacing="0" width="95%">
	<tbody><tr>
	<td style="background-repeat: repeat-y;" background="{$IMAGE_PATH}report_btn.gif" width="16"></td>

	<td style="padding: 5px;" valign="top">
	<table cellpadding="0" cellspacing="0" width="100%">
		<tbody><tr>
		<td align="left" width="75%">
		<span class="genHeaderGray">{$REPORTNAME}</span><br>
		</td>
		<td align="right" width="25%">
		<span class="genHeaderGray">Total : {$REPORTHTML.1}  Records</span>
		</td>
		</tr>
		<tr><td id="report_info" align="left" colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr>
		<td colspan="2">
		{$REPORTHTML.0}
		</td>
		</tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		<tr><td colspan="2">{$REPORTTOTHTML}</td></tr>
		<tr><td colspan="2">&nbsp;</td></tr>
		</tbody>
	</table>
	</td>
	<td style="background-repeat: repeat-y;" background="{$IMAGE_PATH}report_btn.gif" width="16"></td>
	</tr>

	</tbody>
</table>
<br>
<table align="center" border="0" cellpadding="5" cellspacing="0" width="95%">
	<tbody><tr>
	<td align="left" width="10%"><input class="classBtn" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTPDF_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreatePDF',{$REPORTID}));" title="{$MOD.LBL_EXPORTPDF_BUTTON}"></td>
	<td align="left" width="20%"><input class="classBtn" id="btnExport" name="btnExport" value="{$MOD.LBL_EXPORTXL_BUTTON}" type="button" onClick="goToURL(CrearEnlace('CreateXL',{$REPORTID}));" title="{$MOD.LBL_EXPORTXL_BUTTON}" ></td>
	<td align="left" width="70%"><input name="print" value=" Print Report " class="classBtn" type="button"></td>
	</tr>
	</tbody>
</table>

