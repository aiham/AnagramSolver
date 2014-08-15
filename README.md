# AnagramSolver

AnagramSolver is a command line program that solves word puzzles including anagrams.

## Requirements

- [PHP][]
- [CLI.php][]

[PHP]: http://www.php.net
[CLI.php]: https://github.com/aiham/CLI.php

## Installation

```sh
git clone https://github.com/aiham/AnagramSolver.git
cd AnagramSolver
./setup.sh
```

## Usage

```sh
  ./anagram <command or alias> [argument ...]
```

----------------------------------------------------------------------------------------------------
| Command    | Alias  | Arguments    | Description                                                 |
----------------------------------------------------------------------------------------------------
| solve      | -s     | anagram      | Solve the anagram <anagram>.                                |
| solveall   | -a     | anagram      | Solve the anagram <anagram> including shorter anagrams.     |
| solvesize  | -z     | letters size | Solve an anagram of size <size> from the letters <letters>. |
| combo      | -m     | word         | List unique combinations of letters in <word>.              |
| combocount | -n     | word         | Count unique combinations of letters in <word>.             |
| contain    | -c     | word         | List all words that contain <word>.                         |
| begin      | -b     | word         | List all words that begin with <word>.                      |
| end        | -e     | word         | List all words that end with <word>.                        |
| word       | -w     | word         | Verify whether <word> is in English dictionary.             |
| anagrams   | -g     | word1 word2  | Verify whether <word1> and <word2> are anagrams.            |
| help       | -h, -? |              | Display this help screen.                                   |
| examples   | -x     |              | Display a list of examples of how to use this program.      |
----------------------------------------------------------------------------------------------------

## Examples

```sh
  ./anagram solve enstdtu

      1. student
      2. stunted
```

```sh
  ./anagram solveall kcab

       1. back
       2. k
       3. c
       4. b
       5. a
       6. ka
       7. ca
       8. ab
       9. ba
      10. bac
      11. cab
```

```sh
  ./anagram combo man

      1. amn
      2. anm
      3. man
      4. mna
      5. nam
      6. nma
```

```sh
  ./anagram combocount man

      6
```

```sh
  ./anagram combocount magnificent

      9979200
```

```sh
  ./anagram contain mountainous

      1. mountainous
      2. mountainously
      3. mountainousness
      4. nonmountainous
      5. unmountainous
```

```sh
  ./anagram begin mountainous

      1. mountainous
      2. mountainously
      3. mountainousness
```

```sh
  ./anagram end mountainous

      1. mountainous
      2. nonmountainous
      3. unmountainous
```

```sh
  ./anagram word mysterious

      Yes
```

```sh
  ./anagram word abcdef

      No
```

```sh
  ./anagram anagrams family ymailf

      Yes
```

```sh
  ./anagram anagrams family tree

      No
```

## Dictionary

If you want to use a different dictionary, create a text file with each word on a new line and update the filename in `config.json`.

