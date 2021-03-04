<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>MLM Developers</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" type="text/css" href="web_css/css_style1.css" />
<link rel="stylesheet" href="web_css/calendar.css" type="text/css" />
<script language="JavaScript" src="js/calendar_us.js" type="text/javascript"></script>
<script type="text/javascript" src="user_menu/stmenu.js" language="javascript"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
	<style>
      div#container {
        width: 580px;
        margin: 100px auto 0 auto;
        padding: 20px;
        background: #000;
        border: 1px solid #1a1a1a;
      }
      
      /* HOVER STYLES */
      div#pop-up {
        display: none;
        position:absolute;
        width:410px;
        padding:0;
        background: #eeeeee;
        color: #000000;
        border: 1px solid #ffffff;
        font-size: 90%;
      }
    </style>
    <script type="text/javascript">
      $(function() {
        var moveLeft = 20;
        var moveDown = 10;
        
        $('a#trigger').hover(function(e) {
          $('div#pop-up').show();
          //.css('top', e.pageY + moveDown)
          //.css('left', e.pageX + moveLeft)
          //.appendTo('body');
        }, function() {
          $('div#pop-up').hide();
        });
        
        $('a#trigger').mousemove(function(e) {
          $("div#pop-up").css('top', e.pageY + moveDown).css('left', e.pageX + moveLeft);
        });
        
      });
    </script>
