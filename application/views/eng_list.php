<!-- container -->
<div class="container theme-showcase" role="main" style="margin-top:51px;">
    <div class="page-header">
        <h1>빅보카 영어</h1>
        <div class="form-group">
            <input type="text" id="input_eng" class="form-control" placeholder="English" />
            <input type="text" id="input_ko" class="form-control" placeholder="Korean" />
            <input type="hidden" id="input_idx" class="form-control" placeholder="Korean" />
            <button type="button" class="btn btn-success btnInsertBigvoca" data-url="/?mode=insertEng">등록</button>
            <button type="button" class="btn btn-success btnUpdateBigvoca" data-url="/?mode=updateEng">수정</button>
        </div>
    </div>

    <div class="row">
        <table class="table">
            <thead>
            <tr>
                <th>idx</th>
                <th>English</th>
                <th>Korean</th>
                <th>Btn</th>
            </tr>
            </thead>
            <tbody class="bigvocaList">
            </tbody>
        </table>
    </div>
    <div class="form-group">
        <button type="button" class="btn btn-info btnListBigvoca" data-url="/?mode=englist_ajax">List</button>
    </div>
</div>
<!-- /container -->