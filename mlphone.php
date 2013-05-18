<?php

/*
	MLphone
	http://nadh.in/code/mlphone

	MLphone (like Metaphone for English), is a phonetic algorithm for
	indexing Malayalam words by their pronounciation. The algorithm 
	produces three phonetic keys for an input Malayalam word, where:

	key0 =	a broad phonetic hash comparable to a Metaphone key that
			doesn't account for hard sounds (ഷ, ണ..) or phonetic modifiers

	key1 =	is a slightly more inclusive hash that accounts for hard sounds

	key2 =	highly inclusive and narrow hash that accounts for hard sounds
			and phonetic modifiers

	Usage:
	print_r( MLphone::compute($word) );

	This work is licensed under GPL v2
	
	--
	Kailash Nadh, November 2012
	http://nadh.in
*/

class MLphone {

	private static $_vowels = array(
		"അ" => "A", "ആ" => "A", "ഇ" => "I", "ഈ" => "I", "ഉ" => "U", "ഊ" => "U", "ഋ" => "R",
		"എ" => "E", "ഏ" => "E", "ഐ" => "AI", "ഒ" => "O", "ഓ" => "O", "ഔ" => "O"
	);

	private static $_consonants = array(
		"ക" => "K", "ഖ" => "K", "ഗ" => "K", "ഘ" => "K", "ങ" => "NG",
		"ച" => "C", "ഛ" => "C", "ജ" => "J", "ഝ" => "J", "ഞ" => "NJ",
		"ട" => "T", "ഠ" => "T", "ഡ" => "T", "ഢ" => "T", "ണ" => "N1",
		"ത" => "0", "ഥ" => "0", "ദ" => "0", "ധ" => "0", "ന" => "N",
		"പ" => "P", "ഫ" => "F", "ബ" => "B", "ഭ" => "B", "മ" => "M",
		"യ" => "Y", "ര" => "R", "ല" => "L", "വ" => "V",
		"ശ" => "S1", "ഷ" => "S1", "സ" => "S","ഹ" => "H",
		"ള" => "L1", "ഴ" => "Z", "റ" => "R1"
	);

	private static $_chillus = array(
		"ൽ" => "L", "ൾ" => "L1", "ൺ" => "N1",
		"ൻ" => "N", "ർ" => "R1", "ൿ" => "K"
	);

	private static $_compounds = array(
		"ക്ക" => "K2", "ഗ്ഗ" => "K", "ങ്ങ" => "NG",
		"ച്ച" => "C2", "ജ്ജ" => "J", "ഞ്ഞ" => "NJ",
		"ട്ട" => "T2", "ണ്ണ" => "N2",
		"ത്ത" => "0", "ദ്ദ" => "D", "ദ്ധ" => "D", "ന്ന" => "NN",
		"ന്ത" => "N0", "ങ്ക" => "NK", "ണ്ട" => "N1T", "ബ്ബ" => "B",
		"പ്പ" => "P2", "മ്മ" => "M2",
		"യ്യ" => "Y", "ല്ല" => "L2", "വ്വ" => "V", "ശ്ശ" => "S1", "സ്സ" => "S",
		"ള്ള" => "L12",
		"ഞ്ച" => "NC", "ക്ഷ" => "KS1", "മ്പ" => "MP",
		"റ്റ" => "T", "ന്റ" => "NT", "ന്ത" => "N0",
		"്രി" => "R",
		"്രു" => "R",
	);

	private static $_modifiers = array(
		"ാ" => "", "ഃ" => "", "്" => "", "ൃ" => "R",
		"ം" => 3, "ി" => 4, "ീ" => 4, "ു" => 5, "ൂ" => 5, "െ" => 6, "േ" => 6,
		"ൈ" => 7, "ൊ" => 8, "ോ" => 8, "ൌ" => 9, "ൗ" => 9
	);

	// ______ compute hashes
	public static function compute($input) {

		// key2 accounts for hard and modified sounds
		$key2 = self::_process($input);

		// key1 loses numeric modifiers that denote phonetic modifiers (except for anuswaram)
		$key1 = preg_replace('/[2,4-9]/', '', $key2);

		// key0 loses numeric modifiers that denote hard sounds, doubled sounds, and phonetic modifiers (except for anuswaram)
		$key0 = preg_replace('/[1,2,4-9]/', '', $key2);

		return array($key0, $key1, $key2);
	}

	// ______ do everything!
	private static function _process($input) {
		// remove all non-malayalam characters
		$input = trim(preg_replace("/[^\p{Malayalam}]/u", '', $input));

		// all character replacements are grouped between { and } to maintain
		// separatability till the final step

		// replace and group modified compounds
		$input = self::_replaceModifiedGlyphs(self::$_compounds, $input );

		// replace and group unmodified compounds
		foreach(self::$_compounds as $k=>$v) {
			$input = preg_replace("/".$k."/u", '{'.$v.'}', $input);
		}

		// replace and group modified consonants and vowels
		$input = self::_replaceModifiedGlyphs(array_merge(self::$_consonants, self::$_vowels), $input );

		// replace and group unmodified consonants
		foreach(self::$_consonants as $k=>$v) {
			$input = preg_replace("/".$k."/u", '{'.$v.'}', $input);
		}

		// replace and group unmodified vowels
		foreach(self::$_vowels as $k=>$v) {
			$input = preg_replace("/".$k."/u", '{'.$v.'}', $input);
		}
		// replace and group chillu
		foreach(self::$_chillus as $k=>$v) {
			$input = preg_replace("/".$k."/u", '{'.$v.'}', $input);
		}

		// replace all modifiers
		foreach(self::$_modifiers as $k=>$v) {
			$input = preg_replace("/".$k."/u", $v, $input);
		}

		// remove non alpha numeric characters (losing the bracket grouping)
		$input = preg_replace('/[^0-9a-z]/i', '', $input);

		// phonetic exceptions (uthsavam - ulsavam...)
		$input = preg_replace('/^(A|V|T|S|U|M|O)L(K|S)/i', '${1}0${2}', $input);

		return $input;
	}

	// ______ replace modified glyphs
	private static function _replaceModifiedGlyphs($glyphs, $input) {
		// see if a given set of glyphs have modifiers trailing them
		preg_match_all("/(".implode("|", array_keys($glyphs)).")(". implode('|', array_keys(self::$_modifiers) ).")/u", $input, $match);

		// if yes, replace the glpyh with its roman equivalent, and the modifier with its
		if(isset($match[0])) {
			for($n=0; $n<count($match[0]); $n++) {
				$input = preg_replace("/".$match[0][$n]."/u", '{'.$glyphs[ $match[1][$n] ] . $match[2][$n].'}', $input);
			}
		}

		return $input;
	}
}

?>