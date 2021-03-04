<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
<div class="container" style="width: 99%;">
	<div class="member">
		<div class="member-info">
			<div class="imgMem">
				<img src="images/mlm_tree_view/p.png" style="cursor:pointer">
			</div>
			<span class="clsspanid">TR65838798 DEV</span>
			<div class="divhrline"></div>
		</div>
		<div style="width:100%"><div class="divhr"></div></div>
		<div style="width:100%">
			<div class="divhrlineleft"></div>
			<div class="divhrlineright"></div>
		</div>
	</div>
	<div class="container" style="width: 49%;">
		<div class="member">
			<div class="member-info">
				<div class="imgMem">
					<img src="images/mlm_tree_view/f.png" style="cursor:pointer">
				</div>
				<span class="clsspanid">TR59974921 UNICGROW  </span>
				<div class="divhrline"></div>
			</div>
			<div style="width:100%"><div class="divhr"></div></div>
			<div style="width:100%">
				<div class="divhrlineleft"></div>
				<div class="divhrlineright"></div>
			</div>
		</div>
		<div class="container" style="width: 49%;">
			<div class="member">
				<div class="member-info">
					<div class="imgMem">
						<img src="images/mlm_tree_view/f.png" style="cursor:pointer">
					</div>
					<span class="clsspanid">TR89977555  UNICGROW </span>
				</div>
			</div>
		</div>
		<div class="container" style="width: 49%;">
			<div class="member">
				<div class="member-info">
					<div class="imgMem">
						<img src="images/mlm_tree_view/c.png" style="cursor:pointer">
					</div>
					<span class="clsspanid">  </span>
					<span style="display:none" class="clsspanName">
						<span onclick="return BindChildTree(this,0,1,0," l")"=""></span>
					</span>
				</div>
			</div>
		</div>
	</div>
	<div class="container" style="width: 49%;">
		<div class="member">
			<div class="member-info">
				<div class="imgMem">
					<img src="images/mlm_tree_view/f.png" style="cursor:pointer">
				</div>
				<span class="clsspanid">TR14436285  UNICGROW   </span>
				<div class="divhrline"></div>
			</div>
			<div style="width:100%"><div class="divhr"></div></div>
			<div style="width:100%">
				<div class="divhrlineleft"></div>
				<div class="divhrlineright"></div>
			</div>
		</div>
		<div class="container" style="width: 49%;">
			<div class="member">
				<div class="member-info">
					<div class="imgMem">
						<img src="images/mlm_tree_view/c.png" style="cursor:pointer">
					</div>
					<span class="clsspanid">  </span>
				</div>
			</div>
		</div>
		<div class="container" style="width: 49%;">
			<div class="member">
				<div class="member-info">
					<div class="imgMem">
						<img src="images/mlm_tree_view/f.png" style="cursor:pointer">
					</div>
					<span class="clsspanid">TR85881291  UNICGROW   </span>
				</div>
			</div>
		</div>
	</div>
</div>
<style type="text/css">
    .container {
        text-align: center;
        margin: 10px -.025%;
        padding: 10px .5%;
        float: left;
        overflow: visible;
        position: relative;
    }

    .divhr {
        border: 1px solid;
        width: 49.2%;
        margin-left: 24.5%;
        /* margin-right: auto; */
    }

    .divhrline {
        border: 1px solid;
        width: 1px;
        height: 25px;
        margin-left: 24.5%;
        margin-left: auto;
        margin-right: auto;
    }

    .divhrlineleft {
        border: 1px solid;
        width: 1px;
        height: 25px;
        margin-left: 24.5%;
        margin-right: auto;
        text-align: left;
        float: left;
    }

    .divhrlineright {
        border: 1px solid;
        width: 1px;
        height: 25px;
        margin-right: 26.3%;
        margin-left: auto;
        text-align: right;
    }

    .member {
        position: inherit;
        z-index: 1;
        cursor: default;
        /*border-bottom: solid 1px #BEBEBE;*/
        font-size: 13px;
        text-align: center;
    }

        .member .imgMem {
            text-align: center !important;
            margin-top: -18px;
            
        }

        .member:after {
            display: none;
            position: absolute;
            left: 50%;
            width: 1px;
            height: 20px;
            text-align: center;
            content: " ";
            bottom: 100%;
            border: 1px solid black;
        }

        .member:hover {
            z-index: 2;
        }
        .member .metaInfo .detail-row-col dt {
        border:none!important;
          
        }

    /*.clsspanid:hover .metaInfo {
        display: block;
    }*/

    .clsspanid {
        text-transform: uppercase;
        font-size: 14px;
        cursor: pointer;
    }

    .member .metaInfo img {
        width: 40px;
        height: 40px;
        display: inline-block;
    }

    .member .metaInfo span {
        display: inline-block;
        padding: 3px;
        color: black;
    }

    .imgMem {
        margin-top: 5px;
        z-index: 999;
        border-radius: 25px;
        height: 50px;
        width: 50px;
        margin: 0 auto;
    }

    .SubmitButton {
        padding: 4px 9px !important;
    }

    .tblBusiness {
        width: 100% !important;
        border: 1px solid #BEBEBE;
    }

        .tblBusiness td {
            text-align: left;
            font-size: 10px;
        }

    .clsTipTable td {
        border: solid 1px #ddd;
        text-align: left;
    }

        .clsTipTable td span {
            width: 100%;
        }

    .clsTipTable {
        width: 100%;
        background:#fff;
        padding:5px 0px 5px 4px;
    }
    /*.metaInfo{position:fixed}
    .divInfo {
        position: fixed;
    }*/
    /*.member .metaInfo .divInfo {
    width:50%;
    }*/

    #mainContainer .container { position:inherit;}


    .member-info { width:100px; margin:auto}
    .member-info:hover .metaInfo{display:block;  }
.imgMem {
    margin-top: 20px;
    z-index: 999;
    height: 70px;
    width: 70px;
    border-radius: 100px;
}

.imgMemhead {
    margin-top: 10px;
    z-index: 999;
    height: 80px;
    width: 80px;
    border-radius: 100px;
}
    

</style>