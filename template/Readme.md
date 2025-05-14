##dropdown.js
##引入 <link rel="stylesheet" href="../../css/dropdown/dropdowm.css">
## <script src="../../js/dropdown/dropdown.js"></script>

##html
<div class="">
    <label for="back">标题</label>
    <div class="select form-control">
        <div class="dropdown-toggle text">这是placeholder</div>
        <input id="back">  //如果input有 val则 val为这个下拉框的默认值 ，如果没有 就没有默认值 placeholder是展示给用户看的
        <ul>
            <li>上海</li>
            <li>浦东</li>
        </ul>
    </div>
</div>