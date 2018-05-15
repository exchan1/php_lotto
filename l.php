<?
/*
 * 로또 분석 프로그램
 * 2015.09.24 - 최초 프로그램 작성 >> 기존의 당첨된 로또번호를 웹에서 가져와서 화면출력
 */

$kai = (isset($_GET['kai'])) ? $_GET['kai'] : 1;
include './application/libraries/Snoopy.php';
header("Content-Type: text/html; charset=UTF-8");

$snoopy = new snoopy;
$getUrl = "http://www.645lotto.net/gameResult.do?method=byWin&drwNo=".$_GET['kai'];
$snoopy->fetch($getUrl);

$pattern='/<option value="[0-9]*"\s*>(.*?)<\/option>/';
preg_match_all($pattern,$snoopy->results,$out);

$lottoNo = array();

?>


<!DOCTYPE html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- 위 3개의 메타 태그는 *반드시* head 태그의 처음에 와야합니다; 어떤 다른 콘텐츠들은 반드시 이 태그들 *다음에* 와야 합니다 -->
	<title>부트스트랩 템플릿</title>

	<!-- 부트스트랩 -->
	<!-- <link href="css/bootstrap.min.css" rel="stylesheet"> -->

	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<!-- 부가적인 테마 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
	<!-- jQuery (부트스트랩의 자바스크립트 플러그인을 위해 필요합니다) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<script src="/resources/excel-bootstrap-table-filter-bundle.js"></script>
	<link rel="stylesheet" href="/resources/excel-bootstrap-table-filter-style.css">

	<!-- IE8 에서 HTML5 요소와 미디어 쿼리를 위한 HTML5 shim 와 Respond.js -->
	<!-- WARNING: Respond.js 는 당신이 file:// 을 통해 페이지를 볼 때는 동작하지 않습니다. -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<style type="text/css">
	#count { margin:10px 0px 10px 0px }
	#count td { padding:15px; }
	</style>

	<script type="text/javascript">
	var searchNo = function(){
		$.ajax({
			type : 'post',
			url : './lexec.php',
			data : {kai:$('[name="kai"]').val(),save:$('[name="save"]').prop("checked")},
			dataType : 'json'
		}).done(function(data) {
			// console.log(data);
			$('#lno').html('');
			if(data.lno!=''){
				var hh = '';
				$.each(data.lno,function(i){
					hh += '<td>'+data.lno[i]+'</td>';
				});
				$('#lno').html(hh);
				$('#cno').html($('[name="kai"]').val()+' 회차');
			}
		}).fail(function() {
			alert("error");
		});
	}

	$(document).ready(function(){
		var newNo = 0;
		initCount();
		$('[name="kai"]').val($('#kai').val());
		$('#countNo').click(function(){
			countNo();
		});

		$('#btnSearch').click(function(){
			searchNo();
		});

		$('#btnSearchPrev').click(function(){
			var objNo = Number($('[name="kai"]').val());
			newNo = (objNo>1) ? (objNo-1) : objNo;
			$('[name="kai"]').val(newNo);
			searchNo();
		});

		$('#btnInsertAll').click(function(){
			var re = new Array();
			var kai="";
			$('[name="kai"] option').each(function(){
				kai += $(this).val()+',';
			});
			insertLno(kai,re);
		});

		getAllNo();
		$('#lotte_list').excelTableFilter();
	});

	var countNo = function(){
		var i=0;
		var txt = "";
		var arr=0;
		var color="";
		$('#count').find('td').css('background-color', '#fff');
		$('#count').find('td').html(0);
		$('#allNo>tr').each(function(i){
			var j=0;
			txt = "";
			$(this).find('td').each (function() {
				if(j!=0 && j!=7){
					arr = $(this).text()-1;
					txt = $('#count>tbody>tr>th:eq("'+arr+'")').html();
					// $('#count>tbody>tr>th:eq("'+arr+'")').css('background-color', 'yellow');
					var noCnt = $('#count>tbody>tr>td:eq("'+arr+'")').html();
					$('#count>tbody>tr>td:eq("'+arr+'")').html(parseInt(noCnt)+1);
					if(parseInt(noCnt) > 3){
						color="#99ffff";
					} else if(parseInt(noCnt) > 6){
						color="#ffcc66";
					} else {
						color="#ffff99";
					}
					$('#count>tbody>tr>td:eq("'+arr+'")').css('background-color', color);
					console.log($(this).text()+' / '+txt);
				}
				j++;
			});
			i++;
			if(i==$('#cnt').val()){
				return false;
			}
		});
	}

	var initCount = function(){
		var h = "";
		for(var i=1;i<46;i++){
			if(i==1){ h += '<tr>'; }
			if((i%6)==1){ h += '</tr><tr>'; }
			h += "<th>"+i+"</th><td>0</td>";
		}
		$('#count>tbody').html(h);
	};

	var colToggle = function(obj){
		console.log($(obj).parent().css('background-color'));
		if($(obj).parent().css('background-color')=='rgba(0, 0, 0, 0)') {
			$(obj).parent().css('background-color', '#ffcc66');
		} else {
			$(obj).parent().css('background-color', '');
		}
	};

	var getAllNo = function(){
		$.ajax({
			type : 'post',
			url : './lget.php',
			dataType : 'json'
		})
		.done(function(data) {
			$('#allNo').html('');
			if(data!=''){
				var hh = '';
				$.each(data,function(i){
					hh += '<tr>';
					hh += '<td onclick="colToggle(this)">'+data[i].lno+'</td>';
					hh += '<td>'+data[i].n1+'</td>';
					hh += '<td>'+data[i].n2+'</td>';
					hh += '<td>'+data[i].n3+'</td>';
					hh += '<td>'+data[i].n4+'</td>';
					hh += '<td>'+data[i].n5+'</td>';
					hh += '<td>'+data[i].n6+'</td>';
					hh += '<td>'+data[i].nb+'</td>';
					hh += '</tr>';
				});
				$('#allNo').html(hh);
				//$('#cno').html($('[name="kai"]').val()+' 회차');
			}
		})
		.fail(function() {
			alert("error");
		});
	}

	var insertLno = function(n,re){
		$.ajax({
				type : 'post',
				url : './lsave.php',
				data : {kai:n},
				dataType : 'json'
			})
			.done(function(data) {
				re.push(data);
			})
			.fail(function() {
				console.log(n+" error");
			});
	}
	</script>>
