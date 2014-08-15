<?php

class Anagram {

  protected $word = null;
  protected $dictionary = null;
  protected $combinations = null;
  protected $prefixes = null;
  public $counter = 0;
  protected $solutions = null;
  protected $innerAnagrams = null;
  protected $innerWords = null;

  public function __construct ($word, $dictionary = null) {

    $this->word = $this->formatWord($word);
    $this->dictionary = $dictionary;

  }

  public function isAnagramOf ($word) {

    return $this->word === $this->formatWord($word);

  }

  public function solve ($all = true) {

    if (!is_null($this->dictionary) &&
        (is_null($this->solutions) || ($all && count($this->solutions) === 1))) {
      $this->solutions = array();
      foreach ($this->combinations() as $combination) {
        if ($this->dictionary->isValidWord($combination)) {
          array_push($this->solutions, $combination);
          if (!$all) {
            break;
          }
        }
      }
    }
    return $all ? $this->solutions : (!empty($this->solutions) ? $this->solutions[0] : null);

  }

  public function solveAll ($length = 0) {

    if (is_null($this->innerWords)) {
      $this->innerWords = $this->solve();
      $this->innerAnagrams = array();
      $this->counter = 0;
      $this->buildInnerWords($this->word);
      $this->innerWords = array_unique($this->innerWords);
      $this->innerAnagrams = null;
    }
    if ($length > 0) {
      return array_filter($this->innerWords, function ($innerWord) use ($length) {
        $r = strlen($innerWord) === $length;
        return $r;
      });
    }
    return $this->innerWords;

  }

  protected function buildInnerWords ($word) {

    $this->counter++;
    for ($i = 0, $l = strlen($word); $l > 1 && $i < $l; $i++) {
      $innerAnagram = substr_replace($word, '', $i, 1);
      if (!in_array($innerAnagram, $this->innerAnagrams, true)) {
        array_push($this->innerAnagrams, $innerAnagram);
        $this->buildInnerWords($innerAnagram);

        $anagram = new Anagram($innerAnagram, $this->dictionary);
        $solutions = $anagram->solve();
        if (!empty($solutions)) {
          array_unshift($solutions, $this->innerWords);
          call_user_func_array('array_push', $solutions);
        }
        $innerAnagram = $anagram = $solutions = null;
      }
    }

  }

  public function combinationCount () {

    $count = 1;
    for ($i = 2, $l = strlen($this->word); $i <= $l; $i++) {
      $count *= $i;
    }

    foreach (count_chars($this->word, 1) as $charCount) {
      if ($charCount > 1) {
        for ($i = 2; $i <= $charCount; $i++) {
          $count /= $i;
        }
      }
    }
    return $count;

  }

  public function combinations () {

    if (is_null($this->combinations)) {
      $this->combinations = array();
      $this->prefixes = array();
      $this->counter = 0;
      $this->buildCombinations($this->word);
      $this->prefixes = null;
    }
    return $this->combinations;

  }

  protected function buildCombinations ($word, $prefix = '') {

    $this->counter++;
    if (($l = strlen($word)) > 0) {
      for ($i = 0; $i < $l; $i++) {
        $newPrefix = $prefix . $word[$i];
        if (!in_array($newPrefix, $this->prefixes, true)) {
          array_push($this->prefixes, $newPrefix);
          $this->buildCombinations(substr_replace($word, '', $i, 1), $newPrefix);
        }
      }
    } else if (strlen($prefix) > 0) {
      array_push($this->combinations, $prefix);
    }

  }

  protected function formatWord ($word) {

    $letters = str_split(strtolower($word));
    sort($letters);
    return implode('', $letters);

  }

}

