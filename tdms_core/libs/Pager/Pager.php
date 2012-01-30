<?
class Pager{
	protected $totalPageNum;	// 전체 페이지수
	protected $PagePerRow;		// 페이지당 표시할 줄수
	protected $limitPagenum;	// 표시할 페이지 번호
	protected $startnum;		// 페이지 시작 번호
	protected $endnum;			// 페이지 끝 번호
	protected $PageG;
	protected $Page;
	protected $allpageG;
	protected $rowvalue;
	protected $strLink;

	protected $iterPrevPage;
	protected $iterNextPage;

	function Pager($totalnum, $Page, $PageG){
		$this->PagePerRow = 5;		
		$this->limitPagenum =5;	
		$this->setUpPage($Page, $PageG); //페이지를 초기화한다.
		$this->rowvalue = $totalnum;
	}

	public function PageCal(){
		$this->totalPageNum = ceil($this->rowvalue / $this->PagePerRow);
		$this->startnum = ($this->Page-1) * $this->PagePerRow;
		$this->allpageG = ceil($this->totalPageNum/$this->limitPagenum);
	}

	protected function setUpPage($Page, $PageG){
		if(!$PageG){
			$this->setPageG(1);
		}else{
			$this->setPageG($PageG);
		}

		if(!$Page){
			$this->setPage(1);
		}else{
			$this->setPage($Page);
		}

	}
	public function getStartNum(){
		return $this->startnum;
	}
	public function setPage($value){
		$this->Page = $value;
	}

	public function setPageG($value){
		$this->PageG = $value;
	}

	public function getPage(){
		return $this->Page; 
	}

	public function getPageG(){
		return $this->PageG;
	}

		// 총 페이지수 얻어오기
	public function getTotalPageNum(){
		return $this->totalPageNum;
	}

	public function setLink($arrValue){
		while(list($key, $value) = each($arrValue)){
			$this->strLink .= $key.'='.$value.'&';
		}
		//$this->strLink = substr($this->strLink, 0, -1);
	}

	public function viewMainPageNumber($link){
		// 페이지 출력

		$this->startPage = ($this->PageG-1) * $this->limitPagenum + 1;
		$this->endPage = $this->startPage + $this->limitPagenum - 1;

		if($this->endPage >= $this->totalPageNum){
			$this->endPage = $this->totalPageNum;
		}

		for($i=$this->startPage; $i<=$this->endPage; $i++){
			
			if($this->Page == $i){
				$printPageNumber="<b><font style='color:blue'>".$i."</font></b>";
			}else{
				$printPageNumber=$i;
			}
			$mainPageView .= "[<a href=".$link."Page=".$i."&PageG=".$this->PageG.">".$printPageNumber."</a>]";
		}
		return $mainPageView;
	}

	public function setIteration($iterPrevPage, $iterNextPage){
		$this->iterPrevPage = $iterPrevPage;
		$this->iterNextPage = $iterNextPage;
	}


	public function viewNextPageNumber($link){
		// 다음페이지
		if($this->PageG >= $this->allpageG){
			echo "";
		}else{
			$nextpage = ($this->PageG) * $this->limitPagenum+1;
		

		$nextPageView = "[<a href=".$link."Page=".$nextpage."&PageG=".($this->PageG+1).">".iterNextPage."</a>]";
		
		return $nextPageView;
		}
	}


	public function viewPrevPageNumber($link){
		// 이전 페이지
		if($this->PageG == 1){
			echo "";
		}else{
			if(($this->PageG-1) == 1){
				$prevpage=1;
			}else{
				$prevpage=($this->PageG-2) * $this->limitPagenum+1;
			}
		
		$prevPageView = "[<a href=".$link."&Page=".$prevpage."&PageG=".($this->PageG-1).">".$this->iterPrevPage."</a>]";
		
		return $prevPageView;
		}
	}



	/**
	 *  
	 * viewPageNumber()
	 * 페이지를 표시한다.
	 * public
	 * 
	 * @param $value : 수정할 값
	 *
	 * @return nothing
	*/
	public function viewPageNumber($PageLink){
		$output1 = $this->viewPrevPageNumber($PageLink);
		$output2 = $this->viewMainPageNumber($PageLink);
		$output3 = $this->viewNextPageNumber($PageLink);

		return $output1.$output2.$output3;
	}

	/**
	 *  
	 * setPagePerRow()
	 * 페이지당 표시할 줄수 설정
	 * public
	 * 
	 * @param $value : 수정할 값
	 *
	 * @return nothing
	*/
	public function setPagePerRow($value){
		$this->PagePerRow = $value;
	}

	/**
	 *  
	 * getPagePerRow()
	 * 페이지당 표시할 줄수 설정
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $PagePerRow : 반환되는 값
	*/
	public function getPagePerRow(){
		return $this->PagePerRow;
	}
	
	/**
	 *  
	 * setlimitPagenum()
	 * 화면에 표시할 페이지 번호 갯수 설정
	 * public
	 * 
	 * @param $value : 수정할 값
	 *
	 * @return nothing
	*/
	public function setlimitPagenum($value){
		$this->limitPagenum = $value;
	}

	/**
	 *  
	 * getlimitPagenum()
	 * 화면에 표시할 페이지 번호 갯수 알아오기
	 * public
	 * 
	 * @param nothing
	 *
	 * @return $limitPagenum : 반환되는 값
	*/
	public function getlimitPagenum(){
		return $this->limitPagenum;
	}

}

?>