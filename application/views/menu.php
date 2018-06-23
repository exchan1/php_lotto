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
    </div>
    <div id="navbar" class="navbar-collapse collapse">
    <ul class="nav navbar-nav">
        <li class="<?=(''==$mode) ? 'active' : '' ?>"><a href="/">Home</a></li>
        <li class="<?=('englist'==$mode) ? 'active' : '' ?>"><a href="/?mode=englist">Eng List</a></li>
        <li class="<?=('quiz'==$mode) ? 'active' : '' ?>"><a href="/?mode=quiz">Quiz</a></li>
        <li class="<?=('wordlist'==$mode) ? 'active' : '' ?>"><a href="/?mode=wordlist">Word List</a></li>
    </ul>
    </div><!--/.nav-collapse -->
</div>
</nav>