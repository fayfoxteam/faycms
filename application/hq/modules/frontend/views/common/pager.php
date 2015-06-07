<?php
//当前页
$currentPage = $listview->current_page;
//总页数
$totalPages = $listview->total_pages;
//加载地址
$reload = $listview->reload;
//总条数
$total = $listview->total_records;
//前后显示页数
$adjacents = $listview->adjacents;

$prevlabel = "上一页";
$nextlabel = "下一页";

$out = "<div class='gyah-minryema'><form method='get'>\n<ul>";

$currentPage = $currentPage<=0?1:$currentPage;
$currentPage = $currentPage>$totalPages?$totalPages:$currentPage;

// previous
if($currentPage==1) {
    $out.= '<li class="gyah-minryemaxyy"><span>上一页</span></li>';
}
elseif($currentPage==2) {
    $out.= "<li class='prev'><a href='{$reload}'>{$prevlabel}</a></li>\n";
}else {
    $out.= "<li class='prev'><a href=\"" . $reload . "?page=" . ($currentPage-1) . "\">" . $prevlabel . "</a></li>\n";
}

// first
if($currentPage>($adjacents+1)) {
    $out.= "<li><a href=\"" . $reload . "\">1</a></li>\n";
}

// interval
if($currentPage>($adjacents+2)) {
    $out.= "<li><span>...</span></li>\n";
}

// pages
$pmin = ($currentPage>$adjacents) ? ($currentPage-$adjacents) : 1;
$pmax = ($currentPage<($totalPages-$adjacents)) ? ($currentPage+$adjacents) : $totalPages;
for($i=$pmin; $i<=$pmax; $i++) {
    if($i==$currentPage) {
        $out.= "<li class='curent'><a class='yemabg' href=''> ". $i . "</a></li>\n";
    }
    elseif($i==1) {
        $out.= "<li><a href=\"" . $reload . "\">" . $i . "</a></li>\n";
    }
    else {
        $out.= "<li><a href=\"" . $reload . "?page=" . $i . "\">" . $i . "</a></li>\n";
    }
}

// interval
if($currentPage<($totalPages-$adjacents-1)) {
    $out.= "<li><span>...</span></li>\n";
}

// last
if($currentPage<($totalPages-$adjacents)) {
    $out.= "<li><a href=\"" . $reload . "?page=" . $totalPages . "\">" . $totalPages . "</a></li>\n";
}

// next
if($currentPage<$totalPages) {
    $out.= "<li class='next'><a href=\"" . $reload . "?page=" . ($currentPage+1) . "\">下一页</a></li>\n";
}
else {
    $out.= '<li class="next"><span>下一页</span></li>';
}

$out.="</ul></form>";
//$out.= "<div class='clear'></div>";
$out.="<p class='pagination_info'>当前 ".$currentPage." / ".$totalPages."页 (共 ".$total." 条记录)</p>";
$out.= "</div>";

echo $out;