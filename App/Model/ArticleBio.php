<?php
	class ArticleBio extends ArticleEntity
	{
	
		private $articleDA;
	
		function __construct()
		{
			$this->articleDA = new ArticleDA();
		}
		
		public function setDatabase($database)
		{
			$this->articleDA->setDatabase($database);
		}
		
		public function getAllBios()
		{
			$articles = $this->articleDA->getAllMemberBios();
		
			return $articles;
		}

	}
?>