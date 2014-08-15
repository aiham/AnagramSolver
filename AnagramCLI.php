<?php

require_once dirname(__FILE__) . '/CLI.php/CLI.php';
require_once dirname(__FILE__) . '/Dictionary.php';
require_once dirname(__FILE__) . '/Anagram.php';

class AnagramCLI extends CLI {

  protected $title = 'Anagram Solver';
  protected $author = 'Aiham Hammami';
  protected $date = '2013';
  protected $version = '1.0.0';
  protected $description = 'Various tools to help solve word puzzles.';
  protected $examples = array(
    'solve enstdtu' => "1. student\n2. stunted",
    'solveall kcab' => " 1. back\n 2. k\n 3. c\n 4. b\n 5. a\n 6. ka\n 7. ca\n 8. ab\n 9. ba\n10. bac\n11. cab",
    'combo man' => "1. amn\n2. anm\n3. man\n4. mna\n5. nam\n6. nma",
    'combocount man' => '6',
    'combocount magnificent' => '9979200',
    'contain mountainous' => "1. mountainous\n2. mountainously\n3. mountainousness\n4. nonmountainous\n5. unmountainous",
    'begin mountainous' => "1. mountainous\n2. mountainously\n3. mountainousness",
    'end mountainous' => "1. mountainous\n2. nonmountainous\n3. unmountainous",
    'word mysterious' => 'Yes',
    'word abcdef' => 'No',
    'anagrams family ymailf' => 'Yes',
    'anagrams family tree' => 'No'
  );
  protected $commands = array(
    'solve' => array(
      'callback' => 'processSolve',
      'alias' => '-s',
      'description' => 'Solve the anagram <anagram>',
      'needsDictionary' => true
    ),
    'solveall' => array(
      'callback' => 'processSolveAll',
      'alias' => '-a',
      'description' => 'Solve the anagram <anagram> including shorter anagrams',
      'needsDictionary' => true
    ),
    'solvesize' => array(
      'callback' => 'processSolveSize',
      'alias' => '-z',
      'description' => 'Solve an anagram of size <size> from the letters <letters>',
      'needsDictionary' => true
    ),
    'combo' => array(
      'callback' => 'processCombo',
      'alias' => '-m',
      'description' => 'List unique combinations of letters in <word>',
      'needsDictionary' => false
    ),
    'combocount' => array(
      'callback' => 'processComboCount',
      'alias' => '-n',
      'description' => 'Count unique combinations of letters in <word>',
      'needsDictionary' => false
    ),
    'contain' => array(
      'callback' => 'processContain',
      'alias' => '-c',
      'description' => 'List all words that contain <word>',
      'needsDictionary' => true
    ),
    'begin' => array(
      'callback' => 'processBegin',
      'alias' => '-b',
      'description' => 'List all words that begin with <word>',
      'needsDictionary' => true
    ),
    'end' => array(
      'callback' => 'processEnd',
      'alias' => '-e',
      'description' => 'List all words that end with <word>',
      'needsDictionary' => true
    ),
    'word' => array(
      'callback' => 'processWord',
      'alias' => '-w',
      'description' => 'Verify whether <word> is in English dictionary',
      'needsDictionary' => true
    ),
    'anagrams' => array(
      'callback' => 'processAnagrams',
      'alias' => '-g',
      'description' => 'Verify whether <word1> and <word2> are anagrams',
      'needsDictionary' => false
    )
  );

  protected $dictionaryPath = null;
  protected $dictionary = null;

  public function __construct ($dictionaryPath) {

    parent::__construct();
    $this->dictionaryPath = $dictionaryPath;

  }

  protected function before ($command) {

    $this->initialiseDictionaryForCommand($command);
    return true;

  }

  protected function initialiseDictionaryForCommand ($command) {

    $settings = $this->commands[$command];
    if (array_key_exists('needsDictionary', $settings) && $settings['needsDictionary']) {
      $this->dictionary = new Dictionary($this->dictionaryPath);
    }

  }

  protected function processSolve ($anagram) {

    $anagram = new Anagram($anagram, $this->dictionary);
    $this->printList($anagram->solve());
    return true;

  }

  protected function processSolveAll ($anagram) {

    $anagram = new Anagram($anagram, $this->dictionary);
    $this->printList($anagram->solveAll());
    return true;

  }

  protected function processSolveSize ($letters, $size) {

    $anagram = new Anagram($letters, $this->dictionary);
    $this->printList($anagram->solveAll(intval($size)));
    return true;

  }

  protected function processCombo ($word) {

    $anagram = new Anagram($word);
    $this->printList($anagram->combinations());
    return true;

  }

  protected function processComboCount ($word) {

    $anagram = new Anagram($word);
    printf("%d\n", $anagram->combinationCount());
    return true;

  }

  protected function processContain ($word) {

    $this->printList($this->dictionary->containsWord($word));
    return true;

  }

  protected function processBegin ($word) {

    $this->printList($this->dictionary->beginsWithWord($word));
    return true;

  }

  protected function processEnd ($word) {

    $this->printList($this->dictionary->endsWithWord($word));
    return true;

  }

  protected function processWord ($word) {

    printf("%s\n", $this->dictionary->isValidWord($word) ? 'Yes' : 'No');
    return true;

  }

  protected function processAnagrams ($word1, $word2) {

    $anagram = new Anagram($word1);
    printf("%s\n", $anagram->isAnagramOf($word2) ? 'Yes' : 'No');
    return true;

  }

  protected function printList ($list) {

    if (empty($list)) {
      printf("%s.\n", 'No results');
    } else {
      $padding = strlen(strval(count($list)));
      $format = sprintf("%% %dd. %%s\n", $padding);

      $i = 0;
      foreach ($list as $row) {
        printf($format, ++$i, $row);
      }
    }

  }

}

