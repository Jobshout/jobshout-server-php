<?php 
//require_once("include/lib.inc.php");

class Metakeywords {
	
	/** 
    * This array of words might be stored in a database for ease of update-ability or being more accessible
    */ 	
	private $stopWords = array("a", "about", "above", "above", "across", 
		"after", "afterwards", "again", "against", "all", "almost", "alone", 
		"along", "already", "also", "although", "always", "am", "among", 
		"amongst", "amoungst", "amount", "an", "and", "another", "any", "anyhow", 
		"anyone", "anything", "anyway", "anywhere", "are", "around", "as", "at", "b",
		"back", "be", "became", "because", "become", "becomes", "becoming", 
		"been", "before", "beforehand", "behind", "being", "below", "beside", 
		"besides", "between", "beyond", "bill", "both", "bottom", "but", "by","c" ,
		"call", "can", "cannot", "cant", "co", "com", "con", "could", "couldn't", "d" ,
		"de", "detail", "do", "done", "down", "due", "during", "each", "e",
		"eg", "eight", "either", "eleven", "else", "elsewhere", "empty", "enough", 
		"etc", "even", "ever", "every", "everyone", "everything", "everywhere", 
		"except","f", "few", "fifteen", "fify", "fill", "find", "first", 
		"five", "for", "former", "formerly", "forty", "found", "four", "from", 
		"front", "full", "further", "g","get", "give", "go", "had", "has", "hasnt", "h",
		"have", "he", "hence", "her", "here", "hereafter", "hereby", "herein", 
		"hereupon", "hers", "herself", "him", "himself", "his", "how", "however", 
		"hundred", "i", "ie", "if", "in", "inc", "indeed", "interest", "into", "is", "j","k","l","m", "n", "o","p","q","r","w","u","v","x","y","z",
		"it", "its", "itself", "keep", "last", "latter", "latterly", "least", 
		"less", "ltd", "made", "many", "may", "me", "meanwhile", "might", "mill", 
		"mine", "more", "moreover", "most", "mostly", "move", "much", "must", 
		"my", "myself", "name", "namely", "neither", "never", "nevertheless", 
		"next", "nine", "no", "nobody", "none", "noone", "nor", "not", "nothing", 
		"now", "nowhere", "of", "off", "often", "on", "once", "one", "only", 
		"onto", "or", "other", "others", "otherwise", "our", "ours", "ourselves", 
		"out", "over", "own", "part", "per", "perhaps", "please", "put", "rather", "rd", 
		"re", "same", "see", "seem", "seemed", "seeming", "seems", "serious", 
		"several", "she", "should", "show", "side", "since", "sincere", "six", 
		"sixty", "so", "some", "somehow", "someone", "something", "sometime", 
		"sometimes", "somewhere", "still", "such", "take", "ten", 
		"than", "that", "the", "their", "them", "themselves", "then", "thence", 
		"there", "thereafter", "thereby", "therefore", "therein", "thereupon", 
		"these", "they", "thin", "third", "this", "those", "though", 
		"three", "through", "throughout", "thru", "thus", "to", "together", "too", 
		"top", "toward", "towards", "twelve", "twenty", "two", "un", "under", 
		"until", "up", "upon", "us", "very", "via", "was", "we", "well", "were", 
		"what", "whatever", "when", "whence", "whenever", "where", "whereafter", 
		"whereas", "whereby", "wherein", "whereupon", "wherever", "whether", 
		"which", "while", "whither", "who", "whoever", "whole", "whom", "whose", 
		"why", "will", "with", "within", "without", "would", "yet", "you", "your", 
		"yours", "yourself", "yourselves", "ll", "t", "s", "d", "ve", "m", "asap","ndash", "&pound;",

		// special oddities
		"-", "--", '+', "http", "www", "org","&nbsp;", "&nbsp;-"
	); 
   
   /** 
    * Get a string of keywords
    * @return string 
    * @param string $text 
    * @param int $nbrWords Number of words to return, default = 200 
    */ 
	public function get($db, $text, $nbrWords = 200) { 
		global $db;
		$text = strtolower(strip_tags($text));
		$keywords = $this->getKeywords($db, $text, $nbrWords); 
		return implode(",", $keywords);
	}	
	
   /** 
    * Get array of keywords
    * @return array 
    * @param string $text 
    * @param int $nbrWords Number of words to return, default = 200 
    */ 
    
