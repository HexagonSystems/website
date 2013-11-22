<?php

	/**
	 * This file is the model used to access bios(biographies) for the about page
	 * The construct() function creates a new data access to be used
	 * The setDatabase() function is used to select which database is going to be used
	 * The getAllBios() function is used to get all team member bios
	 *
	 * @author Sam Imeneo
	 */


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