</head>
<body>

			
						<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" style="margin-top:10px; margin-bottom:10px;">
						  
						  <tr>
							<td width="21"></td>
							<td valign="top">
							<table width="100%" border="0" cellpadding="0" cellspacing="0">
                              <tr><td height="1" colspan="2" valign="top" bgcolor="#E9EDF0"></td></tr><tr><td height="1" colspan="2" valign="top" bgcolor="#ffffff"></td></tr>
                              <tr>
                                <td height="55" colspan="2" class="in_inner_title1"><span>MLM Developers</span><br />
                                Binary Tree </td>
                              </tr>
                              <tr>
							  <td height="1" colspan="2" valign="top" bgcolor="#E9EDF0"></td>
							  </tr>
							  <tr><td height="1" colspan="2" valign="top" bgcolor="#ffffff"></td>
							  </tr>
                              <tr>
                                <td height="40" colspan="2" valign="top" class="in_inner_txt1">
								<table class="MyTable" border="0" bordercolor="#FFFFFF" style="border-collapse:collapse; margin:6px;" cellpadding="0" cellspacing="0" width="99%" >
                                  <tbody>
                                    <tr class="MyTitle">
                                      <td height="30" colspan="6" bgcolor="#E3E8EC"> &nbsp;&nbsp;&nbsp;<strong>Legend </strong></td>
                                    </tr>
                                    <tr>
                                      <td width="17%" height="60"> &nbsp;&nbsp;Registered </td>
                                      <td width="17%"><img src="web_img/binary_icon11.jpg" width="50" height="50" /></td>
                                      <td width="17%"> &nbsp;&nbsp;Basic </td>
                                      <td width="17%"><img src="web_img/binary_icon21.jpg" width="50" height="50" /></td>
                                      <td width="17%"> &nbsp;&nbsp;Premium </td>
                                      <td width="17%"><img src="web_img/binary_icon51.jpg" width="50" height="50" /></td>
                                    </tr>
                                  </tbody>
                                </table>
								</td>
                              </tr>
                              <tr>
                                <td width="51%" height="40" valign="middle" class="in_inner_txt1">
								<table class="MyTable" border="1" bordercolor="#FFFFFF" style="border-collapse:collapse; margin:6px;" cellpadding="0" cellspacing="0" width="99%">
                                  <tbody>
                                    <tr class="MyTitle">
                                      <td width="54%" height="30" bgcolor="#E3E8EC"><strong> &nbsp;&nbsp;&nbsp;Tree Count</strong></td>
                                      <td width="23%" bgcolor="#E3E8EC"><strong> &nbsp;&nbsp;&nbsp;Left </strong></td>
                                      <td width="23%" bgcolor="#E3E8EC"><strong> &nbsp;&nbsp;&nbsp;Right </strong></td>
                                    </tr>
                                    <tr>
                                      <td> &nbsp;&nbsp;&nbsp;Registered </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblLeftR">&nbsp;&nbsp;&nbsp;2</span> </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblRightR">&nbsp;&nbsp;&nbsp;0</span> </td>
                                    </tr>
                                    <tr>
                                      <td> &nbsp;&nbsp;&nbsp;Basic </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblLeftB">&nbsp;&nbsp;&nbsp;0</span> </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblRightB">&nbsp;&nbsp;&nbsp;0</span> </td>
                                    </tr>
                                    <tr>
                                      <td> &nbsp;&nbsp;&nbsp;Premium </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblLeftP">&nbsp;&nbsp;&nbsp;4</span> </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblRightP">&nbsp;&nbsp;&nbsp;0</span> </td>
                                    </tr>
                                    <tr class="MyFooter">
                                      <td> <strong>&nbsp;&nbsp;&nbsp;Total </strong></td>
                                      <td><strong> &nbsp;&nbsp;&nbsp;6 </strong></td>
                                      <td><strong> &nbsp;&nbsp;&nbsp;0 </strong></td>
                                    </tr>
                                  </tbody>
                                </table>
								</td>
                                <td width="49%" valign="top" class="in_inner_txt1">
								<table class="MyTable" border="1" bordercolor="#FFFFFF" style="border-collapse:collapse; margin:6px;" cellpadding="0" cellspacing="0" width="99%" >
                                  <tbody>
                                    <tr class="MyTitle">
                                      <td width="50%" height="30" bgcolor="#E3E8EC"><strong> &nbsp;&nbsp;&nbsp;PV Count </strong></td>
                                      <td width="25%" height="30" bgcolor="#E3E8EC"><strong> &nbsp;&nbsp;&nbsp;Left </strong></td>
                                      <td width="25%" height="30" bgcolor="#E3E8EC"><strong> &nbsp;&nbsp;&nbsp;Right </strong></td>
                                    </tr>
                                    <tr>
                                      <td> &nbsp;&nbsp;&nbsp;Paid PV </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblLeftPaidPV">&nbsp;&nbsp;&nbsp;0</span> </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblRightPaidPV">&nbsp;&nbsp;&nbsp;0</span> </td>
                                    </tr>
                                    <tr>
                                      <td> &nbsp;&nbsp;&nbsp;Potential PV </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblLeftUnPaidPV">&nbsp;&nbsp;&nbsp;800</span> </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblRightUnPaidPV">&nbsp;&nbsp;&nbsp;0</span> </td>
                                    </tr>
                                    <tr>
                                      <td> &nbsp;&nbsp;&nbsp;Renewal PV </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblLeftRenewalPV">&nbsp;&nbsp;&nbsp;0</span> </td>
                                      <td><span id="ctl00_ContentPlaceHolder1_lblRightRenewalPV">&nbsp;&nbsp;&nbsp;0</span> </td>
                                    </tr>
                                    
                                  </tbody><tr class="pager">
                                      <td> <strong>&nbsp;&nbsp;&nbsp;Total * </strong></td>
                                      <td><strong> &nbsp;&nbsp;&nbsp;800 </strong> </td>
                                      <td><strong> &nbsp;&nbsp;&nbsp;0 </strong> </td>
                                    </tr>
                                </table>
								</td>
                              </tr>
                              <tr>
                                <td height="40" colspan="2" valign="top" class="binary_tree_txt">
								<table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td colspan="8">
									<div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon1.jpg" width="76" height="76" /></a><br />
                                        <strong><a href="#" id="trigger">2</a></strong></div>
      <div id="pop-up">

