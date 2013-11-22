<?php
/**
 * Contains a single Article entry for the blog
 * Features a helper method for building and returning Arrays of articles
 *
 * @author Stephen McMahon <stephentmcm@gmail.com>
 * @author Alex Robinson
 * @author Tara Stevenson <tara.stevenson@hotmail.com>
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
	/*
	* When this function is called htmlspecialchars() will remove the 
	* meaning of any html code and convert it to a flat string
	*
	* @param 	$text	String of text
	* @return	$char	String sanitised
	*/
	function html($text)
	{	
		$char = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
		return $char;
	}
	
	/*
	* When this function is called it will do the opposite of 
	* htmlspecialchars() and retun meaning to any html characters 
	*
	* @param 	$text	String of text
	* @return	$char	String sanitised
	*/
	function htmlOut($text)
	{
		$char = htmlspecialchars_decode($text, ENT_NOQUOTES);
		return $char;
	}
	
	/*
	* When this function is called it will return all the projects 
	* we have worked on this is classed by the caragory of 2
	*
	* @return	$sql		String array
	* @throws	Exception
	*/
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
	
	/*
	* The query will search for the project data and the 
	* all people who worked on it via the article id
	*
	* @param 	$articleId	string number of an article
	* @return	$sql		String array
	* @throws	Exception
	*/
	public function getIndividualProjectData($id){
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

	/*
	* Gets all the file data associated with an individual project
	* The title of the project is the tag for each file. This is how 
	* it file how they're connected
	*
	* @param 	$titleName	string title of an article is the 
	* @return	$sql		String array
	* @throws	Exception
	*/
	public function getIndividualProjectFiles($titleName){
		try {
			$sql = $this->database->query("SELECT a.title, a.content, a.date FROM article a WHERE category = '3' AND tag = '$titleName';")->fetchAll();
			return $sql;
		
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}
	
	/**
	* Turns the Individual Project data into objects
	*
	* @param String $articleId
	* @param String $title
	* @param String $content
	* @param String $tag
	* @param String $date
	* @param String $firstName
	* @param String $firstName
	* @return Object|String Return this object or an error string
	*/
	public function getIndividualProjectObject($articleId, $title, $content, $tag, $date, $firstName, $firstName)
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
	
	/**
	* Turns the article data into objects
	*
	* @param String $articleId
	* @param String $category
	* @param String $title
	* @param String $content
	* @param String $tag
	* @param String $status
	* @return Object|String Return this object or an error string
	*/
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
	
	/**
	* Gets all the the article data that are either Bio(1) or Projects(2)
	* 
	* @return	$sql	String array
	* @throws	Exception
	*/
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
	
	/**
	* Gets all the the article data where the id is passed thrugh
	* 
	* @param	$id		String article number
	* @return	$sql	String array
	* @throws	Exception
	*/
	public function getProjectDataToEdit($id){
		try {
			$sql = $this->database->query("SELECT * FROM article WHERE articleId = '$id';")->fetchAll();
			return $sql;
			
		} catch (Exception $e) {
		
			throw new Exception('Database error:', 0, $e);
			return false;
		}
	}
	
	/**
	* Saves the data sent through the param into the database
	* 
	* @param	$formData	String array 
	* @return	nil
	* @throws	Exception
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

	/**
	* Validates if the file type is accepted and changed the file name 
	* if spaces are found. If ok moves the file to the media folder 
	* 
	* @param	$file		String array 
	* @return	$filename	String
	* @throws	Exception
	*/
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
		
		//$destination = http://mercury.ict.swin.edu.au/ccpmg501a/ccpmg501a_18/website/Media/";
		$destination = "Media/";
		
		//no limit on size
		$typeOK = false;
		$permitted = array('image/gif','image/jpeg', 'image/pjpeg', 'image/png', 'application/pdf', 'application/zip', 'application/x-zip', 'application/x-zip-compressed', 'application/x-rar-compressed', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain');
		foreach ($permitted as $type) 
		{
			if ($type == $file['file']['type']) 
			{
				$typeOK = true;
				break;
			}
		}//end foreach
		
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
			}//end statement
		}
		else{
			return;
		}//end statement
		return $fileName;
	}
	
	/*
	* saves the file that has been uploaded into the database then
	* creates a new memberarticle entry
	*
	*/
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
		}//end statment
		
		$lastArticleId = $this->database->lastInsertId();
		
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
		}//end statment
	}// end function
	
	/**
	 * Gets all possible Task status'
	 * @param nil
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
