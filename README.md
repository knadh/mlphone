# MLphone (Python, PHP)
MLphone is a phonetic algorithm for indexing Malayalam words by their pronunciation, like Metaphone for English. The algorithm generates three Romanized phonetic keys (hashes) of varying phonetic affinities for a given Malayalam word.

Full documentation: http://nadh.in/code/mlphone

Licensed under GNU GPL v2 license.

# Intro
MLphone is a phonetic algorithm for indexing Malayalam words by their pronunciation,
like Metaphone for English. The algorithm generates three Romanized phonetic keys (hashes) of varying
phonetic affinities for a given Malayalam word.

The	algorithm takes into account the context sensitivity of sounds, syntactic and
phonetic gemination, compounding, modifiers, and other known exceptions to produce
Romanized phonetic hashes of increasing phonetic affinity that are very faithful
to the pronunciation of the original Malayalam word.

<ul>
	<li>key0 =	a broad phonetic hash comparable to a Metaphone key that
			doesn't account for hard sounds (ഷ, ണ..) or phonetic modifiers</li>

	<li>key1 =	is a slightly more inclusive hash that accounts for hard sounds</li>

	<li>key2 =	highly inclusive and narrow hash that accounts for hard sounds
			and phonetic modifiers</li>
</ul>

MLphone was created to aid spelling tolerant Malayalam word search, but may 
be useful in tasks like spell checking, word suggestion etc.

# Examples
<table width="100%" cellspacing="0" class="list">
	<thead>
		<td>Word</td>
		<td>key0</td>
		<td>key1</td>
		<td>key2</td>
		<td class="en">Transliteration</td>
		<td class="meta">Metaphone</td>
	</thead>
	<tbody>
	<tr>
		<td>നീലക്കുയില്‍</td>
		<td>NLKYL</td>
		<td>NLKYL</td>
		<td>N4LK25Y4L</td>
		<td class="en">Neelakkuyil‍</td>
		<td class="meta">NLKYL</td>
	</tr>
	<tr>
		<td>മൃഗം</td>
		<td>MRK3</td>
		<td>MRK3</td>
		<td>MRK3</td>
		<td class="en">Mrugam</td>
		<td class="meta">MRKM</td>
	</tr>
	<tr>
		<td>മ്രിഗം</td>
		<td>MRK3</td>
		<td>MRK3</td>
		<td>MRK3</td>
		<td class="en">Mrigam</td>
		<td class="meta">MRKM</td>
	</tr>
	<tr>
		<td>ഉത്സവം</td>
		<td>U0SV3</td>
		<td>U0SV3</td>
		<td>U0SV3</td>
		<td class="en">Uthsavam</td>
		<td class="meta">U0SFM</td>
	</tr>
	<tr>
		<td>ഉല്‍സവം</td>
		<td>U0SV3</td>
		<td>U0SV3</td>
		<td>U0SV3</td>
		<td class="en">Ul‍savam</td>
		<td class="meta">ULSFM</td>
	</tr>
	<tr>
		<td>വാഹനം</td>
		<td>VHN3</td>
		<td>VHN3</td>
		<td>VHN3</td>
		<td class="en">Vaahanam</td>
		<td class="meta">FHNM</td>
	</tr>
	<tr>
		<td>വിഹനനം</td>
		<td>VHNN3</td>
		<td>VHNN3</td>
		<td>V4HNN3</td>
		<td class="en">Vihananam</td>
		<td class="meta">FHNNM</td>
	</tr>
	<tr>
		<td>രാഷ്ട്രീയം</td>
		<td>RSTRY3</td>
		<td>RS1TRY3</td>
		<td>RS1TR4Y3</td>
		<td class="en">Raashtreeyam</td>
		<td class="meta">RXTRYM</td>
	</tr>
	<tr>
		<td>കണ്ണകി</td>
		<td>KNK</td>
		<td>KNK</td>
		<td>KN2K4</td>
		<td class="en">Kannaki</td>
		<td class="meta">KNK</td>
	</tr>
	<tr>
		<td>കന്യക</td>
		<td>KNYK</td>
		<td>KNYK</td>
		<td>KNYK</td>
		<td class="en">Kanyaka</td>
		<td class="meta">KNYK</td>
	</tr>
	<tr>
		<td>മനം</td>
		<td>MN3</td>
		<td>MN3</td>
		<td>MN3</td>
		<td class="en">Manam</td>
		<td class="meta">MNM</td>
	</tr>
	<tr>
		<td>മണം</td>
		<td>MN3</td>
		<td>MN13</td>
		<td>MN13</td>
		<td class="en">Manam</td>
		<td class="meta">MNM</td>
	</tr>
	<tr>
		<td>വിഭക്ത്യാഭാസം</td>
		<td>VBK0YBS3</td>
		<td>VBK0YBS3</td>
		<td>V4BK0YBS3</td>
		<td class="en">Vibhakthyaabhaasam</td>
		<td class="meta">FBHK0YBHSM</td>
	</tr>
	<tr>
		<td>വലയം</td>
		<td>VLY3</td>
		<td>VLY3</td>
		<td>VLY3</td>
		<td class="en">Valayam</td>
		<td class="meta">FLYM</td>
	</tr>
	<tr>
		<td>വളയം</td>
		<td>VLY3</td>
		<td>VL1Y3</td>
		<td>VL1Y3</td>
		<td class="en">Valayam</td>
		<td class="meta">FLYM</td>
	</tr>
	<tr>
		<td>രഥം</td>
		<td>R03</td>
		<td>R03</td>
		<td>R03</td>
		<td class="en">Ratham</td>
		<td class="meta">R0M</td>
	</tr>
	<tr>
		<td>രദം</td>
		<td>R03</td>
		<td>R03</td>
		<td>R03</td>
		<td class="en">Radam</td>
		<td class="meta">RTM</td>
	</tr>
	<tr>
		<td>രത്തം</td>
		<td>R03</td>
		<td>R03</td>
		<td>R03</td>
		<td class="en">Rattham</td>
		<td class="meta">RTM</td>
	</tr>
	<tr>
		<td>രധം</td>
		<td>R03</td>
		<td>R03</td>
		<td>R03</td>
		<td class="en">Radham</td>
		<td class="meta">RTHM</td>
	</tr>
</tbody></table>

# Usage
The algorithm's available in Python and PHP


### Python
```python
from mlphone import MLphone

converter = MLphone()
keys = converter.compute(ml_str)
```

### PHP
```php
<?php
	require 'mlphone.php';

	$keys = MLphone::compute($ml_str);
?>
```
