<?php

class Dictionary {

  protected $words = array();
  protected $wordCount = 0;

  public function __construct ($filename) {

    if (!file_exists($filename)) {
      echo 'Error: Invalid dictionary filename (' . $filename . ')';
      exit(1);
    }

    $this->words = preg_split(
      '/\s*[\n\r]+\s*/',
      file_get_contents($filename),
      null,
      PREG_SPLIT_NO_EMPTY
    );
    sort($this->words);
    $this->wordCount = count($this->words);

  }

  public function containsWord ($word) {

    return array_filter($this->words, function ($container) use ($word) {

      return stripos($container, $word) !== false;

    });

  }

  public function beginsWithWord ($word) {

    return array_filter($this->words, function ($container) use ($word) {

      return stripos($container, $word) === 0;

    });

  }

  public function endsWithWord ($word) {

    return array_filter($this->words, function ($container) use ($word) {

      return stripos($container, $word) === strlen($container) - strlen($word);

    });

  }

  public function isValidWord ($word) {

    return is_string($word) &&
      strlen($word) > 0 &&
      $this->wordCount > 0 &&
      $this->binarySearchWord($word, 0, $this->wordCount - 1);

  }

  protected function binarySearchWord ($word, $min, $max) {

    $half = $max - ceil(($max - $min) / 2);
    if ($half === $min) {
      return $word === $this->words[$min] || $word === $this->words[$max];
    }

    $equality = strcasecmp($word, $this->words[$half]);
    if ($equality === 0) {
      return true;
    }

    return $this->binarySearchWord(
      $word,
      $equality < 0 ? $min : $half,
      $equality < 0 ? $half : $max
    );

  }

}

