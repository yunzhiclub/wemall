<?php
/*
 *  分页类
 * @currentPage 当前页
 * @totalPage 总页数
 * @style 显示样式
 * 样式0.thinkphp自带样式
 */
namespace Admin\Controller;
class PageController
{
    private $currentPage = null;//当前页
    private $counts = null;//总条数
    private $pageSize = null;//每条显示多少页
    private $pageStyle = null;//显示样式
    private $displayNums = null;//最多在下方显示多少条页数
    private $css = null;//CSS样式
    private $beginItem = null;//起始
    private $endItem = null;//终止(偏移量)
    private $totalPages = null;//总页数
    private $beginPage = null;//显示开始页数
    private $maxDis = null;//显示最大页码数
    private $parameter = null; //查询条件,利用map传入，传入后拼接为字符串
    public function __construct()
    {
        $this->currentPage = 1;
        $this->counts = 0;
        $this->pageSize = 20;
        $this->pageStyle = 2;
        $this->maxPages = 10;
        $this->parameter = '';
    }
    function setCurrentPage($currentPage) {
        $this->currentPage = $currentPage;
    }

    function setCounts($counts) {
        $this->counts = $counts;
    }

    function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }

    function setPageStyle($pageStyle) {
        $this->pageStyle = $pageStyle;
    }

    function setDisplayNums($displayNums) {
        $this->displayNums = $displayNums;
    }

    function setCss($css) {
        $this->css = $css;
    }
    function getPageSize() {
        return $this->pageSize;
    }

    function getBeginItem() {
        return $this->beginItem;
    }
    function getCurrentPage() {
        return $this->currentPage;
    }
    function setMap($map)
    {
        $this->parameter = '';
        if(!is_array($map))
        {
            return false;
        }
        foreach($map as $key => $value)
        {
            $this->parameter .= '&' . $key . '=' . $value;
        }
        
    }
        
     /*
     * 获取分页信息
     */
    public function fetch()
    {
        if($this->pageStyle == 0)
        {
            return $this->_fetchStyle0();
        }
        else
        {
            //生成分页数据
            $this->_makePage();
            if($this->pageStyle == 1)
            {
                return $this->_fetchStyle1();
            }
            elseif($this->pageStyle == 2)
            {
                return $this->_fetchStyle2();
            }
        }
    }

    /*
     * 生成分页数据
     */
    private function _makePage()
    {
        $this->currentPage = $this->_getInt($this->currentPage);
        $currentPage = $this->currentPage;
        $this->pageSize = $this->_getInt($this->pageSize);
        $this->displayNums = $this->_getInt($this->maxPages);
        $totalPages = ceil($this->counts/$this->pageSize);//取出总页数
        //如果当前页小于0，则至0，如果大于总页数，则至为总页数。
        $currentPage = ($totalPages>$currentPage?$currentPage:$totalPages);
        $this->currentPage = $currentPage;
        $subDis = (int)(($this->displayNums)/2);//当前位于中间时，页前后显示多少页
        $beginPage = ($currentPage-$subDis)>1?$currentPage-$subDis:1;//如果当开始页小于则至为1.
        $maxDis = $subDis*2;//设置显示的页码总数（不包含当前）
        if(($beginPage+$maxDis) > $totalPages)
        {
            if($totalPages - $maxDis < 1)
            {
                $beginPage = 1;
                $maxDis = $totalPages - $beginPage;
            }
            else
            {
                $beginPage = $totalPages - $maxDis;
            }
        }
        $pageSize = $this->pageSize;
        $this->beginItem = ($currentPage-1)*$pageSize;//起始
        $this->beginItem = ($this->beginItem < 0)?0:$this->beginItem;
        $this->endItem = ($this->beginItem+$pageSize) > $counts ? $counts : $this->beginItem+$pageSize;//终止
        $this->offSet = $this->endItem - $this->beginItem;
        $this->totalPages = $totalPages;//总页数
        $this->beginPage = $beginPage;//显示开始页数
        $this->maxDis = $maxDis;//显示最大页数
    }
    
        /*
     * 样式0
     * thinkphp自带样式
     * 调用\think\page类
     * 输出代码示例:
     * "<div> 
     *  <span class="current">1</span>
     * <a class="num" href="/wemall/WeiPHP/admin.php/Menu/Index/index/p/2.html">2</a> 
     * <a class="next" href="/wemall/WeiPHP/admin.php/Menu/Index/index/p/2.html">>></a> </div>"
     *  12 >> 
     */
    private function _fetchStyle0()
    {
        $page = new \Think\Page($this->counts,$this->pageSize);
        $page->parameter = $this->parameter;//带入查询条件
        return $page->show();        
    }

     //取正整数
    private function _getInt($value)
    {
        $res = (($value == '') || ($value < 1)) ? 1 : intval($value);
        return $res;
    }
    /*
     * 样式1
     * 云智样式
     * 输出代码
     * "<span class="begin">第1页/共2页</span>
     * <span class="separator"></span>
     * <a href="javascript:void(0)" class="first current" data-page="1">首页</a>
     * <a href="javascript:void(0)" class="prev current" data-page="1">上一页</a>
     * <a href="javascript:void(0)" data-page="1" class="current">1</a>
     * <a href="javascript:void(0)" data-page="2">2</a>
     * <a href="javascript:void(0)" class="next" data-page="2">下一页</a>
     * <a href="javascript:void(0)" class="last" data-page="2">末页</a>
     * <span class="separator"></span>
     * <span class="end">第1-20条/共21条</span>"
     * 第1页/共2页首页上一页12下一页末页第1-20条/共21条
     */
    private function _fetchStyle1()
    {
        $currentPage = $this->currentPage;
        $totalPages = $this->totalPages;
        $beginPage = $this->beginPage;
        $maxDis = $this->maxDis;
        
        //显示接接字符串
        $showPage = '<span class="begin">';
        $showPage .='第' . $currentPage . '页/共' . $totalPages . '页</span>';
        $showPage .='<span class="separator"></span>';
        $showPage .= '<a href="javascript:void(0)" class="first';
        if($currentPage == 1 || $currentPage == 0)
        {
            $showPage .= ' current'; 
        }
        $showPage .= '" data-page="1">首页</a>';
        $showPage .= '<a href="javascript:void(0)" class="prev';
        if($currentPage == 1 || $currentPage==0)
        {
            $showPage .= ' current';
        }
        $showPage .= '" data-page="';
        $showPage .= ($currentPage==1 ||$currentPage == 0)?1:$currentPage-1;
        $showPage .= '">上一页</a>';
        //$showPage .= '<span class="separator"></span>';
        for($i=$beginPage;$i<=$beginPage+$maxDis;$i++)
        {
            $showPage .= '<a href="javascript:void(0)" data-page="' . $i .'"';
            if($i == $currentPage)
            {
                $showPage .= ' class="current"';
            }
            $showPage .= '>' . $i . '</a>';
        }
        //$showPage .= '<span class="separator"></span>';
        $showPage .= '<a href="javascript:void(0)" class="next';
        if($currentPage == $totalPages)
        {
            $showPage .= ' current';
        }
        $showPage .= '" data-page="';
        $showPage .= $currentPage == $totalPages ? ($currentPage == 0 ? 1:$currentPage) : $currentPage+1;
        $showPage .= '">下一页</a>';
        $showPage .= '<a href="javascript:void(0)" class="last';
        if( ($currentPage == 0) || ($currentPage == $totalPages))
        {
            $showPage .= ' current';
        }
        $showPage .= '" data-page="' . ($totalPages?$totalPages:1) . '">末页</a>';
        if($this->counts == 0)
        {
            $beginItem = 0;        
        }
        else
        {
            $beginItem = ($currentPage-1)*$this->pageSize+1;
        }
        $showPage .= '<span class="separator"></span>';
        $showPage .= '<span class="end">第' . $beginItem . '-' . ($currentPage*$this->pageSize > $this->counts ? $this->counts : $currentPage*$this->pageSize ) .'条';
        $showPage .= '/共' . $this->counts . '条</span>';
        return $showPage;
    }
    /*
     * 样式2
     * Akira模板样式
     * 依赖于梦云智样式
     * <ul>
     * <li class="disabled"><a href="javascript:void(0)">第2页/共2页</a></li>
     * <li ><a href="?p=1"" >首页</a></li>
     * <li><a href="?p=1">上一页</a></li>
     * <li><a href="?p=1">1</a></li>
     * <li class="active"><a href="?p=2">2</a></li>
     * <li class="active"><a href="?p=2">下一页</a></li>
     * <li class="active"><a href="?p=2">末页</a></li>
     * <li class="disabled"><a href="javascript:void(0)">第21-21条/共21条</a></li>
     * </ul>
     * 
     */
    private function _fetchStyle2()
    {
        $currentPage = $this->currentPage;
        $totalPages = $this->totalPages;
        $beginPage = $this->beginPage;
        $maxDis = $this->maxDis;
        //进行字符串拼接
        $showpage = '';
        $showPage .= '<ul>';
        $showPage .='<li class="disabled"><a href="javascript:void(0)">第' . $currentPage . '页/共' . $totalPages . '页</a></li>';
        $showPage .= '<li ';
        if($currentPage == 1 || $currentPage == 0)
        {
            $showPage .= 'class="active"'; 
        }
        $showPage .= '><a href="?p=1' . $this->parameter . '"" >首页</a></li>';
        $showPage .= '<li';
        if($currentPage == 1 || $currentPage==0)
        {
            $showPage .= ' class="active"';
        }
        $showPage .= '><a href="?p=';
        $showPage .= ($currentPage==1 ||$currentPage == 0)?1:$currentPage-1;
        $showPage .= $this->parameter . '">上一页</a></li>';
        //$showPage .= '<span class="separator"></span>';
        for($i=$beginPage;$i<=$beginPage+$maxDis;$i++)
        {
            $showPage .= '<li';
            if($i == $currentPage)
            {
                $showPage .= ' class="active"';
            }
            $showPage .= '><a href="?p=' . $i . $this->parameter . '">' . $i . '</a></li>';
        }
        //$showPage .= '<span class="separator"></span>';
        $showPage .= '<li';
        if($currentPage == $totalPages)
        {
            $showPage .= ' class="active"';
        }
        $showPage .= '><a href="?p=';
        $showPage .= $currentPage == $totalPages ? ($currentPage == 0 ? 1:$currentPage) : $currentPage+1;
        $showPage .= $this->parameter . '">下一页</a></li>';
        $showPage .= '<li';
        if( ($currentPage == 0) || ($currentPage == $totalPages))
        {
            $showPage .= ' class="active"';
        }
        $showPage .= '><a href="?p=' . ($totalPages?$totalPages:1) . $this->parameter . '">末页</a></li>';
        if($this->counts == 0)
        {
            $beginItem = 0;        
        }
        else
        {
            $beginItem = ($currentPage-1)*$this->pageSize+1;
        }
        $showPage .= '<li class="disabled"><a href="javascript:void(0)">第' . $beginItem . '-' . ($currentPage*$this->pageSize > $this->counts ? $this->counts : $currentPage*$this->pageSize ) .'条';
        $showPage .= '/共' . $this->counts . '条</a></li>';
        $showPage .= "</ul>";
        return $showPage;
        
    }
}