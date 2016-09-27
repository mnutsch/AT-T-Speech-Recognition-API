<?php
	
	function convertspeechtotext()
	{
		session_start();
		require __DIR__ . '/config.php';
		require_once __DIR__ . '/src/Sample/SpeechService.php';
		require_once __DIR__ . '/lib/Util/Util.php';
		$speechService = new SpeechService();
		$response = $speechService->speechToText();

		$error = $speechService->getError();
		if ($error) 
		{
			echo htmlspecialchars($error); 
		} 
		else if ($response) 
		{
			echo 'ResponseID ';
			echo $response->getResponseId(); 
			echo "<br>";
			echo 'Status '; 
			echo $response->getStatus();
			echo "<br>";
			$nbest = $response->getNBest();
      
    		if ($nbest != NULL) 
     	 	{
				echo 'Hypothesis '; 
				echo $nbest->getHypothesis();
				echo "<br>";
				echo 'LanguageId ';
				echo $nbest->getLanguageId();
				echo "<br>";
				echo 'Confidence '; 
				echo $nbest->getConfidence(); 
				echo "<br>";
				echo 'Grade '; 
				echo $nbest->getGrade(); 
				echo "<br>";
				echo 'ResultText '; 
				echo $nbest->getResultText(); 
				echo "<br>";
				echo 'Words ';
				echo json_encode($nbest->getWords()); 
				echo "<br>";
				echo 'WordScores '; 
				echo json_encode($nbest->getWordScores());
			} 
		}
	}
	
	
	$allowedExts = array("wav", "mp3", "wma", "amr", "ogg");

	if(isset( $_FILES["file"]["name"]))
	{

		if (file_exists($_FILES["file"]["name"])) 
		{
   		unlink($_FILES["file"]["name"]);
		} 

		$extension = end(explode(".", $_FILES["file"]["name"]));
		if (($_FILES["file"]["size"] < 2000000) && in_array($extension, $allowedExts))
  		{
  			if ($_FILES["file"]["error"] > 0)
 	   	{
 	   		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
 	   	}
 	 		else
 	   	{
				echo "File Information:<br />";
 	 	  		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
  		  		echo "Type: " . $_FILES["file"]["type"] . "<br>";
  		  		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
   	 		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";

     		 	if (file_exists($_FILES["file"]["name"]))
     		 	{
     		 		echo $_FILES["file"]["name"] . " already exists. ";
     		 	}
    			else
      		{
      			move_uploaded_file($_FILES["file"]["tmp_name"], $_FILES["file"]["name"]);
      			echo "Stored in: " . $_FILES["file"]["name"];
      			
      			echo "<input type='hidden' name='audio_file' value='" . $_FILES["file"]["name"] . "'>";
      			

      			
      			convertspeechtotext();
      			
      			
      		}
    		}
		}
	}
	else
	{
		echo "No file was received!";
	}
	
	
	
			
			
			
			
			
			
			
		
	
?>