</head>
<body role="document">

	<!-- Fixed navbar -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="#">Bootstrap theme</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
			<ul class="nav navbar-nav">
				<li class="active"><a href="#">Home</a></li>
				<li><a href="#about">About</a></li>
				<li><a href="#contact">Contact</a></li>
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Dropdown <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="#">Action</a></li>
					<li><a href="#">Another action</a></li>
					<li><a href="#">Something else here</a></li>
					<li class="divider"></li>
					<li class="dropdown-header">Nav header</li>
					<li><a href="#">Separated link</a></li>
					<li><a href="#">One more separated link</a></li>
				</ul>
				</li>
			</ul>
			</div><!--/.nav-collapse -->
		</div>
		</nav>

		<input type="hidden" id="kai" value="<?=$kai?>" />

		<div class="container theme-showcase" role="main" style="margin-top:51px;">

		<div class="page-header">
			<h1 id="cno"><?=$kai?> 회차</h1>

			<div class="form-inline">
				<select name="kai" class="form-control">
					<?
					for( $i=0 ; $i < sizeof($out[0]) ; $i++ ){
						echo($out[0][$i]);
					}
					?>
				</select>
				<input type="button" value="Search" id="btnSearch" class="btn btn-success" />
				<input type="button" value="Insert All" id="btnInsertAll" class="btn btn-success" />
				<input type="button" value="<<" id="btnSearchPrev" class="btn btn-success" />
				<!-- <input type="button" value=">>" id="btnSearchNext" class="btn btn-success" /> -->
				<br /><label><input type="checkbox" name="save" value="1" class="form-control" /> CSV File Save</label>
			</div>
		</div>

		<div class="row">
			<table class="table">
				<thead>
				<tr>
					<th>1</th>
					<th>2</th>
					<th>3</th>
					<th>4</th>
					<th>5</th>
					<th>6</th>
					<th>보너스</th>
				</tr>
				</thead>
				<tbody>
				<tr id="lno">
				</tr>
				</tbody>
			</table>

			<table style="width:100%">
			<tr>
				<td>
					<table class="table" id="lotte_list">
					<thead>
					<tr>
						<th>회차</th>
						<th>1</th>
						<th>2</th>
						<th>3</th>
						<th>4</th>
						<th>5</th>
						<th>6</th>
						<th>보너스</th>
					</tr>
					</thead>
					<tbody id="allNo"></tbody>
					</table>
				</td>
				<td valign="top">
					<table class="table" id="count">
					<thead>
					<tr>
					<th colspan="12" class="form-inline">
					당첨번호 Count
					<input type="text" class="form-control" id="cnt" value="10" />
					<input type="button" value="Count" id="countNo" class="btn btn-success" />
					</th>
					</tr>
					</thead>
					<tbody></tbody>
					</table>
				</td>
			</tr>
			</table>
		</div>


		</div> <!-- /container -->

</body>
</html>