    private function getKeywords($db, $db_name, $text, $nbrWords = 200) { 
		global $db;
		$text = preg_replace('/\'*\b/',' ',$text); // both contractions and possessives
		$words = str_word_count($text, 1); 
		
		$find_words = "'". implode("', '", $words) ."'";	
		
		$result_words=array();
		if($phrase_Result=$db->get_results("select * from `key_phrases` WHERE phrase in ($find_words) ORDER BY `phrase` ASC ")){
			foreach($phrase_Result as $row){
				$result_words[]=$row->synonym;
				$result_words[]=$row->phrase;
			}
		}
		if($synonymResult=$db->get_results("select * from `key_phrases` WHERE synonym in ($find_words) ORDER BY `synonym` ASC ")){
			foreach($synonymResult as $row){
				$result_words[]=$row->phrase;
				$result_words[]=$row->synonym;
			}
		}
		
		$db->select('dictionary');
		$remainingWords = array_diff($words, $result_words); 
		$dictionary_check = "'". implode("', '", $remainingWords) ."'";	
		if($dic_result= $db->get_results("select word from `words` WHERE word in ($dictionary_check) ORDER BY `word` ASC ")){
			foreach($dic_result as $row){
				$result_words[]=$row->word;
			}
		}
		
		$db->select($db_name);
		$remaining_words = array_diff($words, $result_words); 

		$check_towns_cities_ = "'". implode("', '", $remaining_words) ."'";	
		
		if($town_result=$db->get_results("select name from `uk_towns_cities` WHERE name in ($check_towns_cities_) ORDER BY `name` ASC ")){
			foreach($town_result as $row){
				$result_words[]=$row->name;
			}
		}
		if($country_result=$db->get_results("select country from `uk_towns_cities` WHERE country in ($check_towns_cities_) ORDER BY `country` ASC ")){
			foreach($country_result as $row){
				$result_words[]=$row->name;
			}
		}
		
		$result_words = array_unique($result_words);
		array_walk($result_words, array( $this, 'filter' ));
		$result_words = array_diff($result_words, $this->stopWords);
		
		$wordCount = array_count_values($result_words);
		
		arsort($wordCount); 
		$wordCount = array_slice($wordCount, 0, $nbrWords); 
		
		return array_keys($wordCount); 
	} 
	
	private function getKeywordsold($db, $db_name, $text, $nbrWords = 200) { 
		global $db;
		
		$filteredText= $this->filterKeywords($db, $db_name, $text);
		$result_words = preg_split("/[\s,]+/", $filteredText);
				
		$wordCount = array_count_values($result_words);
		
		arsort($wordCount); 
		$wordCount = array_slice($wordCount, 0, $nbrWords); 
		
		return array_keys($wordCount); 
	} 
   	  	
   	public function filterKeywords($db, $db_name, $text) { 
		global $db;
		$text = str_replace("/"," ",$text);
		$text = str_replace("'","",$text);
		$text = str_replace("-","",$text);
		$text = strtolower(strip_tags($text));
		$words = preg_split("/[\s,]+/", $text);
	
		$words = $this->less_than_one($words);
		$find_words = "'". implode("','", $words) ."'";	
		
		$result_words=array();
		
		if($phrase_Result=$db->get_results("select * from `key_phrases` WHERE phrase in ($find_words) ORDER BY `phrase` ASC ")){
			foreach($phrase_Result as $row){
				$result_words[]=$row->synonym;
				$result_words[]=$row->phrase;
			}
		}
		if($synonymResult=$db->get_results("select * from `key_phrases` WHERE synonym in ($find_words) ORDER BY `synonym` ASC ")){
			foreach($synonymResult as $row){
				$result_words[]=$row->phrase;
				$result_words[]=$row->synonym;
			}
		}
		
		$result_words=$words;
		/**$remainingWords = array_diff($words, $result_words); 
		$dictionary_check = "'". implode("', '", $remainingWords) ."'";	
		
		$db->select('dictionary');
		if($dic_result= $db->get_results("select word from `words` WHERE word in ($dictionary_check) ORDER BY `word` ASC ")){
			foreach($dic_result as $row){
				$result_words[]=$row->word;
			}
		}
		
		$db->select($db_name);
		$remaining_words = array_diff($words, $result_words); 

		$check_towns_cities_ = "'". implode("', '", $remaining_words) ."'";	
		
		if($town_result=$db->get_results("select name from `uk_towns_cities` WHERE name in ($check_towns_cities_) ORDER BY `name` ASC ")){
			foreach($town_result as $row){
				$result_words[]=$row->name;
			}
		}
		if($country_result=$db->get_results("select country from `uk_towns_cities` WHERE country in ($check_towns_cities_) ORDER BY `country` ASC ")){
			foreach($country_result as $row){
				$result_words[]=$row->name;
			}
		}
		**/
		
		$result_words = array_unique($result_words);
		
		array_walk($result_words, array( $this, 'filter' ));
		$result_words = array_diff($result_words, $this->stopWords); 
		$result_words = array_map('trim', $result_words);
		return implode(", ", $result_words);
	} 
	
  	public function removeStopWords($wordsArr){
  		return array_diff($wordsArr, $this->stopWords); 
  	}
  	 
  	public function uniqueWords($text){
  		$text = str_replace("/"," ",$text);
  		$text = strtolower(strip_tags($text));
  		
		$words = preg_split("/[,]+/", $text);
		$words = array_map('trim', $words);
  		$result_words= array_unique($words);
  		return implode(", ", $result_words);
  	}
  		
	private function filter(&$val, $key) { 
      $val = strtolower($val); 
	} 
	
	private function less_than_one($arrayVal) { 
		$counter=0;
		foreach ($arrayVal as $value) {
   			if (strlen($value) <=1) {
				unset($arrayVal[$counter]);
   			}
 			$counter++;
		}
		return $arrayVal;
	} 
	
}
?>