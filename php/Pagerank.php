<?php

class PageRankGrabber {

	/**
	 * Returns PageRank for specified page
	 * @param string $url 
	 * @return integer PageRank value
	 */
	public function getRank($url) {
		$pageUrl = $this->getRankUrlStingByPageUrl($url);
		$content = $this->getContentCurl($pageUrl);
		$parts   = explode(":", $content);
		return intval(@$parts[count($parts)-1]);
	}

	/**
	 * Returns content by URL with using
	 * cURL library.
	 *
	 * @param string $url 
	 * @return string Site content
	 */
	public function getContentCurl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;        
	}

	/**
	 * Returning URL where PR can be grabbed.
	 *
	 * @param string $page - Page URL
	 * @return string 
	 */
	public function getRankUrlStingByPageUrl($page) {
		$this->awesomeHash($page);
		$hash = "8" . $this->awesomeHash($page);
		$url  = "http://toolbarqueries.google.com/search?sourceid=" .
			"navclient-ff&features=Rank&client=navclient-auto-ff&";
		$url .= "ch=" . $hash . "&q=info:" . urlencode($page);
		return str_replace(' ', '', $url);        
	}

	/**
	 * Transforms integer into hexademical
	 *
	 * @param int $num 
	 * @return string 
	 */
	private function toHex8($num) {
		$vector = "0123456789abcdef";
		return $vector[($num % 256) / 16] . $vector[$num % 16];
	}

	/**
	 * Service function: encoder
	 */
	private function hexEncodeU32($num) {
		$result  = $this->toHex8($this->zerofillShift($num, 24));
		$result .= $this->toHex8($this->zerofillShift($num, 16) & 255);
		$result .= $this->toHex8($this->zerofillShift($num, 8) & 255);
		return $result . $this->toHex8($num & 255);
	}

	/**
	 * Service function: hashing
	 */
	private function awesomeHash($value)  {

		$hashSeed = "Mining PageRank is AGAINST GOOGLE'S TERMS OF SERVICE." .
			"Yes, I'm talking to you, scammer.";
		$intValue = 16909125;
		for($i = 0; $i < strlen($value); $i++ ){
			$intValue ^=
				$this->charCodeAt($hashSeed, $i % strlen($hashSeed)) ^
				$this->charCodeAt($value, $i);
			$intValue =
				$this->zerofillShift($intValue,  23) | $intValue << 9;
		}
		return $this->hexEncodeU32($intValue);
	}

	/**
	 * The charCodeAt() method returns the Unicode
	 * of the character at a specified position.
	 *
	 * @param int $value 
	 */
	private function charCodeAt($value, $position) {
		$symbol = $value[$position];
		// ord() is for ASCII!
		// Original function should work with UTF-8.
		return ord($symbol);
	}

	/**
	 * Service function: zerofil with shifing
	 * (unsigned shift right).
	 */
	private function zerofillShift($a, $b) {
		$z = hexdec(80000000);
		if ($z & $a) {
			$a = ($a >> 1);
			$a &= (~$z);
			$a |= 0x40000000;
			$a = ($a >> ($b-1));
		} else {
			$a = ($a >> $b);
		}
		return $a;
	}   
}