<table class="MyTable" border="1" bordercolor="#FFFFFF" style="border-collapse:collapse; margin:6px;" cellpadding="0" cellspacing="0" width="400">
          <tr>
            <td width="113">Distributor ID </td>
            <td colspan="3">2</td>
            </tr>
          <tr>
            <td>Distributor Name</td>
            <td colspan="3">gfh</td>
            </tr>
          <tr>
            <td height="25" colspan="4" bgcolor="#E3E8EC"><p><strong>Date Of Joining : </strong><strong>27/07/2011 </strong></p></td>
            </tr>
          <tr>
            <td>Sponsor ID </td>
            <td colspan="3">1</td>
            </tr>
          <tr>
            <td>Sponsor Name</td>
            <td colspan="3">Edata online.biz</td>
            </tr>
          <tr>
            <td>Binary ID</td>
            <td colspan="3">1</td>
            </tr>
          <tr>
            <td>Binary Name</td>
            <td colspan="3">Edata online.biz</td>
            </tr>
          <tr>
            <td>Total Left ID</td>
            <td width="115">15</td>
            <td width="119">Total Right ID </td>
            <td width="53">7</td>
          </tr>
          <tr>
            <td height="25" colspan="4" bgcolor="#E3E8EC"><p><strong>SelfTopUp&nbsp; :</strong><strong> 10000 </strong></p></td>
            </tr>
          <tr>
            <td>Total Left TopUpAmount </td>
            <td>20000</td>
            <td>Total Right TopUpAmount</td>
            <td>20000</td>
          </tr>
          <tr>
            <td>Total Left Alpha TopUpAmount </td>
            <td>10000.00</td>
            <td>Total Right Alpha TopUpAmount</td>
            <td>10000.00</td>
          </tr>
          <tr>
            <td>Total Left Beta TopUpAmount </td>
            <td>10000.00</td>
            <td>Total Right Beta TopUpAmount</td>
            <td>10000.00</td>
          </tr>
        </table>

      </div>
									</td>
                                  </tr>
                                  <tr>
                                    <td colspan="8"><div align="center"><img src="web_img/band1.gif" width="599" height="35" /></div></td>
                                  </tr>
                                  <tr>
                                    <td colspan="4">
									<div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon5.jpg" width="76" height="76" /><br />
                                      <strong><a href="#" id="trigger">3</a></strong></div>
									  </td>
                                    <td colspan="4">
									<div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon5.jpg" width="76" height="76" /><br />
                                      <strong><a href="#" id="trigger">4</a></strong>
									</div>
									</td>
                                  </tr>
                                  <tr>
                                    <td colspan="4"><div align="center"><img src="web_img/band2.gif" width="357" height="35" /></div></td>
                                    <td colspan="4"><div align="center"><img src="web_img/band2.gif" width="357" height="35" /></div></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon2.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">5</a></strong></div></td>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon2.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">6</a></strong></div></td>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon2.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">7</a></strong></div></td>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon2.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">8</a></strong></div></td>
                                  </tr>
                                  <tr>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/band4.gif" width="130" height="35" /></a></div></td>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/band4.gif" width="130" height="35" /></a></div></td>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/band4.gif" width="130" height="35" /></a></div></td>
                                    <td colspan="2"><div align="center"><a href="#" id="trigger"><img src="web_img/band4.gif" width="130" height="35" /></a></div></td>
                                  </tr>
                                  <tr>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">9</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">10</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">11</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">12</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">13</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">14</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">15</a></strong></div></td>
                                    <td><div align="center"><a href="#" id="trigger"><img src="web_img/binary_icon3.jpg" width="76" height="76" /></a><br />
                                      <strong><a href="#" id="trigger">16</a></strong></div></td>
                                  </tr>
                                  <tr>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                    <td><div align="center"></div></td>
                                  </tr>
								  
                                </table>
								</td>
                              </tr>
                              <tr>
                                <td height="40" colspan="2" valign="middle" class="in_inner_txt1"><br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
                                <br />
								</td>
                              </tr>
                            </table>
							</td>
						  </tr>
						  
				  </table>
			

</body>
</html>