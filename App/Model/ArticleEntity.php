<?php
/**
 * Contains a single Article entry for the blog
 * Features a helper method for building and returning Arrays of articles
 *
 * @author Stephen McMahon <stephentmcm@gmail.com>
 * @author Alex Robinson
 */
class ArticleEntity
{
    protected $database;
    //Holds the articles data in an Array most easily accessed through the getters
    protected $article;
    
    /**
     * Article attributes
     */ 		
	private $content; 
	private $tag; 
	private $firstName; 
	private $lastName; 
	private $articleId;
	private $date;
	private $title;

    /**
     * Sets up an empty Article object
     *
     * @param PDO $database Needs a PDO database connection
     */
    public function __construct(PDO $database)
    {
		$this->database = $database;
    }//end construct

    public function setDatabase(PDO $database)
    {
        $this->database = $database;
    }

    /**
     * Loads an existing article from the database
     *
     * @param Int $articleId the id number of the article
     *
     * @return Boolean   True for loaded false for DB connection error
     * @throws Exception PDO expection
     */
    /*public function load($id)
    {
        return('Error load() not implemented');
    }//end loadArticle
    */
    /**
     * Stores the input data in the article object used for creating new article
     *
     * @param Array $article Must contain `title`, `status`, `ACL`, `content`,
     * `username`
     *
     * @return Boolean   True on sucess else false
     * @throws Exception Throws Database and custom errors
     */
    public function create($data = array())
    {
        return('Error create() not implemented');
    }

    /**
     * Uses the super cool on duplicate key update MySQL function to update an existing article
     * @return Boolean   True on sucess else false
     * @throws Exception PDO expections on Database errors
     */
    public function save()
    {
        return('Error save() not implemented');
    }//end save

    /**
     * Deletes the current article from the database
     *
     * @return Boolean   Sucess or failure
     * @throws Exception Database exceptions if query fails
     */
    public function delete()
    {
        return('Error load() not implemented');
    }

    /**
     * Quick function to shift the keys from 'articleid' to ':articleid' etc
     * @return Array The article array with keys in PDO named parameter form
     * @link www.php.net/maunal/PDO Info about binded parameters
     */
    protected function articleNamedParams()
    {
        foreach ($this->article as $key => $value) {
            $key = ':'.$key;
            $binded[$key] = $value;
        }

        return($binded);
    }//end articleNamedParams

	/*****************************************************************************************************/
	
	function html($text)
	{	
		$char = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		return $char;
	}
	
	function htmlOut($text)
	{
		$char = htmlspecialchars_decode($text, ENT_NOQUOTES);
		return $char;
	}
	
