<!DOCTYPE html>
<html lang="ko">

<?php
$kai = (isset($_GET['kai'])) ? $_GET['kai'] : 1;
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>부트스트랩 템플릿</title>

    <!-- 합쳐지고 최소화된 최신 CSS -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <!-- 부가적인 테마 -->
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
    <!-- jQuery (부트스트랩의 자바스크립트 플러그인을 위해 필요합니다) -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <!-- 합쳐지고 최소화된 최신 자바스크립트 -->
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
    <!--[if lt IE 9]>
        <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <script src="/resources/main.js"></script>
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
    <a class="navbar-brand" href="/">Bootstrap theme >> <?=$class?></a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
        <li class="active"><a href="/">Home</a></li>
    </ul>
    </div><!--/.nav-collapse -->
</div>
</nav>

<!-- container -->
<div class="container theme-showcase" role="main" style="margin-top:51px;">
    <div class="page-header">
        <h1><?=$kai?> 회차</h1>
        <select name="kai" id="kaiList" onchange="location.href='?kai='+this.value" class="form-control">
            <?php
            foreach ($data as $k => $v) {
                ?><option value="<?=$v?>"><?=$v?></option><?
            }
            ?>
        </select>
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
            <tr>
                <?php
                foreach ($lotto as $k => $v) {
                    echo '<td>'.$v.'</td>';
                }
                ?>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="form-group">
        <textarea id="slackMsg" class="form-control" rows="5"></textarea>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary btnSlackTest">Slack Test</button>
        <button type="button" class="btn btn-success btnLottoBomb" data-url="/?mode=bomb">lottobomb Site</button>
        <button type="button" class="btn btn-success btnRecommend" data-url="/?mode=autolotto">로또추천</button>
        <button type="button" class="btn btn-info btnRecommendList" data-url="/?mode=recommendList">List</button>
        <button type="button" class="btn btn-primary btnLottoNo" data-url="/?mode=autolottono">로또 등록</button>
        <button type="button" class="btn btn-primary btnLottoNo" data-url="/?mode=lottoresult">당첨확인</button>
        <button type="button" class="btn btn-danger btnLottoDel" data-url="/?mode=lottodel">lotto Del</button>
    </div>
</div>
<!-- /container -->

<input type="hidden" id="kai" value="<?=$kai?>" />

</body>
</html>