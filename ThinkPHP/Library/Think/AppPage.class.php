<?php
namespace Think;
class Apppage{

	var $currentPage;//当前页
	var $pageSize;//每页显示条数
	var $recordCount;//数据库里的总记录数
	var $totalPage;//总页数
	var $pageUrl;//后面的参数部分 如:pageUrl="?page=",

	function Page($_pagesize=10,$_recordcount=0,$_pageurl="")
	{
		$this->currentPage=abs((int)$_REQUEST['page']);
		($this->currentPage==0)?$this->currentPage=1:'';
		$this->pageSize=ceil(abs(@$_pagesize+0));
		(empty($this->pageSize))?$this->pageSize=5:'';
		$this->recordCount=ceil(abs(@$_recordcount+0));
		(empty($this->recordCount))?$this->recordCount=0:'';
		

		$this->totalPage=ceil($this->recordCount / $this->pageSize);
		if($this->currentPage > $this->totalPage){$this->currentPage=$this->totalPage;}
		if($this->currentPage <=0){$this->currentPage=1;}
		$this->Set_Url($_pageurl);
	}
	function GetPage()
	{
		// 分析分页参数
        if($this->url){
            $depr       =   C('URL_PATHINFO_DEPR');
            $url        =   rtrim(U('/'.$this->url,'',false),$depr).$depr.'__PAGE__';
        }else{
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)){
                $parameter      =   $this->parameter;
            }elseif(empty($this->parameter)){
                unset($_GET[C('VAR_URL_PARAMS')]);
                $var =  !empty($_POST)?$_POST:$_GET;
                if(empty($var)) {
                    $parameter  =   array();
                }else{
                    $parameter  =   $var;
                }
            }
            $parameter['p']  =   '__PAGE__';
            $url            =   U('',$parameter);
        }
		
	    if($this->currentPage==1)
		{
		   //$firstpage="<span>首页</span>";
		   //$prevpage="<span>上页</span>";
		}
		else
		{
		  $firstpage=$this->pageUrl;
		  $prevpage=$this->pageUrl.($this->currentPage-1);
		}
		if($this->currentPage==$this->totalPage or $this->totalPage==0)
		{
		   //$lastpage="<span>末页</span>";
		   //$nextpage="/index.php/xlhh/result.html";
		}
		else
		{
		   $lastpage=$this->pageUrl.$this->totalPage;
		   $nextpage=$this->pageUrl.($this->currentPage+1);
		}
		//$pagetext='共'.$this->recordCount.'条记录，当前页['.$this->currentPage.'/'.$this->totalPage.']';
		
		// 1 2 3 4 5
        $linkPage = "";
		$nowCoolPage    =   ceil($this->currentPage/$this->pageSize);
        for($i=1;$i<=$this->pageSize;$i++){
            $page       =   ($nowCoolPage-1)*$this->pageSize+$i;
            if($page!=$this->currentPage){
                if($page<=$this->totalPage){
                    $linkPage .= "<li><a href='".str_replace('__PAGE__',$page,$url)."?page=".$page."'>".$page."</a></li>";
                }else{
                    break;
                }
            }else{
                if($this->totalPage != 1){
                    $linkPage .= "<li><a href='javascript:;' id='check2'>".$page."</a></li>";
                }
            }
        }
		
		$data['linkpage'] = $linkPage;
		$data['prevpage'] = $prevpage;
		$data['firstpage'] = $firstpage;
		$data['lastpage'] = $lastpage;
		$data['nextpage'] = $nextpage;
		$data['totalPage'] = $this->totalPage;
		$data['currentPage'] = $this->currentPage;
		$startpage=1;
		$endpage=$this->totalPage;
		if($this->currentPage>4)
		{
		    if($this->totalPage<$this->currentPage+3)
			{
			   $startpage=($this->totalPage-4<1)?1:$this->totalPage-4;
			}
			else
			{
			   $startpage=$this->currentPage-1;
			   $endpage=$this->currentPage+3;
			}
		}
		else
		{
		   $endpage=($this->totalPage>=5)?5:$this->totalPage;	
		}
		$p='';
		for($i=$startpage;$i<=$endpage;$i++)
		{
		   	$p.=($i==$this->currentPage)?'<span>'.$i.'</span>':'<a href="'.$this->pageUrl.$i.'">'.$i.'</a>';
		}
		//return $firstpage.$prevpage.$p.$nextpage.$lastpage;
		return $data;
	}
	function Set_Url($url="")
	 {
	  if(!empty($url))
	  {
	   $this->pageUrl=$_SERVER['REQUEST_URI']."?page=";
	  }
	  else
	  {
	    if(empty($_SERVER['QUERY_STRING']))
		{
		   $this->pageUrl=$_SERVER['REQUEST_URI']."?page=";
	    }
		else
		{
		  $u = explode('&',$_SERVER['QUERY_STRING']);
		  if(stristr($_SERVER['QUERY_STRING'],'page=')!=false)
		  {
			 if(stristr($_SERVER['QUERY_STRING'],'page='.$this->currentPage.'&')!=false)
			 {
				$this->pageUrl=str_replace('page='.$this->currentPage.'&','',$_SERVER['REQUEST_URI']);
			 }
			 else
			 {
		       $this->pageUrl=str_replace('page='.$this->currentPage,'',$_SERVER['REQUEST_URI']);
			 }
		     $last=$this->pageUrl[strlen($this->pageUrl)-1];
		     if($last=='?'||$last=='&')
			 {
			    $this->pageUrl.="page=";
		     }
			 else
			 {
			     $this->pageUrl.='&page=';
		     }
		  }
		  else
		  {
			 $u = explode('&',$_SERVER['QUERY_STRING']);
			 if($u[1]){
				$this->pageUrl=$_SERVER['REQUEST_URI'].'&page=';
			 }else{
				$this->pageUrl=$_SERVER['REQUEST_URI'].'?page=';
			 }
		  }
	    }
	  }
	 }
	function GetLimit()
	{
	   return ($this->currentPage-1)*$this->pageSize.','.$this->pageSize;	
	}
	function GetCurrentPage()
	{
	  return $this->currentPage;	
	}
	function GetPageCount()
	{
	  return $this->totalPage;	
	} 
}