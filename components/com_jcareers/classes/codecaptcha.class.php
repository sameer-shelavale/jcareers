<?php


class CodeCaptcha{
	  
	var $formName;
    var $codeLength=6;
	var $code;
	var $noiseLevel;  // nb of background noisy characters
	var $width;
	var $height;
	var $imgDir = 'tmp/';
	var $font = 'comic.ttf';
	var $maxFontSize = 15;
	var $minFontSize = 13;
	var $fX;
	var $fY;
	var $sessionVarName="CodeCaptcha";
	var $fileName;
	var $img;
	var $imgType;
	var $useFiles=false;
	var $sessionName;
	var $sessionID;
	
	
	function CodeCaptcha($frmName="cForm", $tLen=6, $w=100, $h=30, $n=25, $iDir='tmp/', $iType='jpg', $files=false, $s='', $sn=''){
		$this->formName = $frmName;
	    $this->codeLength = $tLen;
		$this->noiseLevel = $n;  // nb of background noisy characters
		$this->width = $w;
		$this->height = $h ;
		$this->imgDir = $iDir;
		$this->font = 'comic.ttf';
		$this->sessionVarName="CodeCaptcha";
		$this->imageType = $iType;
		$this->useFiles = $files;
		if( $s != ''){
			$this->sessionID = $s;
		}
		if( $sn != ''){
			$this->sessionName = $sn;
		}
		$this->GenerateCode();
		$this->SetDirectory();
		$this->SetFilename();
      	$this->SetCode($this->fileName);
      	$this->SetFontSizeRange();
		
	}
	
	function GenerateCode( ){
	    $r = rand(0,31-$this->codeLength-1 );
		$this->code = substr( md5(time()), $r, $this->codeLength );
	}
	
	function SetCode($fName=''){
	  	
	  	if( $this->sessionName != '' ){
			session_name( $this->sessionName );    
		}
	  	
	  	if( $this->sessionID != '' ){
			session_id( $this->sessionID );    
		}
	  	
	  	$_SESSION[$this->sessionVarName][$this->formName]= array( 'code'=>$this->code, 'file'=>$fName);
		
		
	}
	
	function CheckCode($userCode, $form){
		if (!isset( $_SESSION[$this->sessionVarName] ) ) {
		    return false;
		}else{
			if( $userCode == $_SESSION[$this->sessionVarName][$form]['code']){
				return true;
			}else{
				return false;
			}
			if( $_SESSION[$this->sessionVarName][$form]['file'] != ''){
				unlink( $_SESSION[$this->sessionVarName][$form]['file'] );
			}
		}
	}
	
	function SetFontSizeRange(){
		$this->maxFontSize = $this->width / ($this->codeLength + 1);
		if( $this->maxFontSize > $this->height * 2 / 3){
			$this->maxFontSize > intval( $this->height * 2 / 3 );
		}
		$this->minFontSize = $this->maxFontSize*4/5;
		$this->fY = $this->maxFontSize + ($this->height-$this->maxFontSize)/2;
		$this->fX = $this->maxFontSize/2-2;
	}
	
	function SetFilename(){
	  	if( $this->useFiles){
			$this->fileName = $this->imgDir.md5(uniqid(rand(),1)).$this->imagetype;
		}
	}
	
	function SetDirectory(){
	  	if( $this->useFiles ){
			if (!is_dir($this->imgDir)) // check if the captch image directory exists or not
	        	mkdir($this->imgDir);   // create directory if it does not exist
	    }
	}
    
    function CreateCaptcha($noise = true) {
      $image = imagecreatetruecolor($this->width,$this->height);
      $back=ImageColorAllocate($image,intval(rand(200,255)),intval(rand(200,255)),intval(rand(200,255)));
      ImageFilledRectangle($image,0,0,$this->width,$this->height,$back);
      if ($noise) { // rand characters in background with random position, angle, color
        for ($i=0;$i<$this->noiseLevel;$i++) {
          $size=intval(rand($this->maxFontSize/2,$this->maxFontSize-2 ));
          $angle=intval(rand(0,360));
          $x=intval(rand(10,$this->width-5));
          $y=intval(rand(0,$this->height-5));
          $color=imagecolorallocate($image,intval(rand(130,224)),intval(rand(130,224)),intval(rand(130,224)));
          $text=chr(intval(rand(45,250)));
          ImageTTFText ($image,$size,$angle,$x,$y,$color,$this->font,$text);
        }
      }
      else { // random grid color
        for ($i=0;$i<$this->width;$i+=10) {
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          imageline($image,$i,0,$i,$this->height,$color);
        }
        for ($i=0;$i<$this->height;$i+=10) {
          $color=imagecolorallocate($image,intval(rand(160,224)),intval(rand(160,224)),intval(rand(160,224)));
          imageline($image,0,$i,$this->width,$i,$color);
        }
      }
      // private text to read
      for ($i=0,$x=5; $i<$this->codeLength;$i++) {
        $r=intval(rand(0,128));
        $g=intval(rand(0,128));
        $b=intval(rand(0,128));
        $color = ImageColorAllocate($image, $r,$g,$b);
        $shadow= ImageColorAllocate($image, $r+128, $g+128, $b+128);
        $size=intval(rand($this->minFontSize,$this->maxFontSize));
        $angle=intval(rand(-30,30));
        $text=strtoupper(substr($this->code,$i,1));
        ImageTTFText($image,$size,$angle,$this->fX+2,$this->fY+2,$shadow,$this->font,$text);
        ImageTTFText($image,$size,$angle,$this->fX,$this->fY,$color,$this->font,$text);
        $this->fX+=$size+2;
      }
      $this->img = $image;
      
    }
    
    function GetCaptchaFileName( $noise = true ) {
        
    	$this->CreateCaptcha($noise);
      	if ( $this->imageType == "jpg" ){
        	imagejpeg($this->img, $this->fileName, 100);
    	}else{
	    	imagepng($this->img, $this->fileName);
    	}
    	ImageDestroy($this->img);
    	return $this->fileName;
    }
    
    function OutputCaptcha( $noise = true ) {
    	
    	$this->CreateCaptcha($noise);
    	$this->fileName;
		if ($this->imageType=="jpg")
        	imagejpeg($this->img);
    	else
    		imagepng($this->img, $this->fileName);
    	ImageDestroy($this->img);
	}
}
    
    
?>