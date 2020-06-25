<?php
global $adb, $table_prefix;
$body = '<table bgcolor="#f7f7f8" border="0" cellpadding="0" cellspacing="0" width="100%">
	<tbody>
		<tr>
			<td>&nbsp;</td>
			<td width="600">
			<table bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="600">
				<tbody>
					<tr>
						<td>
						<table border="0" cellpadding="0" cellspacing="0" height="19" width="624">
							<tbody>
								<tr>
									<td bgcolor="#f7f7f8" height="10" width="20">&nbsp;</td>
									<td bgcolor="#f7f7f8" style="text-align: center;" width="560">&nbsp;</td>
									<td bgcolor="#f7f7f8" width="34">&nbsp;</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
							<tbody>
								<tr>
									<td bgcolor="#ffffff" colspan="3"><img src="http://www.vtecrm.com/newsletter/dilloamico1802/top.png" style="width: 621px; height: 330px;" /></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<table align="center" bgcolor="#ffffff" border="0" cellpadding="0" cellspacing="0" width="600">
							<tbody>
								<tr>
									<td>
									<p><br />
									<br />
									<span style="color:#444444;"><span style="font-size:16px;"><span style="font-family:verdana,geneva,sans-serif;"><strong>Try VTENEXT and discover the features of the BPM Engine</strong></span></span></span><br />
									&nbsp;</p>

									<p style="text-align: justify;"><span style="color:#333333;"><span style="font-size:14px;"><span style="font-family:verdana,geneva,sans-serif;"><strong>VTENEXT</strong> is the CRM Open Source Enterprise advanced solution with the software <strong>BPM</strong> functions integrated into the CRM.</span></span></span></p>

									<p style="text-align: justify;"><span style="color:#333333;"><span style="font-size:14px;"><span style="font-family:verdana,geneva,sans-serif;">In addition to the CRM operations you can implement your processes and ensure them in an easy way through the intuitive editor of our &quot;Process Manager&quot; module.</span></span></span></p>

									<p style="text-align: justify;"><span style="color:#333333;"><span style="font-size:14px;"><span style="font-family:verdana,geneva,sans-serif;">You have just to draw the process, set the conditions and the actions to the various tasks, test it and enjoy the result.<br />
									<br />
									Plan and implement all the necessary strategies for a successful customer management,from &nbsp;new clients acquisition to the customers loyalization, through an unique environment where you can manage marketing, sales and post sales businesses in a full digital way.</span></span></span></p>

									<p style="text-align: justify;"><span style="font-family:verdana,geneva,sans-serif;"><span style="font-size:14px;">You can try the free <a href="http://vtecrm.com/vtenext" target="_blank">Cloud Trial Version</a> or you can install the <a href="http://www.vtenext.org" target="_blank">Community Version</a>.</span></span></p>

									<p><span style="font-family:verdana,geneva,sans-serif;"><span style="font-size:14px;"><span style="color:#333333;">VTENEXT never leaves you alone! it is even available the APP version for iOS and ANDROID.</span></span></span></p>

									<p style="text-align: justify;"><span style="font-family:verdana,geneva,sans-serif;"><span style="font-size:14px;"><span style="color:#333333;">Try it and it will harder to turn back!</span></span></span><br />
									<br />
									<br />
									<a href="http://www.vtenext.com" target="_blank"><img alt="" src="http://www.vtecrm.com/newsletter/dilloamico1802/logo.png" style="width: 150px; height: 57px;" /></a></p>
									</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td>&nbsp;</td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
					<tr>
						<td>
						<hr /></td>
					</tr>
					<tr>
						<td><img height="1" src="http://www.vtecrm.com/newsletter/newsletter_2ore/images/placeholder.gif" width="20" /></td>
					</tr>
					<tr>
						<td align="center"><span style="font-size:12px;"><span style="font-family:verdana,geneva,sans-serif;">&nbsp;<strong>&copy; </strong><strong>VTENEXT</strong><br />
						Viale Fulvio Testi 223, 20162 Milano - (+39) 0237901352<br />
						<a href="http://vtecrm.com/" target="_blank">www.vtenext.com</a> - <a href="mailto:info@vtecrm.com">info@vtecrm.com</a></span></span></td>
					</tr>
					<tr>
						<td>
						<table align="center" border="0" cellpadding="0" cellspacing="0" height="5" width="618">
							<tbody>
								<tr>
									<td align="center" valign="middle" width="20"><img height="1" src="http://www.vtecrm.com/newsletter/newsletter_2ore/images/placeholder.gif" width="20" /><img height="1" src="http://www.vtecrm.com/newsletter/newsletter_2ore/images/placeholder.gif" width="20" /></td>
								</tr>
							</tbody>
						</table>
						</td>
					</tr>
				</tbody>
			</table>
			</td>
			<td>&nbsp;</td>
		</tr>
	</tbody>
</table>';
$adb->updateClob($table_prefix.'_emailtemplates','body',"templatename='Tell a friend about VTE'",$body);
$adb->pquery("update {$table_prefix}_emailtemplates set subject=?, description=? where templatename=?",array('Try VTENEXT and discover the features of the BPM Engine','Try VTENEXT and discover the features of the BPM Engine','Tell a friend about VTE'));