	public function getProjectData(){
		try {
			$sql = $this->database->query("SELECT a.title, a.articleId, a.content, a.tag, a.date, m.firstName, m.lastName FROM article a 
											LEFT JOIN memberarticle ma ON a.articleId = ma.articleId 
											LEFT JOIN member m ON ma.memberId = m.memberId 
											WHERE a.category = 2 
											ORDER BY a.articleId, m.firstName;"
										)->fetchAll();
			return $sql;
		
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}
	
/**/	public function getIndividualProjectData($id){
		try {
			$sql = $this->database->query("SELECT a.title, a.articleId, a.content, a.tag, a.date, m.firstName, m.lastName FROM article a 
											LEFT JOIN memberarticle ma ON a.articleId = ma.articleId 
											LEFT JOIN member m ON ma.memberId = m.memberId 
											WHERE a.category = 2 
											AND a.articleId = '$id';")->fetchAll();
			return $sql;
		
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}

/**/	public function getIndividualProjectFiles($titleName){
		try {
			$sql = $this->database->query("SELECT a.title, a.content, a.date FROM article a WHERE category = '3' AND tag = '$titleName';")->fetchAll();
			return $sql;
		
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}
	
	public function getIndividualProjectObject($articleId, $title, $content, $tag, $date, $firstName, $lastName)
	{
		$obj = new ArticleEntity($this->database);
		
		$obj->setArticleId($articleId);
		$obj->setTitle($title);
		$obj->setContent($content);
		$obj->setTag($tag);
		$obj->setDate($date);
		$obj->setAuthorFirstName($firstName);
		$obj->setAuthorLastName($lastName);
		return($obj);
	}
	
	public function getArticleObject($articleId, $category, $title, $content, $tag, $date, $status)
	{
		$obj = new ArticleEntity($this->database);
		
		$obj->setArticleId($articleId);
		$obj->setCategory($category);
		$obj->setTitle($title);
		$obj->setContent($content);
		$obj->setTag($tag);
		$obj->setDate($date);
		$obj->setStatus($status);
		return($obj);
	}
	
	public function getAllArticles(){
		try {
			$sql = $this->database->prepare("SELECT * FROM `article` WHERE category = '1' OR category = '2' ORDER BY category, title;");
			$sql->execute();
			$sql = $sql->fetchAll();
			return $sql;
		
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}
	
/**/	public function getProjectDataToEdit($id){
		try {
			$sql = $this->database->query("SELECT * FROM article WHERE articleId = '$id';")->fetchAll();
			return $sql;
			
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}
	
	/* php manual
	function downloadFile($file) 
	{ 
		$filename = $file;
		$file_path = realpath("Media/".$filename);
	
		if(file_exists($file_path)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
           // header('Content-Disposition: attachment; filename='.basename($file_path));
            header('Content-Disposition: attachment; filename='.urlencode($file_path));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            ob_clean();
            flush();
            readfile($file_path);
            exit;
        }
    }
	*/
	
	public function saveChanges($formData)
	{
		try
		{
			$sql = "UPDATE `article` SET 
					`title`		= :title,
					`content`	= :content,
					`category`	= :category,
					`tag`		= :tag,
					`date`		= :date,
					`status`		= :status
					WHERE `articleId` = :articleId";
			$data = $this->database->prepare($sql);
			$data->bindValue(':title', $formData['title']);
			$data->bindValue(':content', $formData['content']);
			$data->bindValue(':category', $formData['category']);
			$data->bindValue(':tag', $formData['tag']);
			$data->bindValue(':date', $formData['date']);
			$data->bindValue(':status', $formData['status']);
			$data->bindValue(':articleId', $formData['articleId']);
			$result = $data->execute();
			
			return;
		}
		catch (PDOException $e) {
			echo $e->getMessage();
			return;
		}
	}

	public function uploadFile($file)
	{
		/*
		* IMAGE ERRORS																
		* 0 Upload successful														
		* 1 File exceeds maximum upload size specified in php.ini (default 2MB)		
		* 2 File exceeds size specified by MAX_FILE_SIZE							
		* 3 File only partially uploaded											
		* 4 Form submitted with no file specified									
		* 6 No temporary folder														
		* 7 Cannot write file to disk												
		* 8 Upload stopped by an unspecified PHP extension							
		*/
		
		
		//$destination = SITE_ROOT."Media/";
		$destination = "Media/";
		
		//any size
		$typeOK = false;
		$permitted = array('image/gif','image/jpeg', 'image/pjpeg', 'image/png', 'application/pdf', 'application/zip', 'application/x-zip', 'application/x-zip-compressed', 'application/x-rar-compressed', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain');
		foreach ($permitted as $type) 
		{
			if ($type == $file['file']['type']) 
			{
				$typeOK = true;
				break;
			}
		}
		
		if ($typeOK)
		{
			$fileName = str_replace(' ','_',$file['file']['name']);
			if ($file['file']['error'] !== UPLOAD_ERR_OK) {
				return;
			}
			else
			{
				$success = move_uploaded_file($file['file']['tmp_name'], $destination.$fileName);
				if($success == false)
				{
					return;
				}
			}
		}
		else{
			return;
		}
		return $fileName;
	}
	
	public function createArticle($post, $fileName)
	{
		$date = date('Y-m-d', time());
		
		try
		{
			$sql = "INSERT INTO `article` SET 
					`title`		= :title,
					`content`	= :content,
					`category`	= :category,
					`tag`		= :tag,
					`date`		= :date,
					`status`	= :status";
			$data = $this->database->prepare($sql);
			$data->bindValue(':title', $this->html($post['title']));
			$data->bindValue(':content', $this->html($fileName));
			$data->bindValue(':category', '3');
			$data->bindValue(':tag', $this->html($post['projectName']));
			$data->bindValue(':date', $date);
			$data->bindValue(':status', 'Completed');
			$result = $data->execute();

		}
		catch (PDOException $e) {
			echo $e->getMessage();
			return;
		}
		
		$lastArticleId = $this->database->lastInsertId();
		echo $lastArticleId;
		
		try
		{
			$sql = "INSERT INTO `memberarticle` SET 
					`articleId`	= :articleId,
					`memberId`	= :memberId";
			$data = $this->database->prepare($sql);
			$data->bindValue(':articleId', $this->html($lastArticleId));
			$data->bindValue(':memberId', $this->html($post['memberId']));
			$result = $data->execute();

			if($result == true)
			{	
				return true;
			}
		}
		catch (PDOException $e) {
			echo $e->getMessage();
			return;
		}
		
	}
	
	/**
	 * Gets all possible Task status'
	 * @author Alex
	 * @author Tara
	 * @return array
	 */
	public function getEnumStatus()
	{
		$statement = "SHOW COLUMNS FROM `article` WHERE FIELD = 'status'";

		try{
			$query = $this->database->prepare($statement);

			$query->execute();
			$returnArray = array();
			/*
			 * Inspiration for code http://akinas.com/pages/en/blog/mysql_enum/
			* Regex taken straight from website
			* 19/04/2013
			*/
			if($query->rowCount())
			{
				foreach($query as $row){
					$enumValues = $row[1];
				}
					
				$regex = "/'(.*?)'/";
				preg_match_all( $regex , $enumValues, $enum_array );
				$enum_fields = $enum_array[1];

				foreach($enum_fields as $row){
					array_push($returnArray, $row);
				}
				return $returnArray;
			}else
			{
				return $this->createError("No status' found");
			}
		} 
		catch(PDOException $e) {
			return $this->createError($e);
		} //end of try/catch statement;
	}
	/****************************************************************************************************************************************************/
	
    //*********SETTERS----------------------
    public function setStatus($param)
    {
        $this->article['status'] = $param;
    }

    public function setDate($param)
    {
        $this->article['date'] = $param;
    }

    public function setContent($param)
    {
        $this->article['content'] = $param;
    }

    public function setTag($param)
    {
        $this->article['tag'] = $param;
    }

    public function setCategory($param)
    {
        $this->article['category'] = $param;
    }

	public function setTitle($param)
    {
        $this->article['title'] = $param;
    }
	
	public function setArticleId($param)
    {
        $this->article['articleId'] = $param;
    }
	
	public function setAuthorFirstName($param)
    {
        $this->article['firstName'] = $param;
    }
	public function setAuthorLastName($param)
    {
        $this->article['lastName'] = $param;
    }
    //*********GETTERS--------------
	public function getAuthorFirstName()
    {
        return($this->article['firstName']);
    }
	
	public function getAuthorLastName()
    {
        return($this->article['lastName']);
    }
	
    public function getStatus()
    {
        return($this->article['status']);
    }

    public function getDate()
    {
        return($this->article['date']);
    }

    public function getContent()
    {
        return($this->article['content']);
    }

	public function getTitle()
    {
        return($this->article['title']);
    }
	
    public function getTag()
    {
        return($this->article['tag']);
    }

    public function getCategory()
    {
        return($this->article['category']);
    }
	
	public function getArticleId()
    {
		return($this->article['articleId']);
    }
}//end article class
