#! /usr/bin/env python3
# -*- coding: utf-8 -*-

'''
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


	This work is licensed under GPL v2
	
	--
	Kailash Nadh, November 2012
	http://nadh.in
'''

import re

class MLphone:
	__vowels = {
		"അ": "A", "ആ": "A", "ഇ": "I", "ഈ": "I", "ഉ": "U", "ഊ": "U", "ഋ": "R",
		"എ": "E", "ഏ": "E", "ഐ": "AI", "ഒ": "O", "ഓ": "O", "ഔ": "O"
	}

	__consonants = {
		"ക": "K", "ഖ": "K", "ഗ": "K", "ഘ": "K", "ങ": "NG",
		"ച": "C", "ഛ": "C", "ജ": "J", "ഝ": "J", "ഞ": "NJ",
		"ട": "T", "ഠ": "T", "ഡ": "T", "ഢ": "T", "ണ": "N1",
		"ത": "0", "ഥ": "0", "ദ": "0", "ധ": "0", "ന": "N",
		"പ": "P", "ഫ": "F", "ബ": "B", "ഭ": "B", "മ": "M",
		"യ": "Y", "ര": "R", "ല": "L", "വ": "V",
		"ശ": "S1", "ഷ": "S1", "സ": "S","ഹ": "H",
		"ള": "L1", "ഴ": "Z", "റ": "R1"
	}

	__chillus = {
		"ൽ": "L", "ൾ": "L1", "ൺ": "N1",
		"ൻ": "N", "ർ": "R1", "ൿ": "K"
	}

	__compounds = {
		"ക്ക": "K2", "ഗ്ഗ": "K", "ങ്ങ": "NG",
		"ച്ച": "C2", "ജ്ജ": "J", "ഞ്ഞ": "NJ",
		"ട്ട": "T2", "ണ്ണ": "N2",
		"ത്ത": "0", "ദ്ദ": "D", "ദ്ധ": "D", "ന്ന": "NN",
		"ന്ത": "N0", "ങ്ക": "NK", "ണ്ട": "N1T", "ബ്ബ": "B",
		"പ്പ": "P2", "മ്മ": "M2",
		"യ്യ": "Y", "ല്ല": "L2", "വ്വ": "V", "ശ്ശ": "S1", "സ്സ": "S",
		"ള്ള": "L12",
		"ഞ്ച": "NC", "ക്ഷ": "KS1", "മ്പ": "MP",
		"റ്റ": "T", "ന്റ": "NT", "ന്ത": "N0",
		"്രി": "R",
		"്രു": "R",
	}

	__modifiers = {
		"ാ": "", "ഃ": "", "്": "", "ൃ": "R",
		"ം": "3", "ി": "4", "ീ": "4", "ു": "5", "ൂ": "5", "െ": "6", 
		"േ": "6", "ൈ": "7", "ൊ": "8", "ോ": "8", "ൌ": "9", "ൗ": "9"
	}

	# ______ compute hashes
	def compute(self, input):
		# key2 accounts for hard and modified sounds
		key2 = self._process(input)

		# key1 loses numeric modifiers that denote phonetic modifiers (except for anuswaram)
		key1 = re.sub(r'[2,4-9]', '', key2)

		# key0 loses numeric modifiers that denote hard sounds, doubled sounds, and phonetic modifiers (except for anuswaram)
		key0 = re.sub(r'[1,2,4-9]', '', key2)

		return [key0, key1, key2]

	# ______ do everything!
	def _process(self, input):
		# remove all non-malayalam characters
		input = re.sub(r'[^\u0D00-\u0D7F]', '', input).strip()

		# all character replacements are grouped between { and } to maintain
		# separatability till the final step

		# replace and group modified compounds
		input = self._replaceModifiedGlyphs(self.__compounds, input )

		# replace and group unmodified compounds
		for k, v in self.__compounds.items():
			input = re.sub(k, '{' + v + '}', input)

		# replace and group modified consonants and vowels
		input = self._replaceModifiedGlyphs( dict(list(self.__consonants.items()) + list(self.__vowels.items())), input )

		# replace and group unmodified consonants
		for k, v in self.__consonants.items():
			input = re.sub(k, '{' + v + '}', input)

		# replace and group unmodified vowels
		for k, v in self.__vowels.items():
			input = re.sub(k, '{' + v + '}', input)

		# replace and group chillu
		for k, v in self.__chillus.items():
			input = re.sub(k, '{' + v + '}', input)

		# replace all modifiers
		for k, v in self.__modifiers.items():
			input = re.sub(k, v, input)

		# remove non alpha numeric characters (losing the bracket grouping)
		input = re.sub(r'[^0-9A-Z]', '', input)

		# phonetic exceptions (uthsavam - ulsavam...)
		input = re.sub(r'^(A|V|T|S|U|M|O)L(K|S)', '\1' + '0' + '\2', input)

		return input

	# ______ replace modified glyphs
	def _replaceModifiedGlyphs(self, glyphs, input):

		# see if a given set of glyphs have modifiers trailing them
		exp = re.compile( '((' + '|'.join(glyphs.keys()) + ')(' + '|'.join(self.__modifiers.keys()) + '))' )
		matches = exp.findall(input)

		# if yes, replace the glpyh with its roman equivalent, and the modifier with its
		if matches != None:
			for match in matches:
				input = input.replace( match[0], glyphs[match[1]] + self.__modifiers[ match[2] ]);

		return input
