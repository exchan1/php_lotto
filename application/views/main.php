<?php
$kai = (isset($_GET['kai'])) ? $_GET['kai'] : 1;
?>

<!-- container -->
<div class="container theme-showcase" role="main" style="margin-top:51px;">
    <div class="page-header">
        <h1><?=$kai?> 회차</h1>
        <div class="form-group form-inline">
            <select name="kai" id="kaiList" onchange="location.href='?kai='+this.value" class="form-control">
                <?php
                foreach ($data as $k => $v) {
                    ?><option value="<?=$v?>"><?=$v?></option><?
                }
                ?>
            </select>
            <button type="button" class="btn btn-success btnMessageSet">선택</button>
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
        <button type="button" class="btn btn-info btnRecommendList" data-url="/?mode=recommendList">List</button>
        <button type="button" class="btn btn-info btnRecommendas" data-url="/?mode=recommendas">분석</button>
        <button type="button" class="btn btn-success btnLottoBomb" data-url="/?mode=bomb">lottobomb Site</button>
        <button type="button" class="btn btn-success btnRecommend" data-url="/?mode=autolotto">로또추천</button>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-primary btnLottoNo" data-url="/?mode=autolottono">로또 등록</button>
        <button type="button" class="btn btn-primary btnLottoNo" data-url="/?mode=lottoresult">당첨확인</button>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-danger btnLottoDel" data-url="/?mode=lottodel">lotto Del</button>
        <button type="button" class="btn btn-danger lottoNodel" data-url="/?mode=lottoNodel">등록 Del</button>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-danger btnBigvoca" data-url="/?mode=voca">Bigvoca</button>
    </div>
</div>
<!-- /container -->

<input type="hidden" id="kai" value="<?=$kai?>" />