<?php

namespace App\Services;

/**
 * Porter Stemmer in PHP.
 *
 * @copyright 2015 Mark Rogoyski
 * @license   http://www.opensource.org/licenses/mit-license.php MIT
 * @link      https://github.com/markrogoyski/porter-stemmer-php
 */
class PorterStemmer
{
    /**
     * A regex of consonant characters.
     *
     * @var string
     */
    protected static $consonant = '(?:[bcdfghjklmnpqrstvwxz]|(?<=[aeiou])y|^y)';

    /**
     * A regex of vowel characters.
     *
     * @var string
     */
    protected static $vowel = '(?:[aeiou]|(?<![aeiou])y)';

    /**
     * Stems a word.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    public static function stem(string $word): string
    {
        // Must be longer than two characters.
        if (strlen($word) <= 2) {
            return $word;
        }

        // Run the word through the stemming steps.
        $word = static::step1ab($word);
        $word = static::step1c($word);
        $word = static::step2($word);
        $word = static::step3($word);
        $word = static::step4($word);
        $word = static::step5($word);

        return $word;
    }

    /**
     * Step 1a.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step1ab(string $word): string
    {
        // Part a.
        if (substr($word, -1) == 's') {
            if (substr($word, -4) == 'sses') {
                $word = substr($word, 0, -2);
            } elseif (substr($word, -3) == 'ies') {
                $word = substr($word, 0, -2);
            } elseif (substr($word, -2) != 'ss') {
                $word = substr($word, 0, -1);
            }
        }

        // Part b.
        if (substr($word, -3) == 'eed') {
            if (static::measure(substr($word, 0, -3)) > 0) {
                $word = substr($word, 0, -1);
            }
        } elseif (preg_match('/' . static::$vowel . '+/', substr($word, 0, -2)) && substr($word, -2) == 'ed') {
            $word = substr($word, 0, -2);
            $word = static::step1ab_2($word);
        } elseif (preg_match('/' . static::$vowel . '+/', substr($word, 0, -3)) && substr($word, -3) == 'ing') {
            $word = substr($word, 0, -3);
            $word = static::step1ab_2($word);
        }

        return $word;
    }

    /**
     * Step 1ab part 2.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step1ab_2(string $word): string
    {
        if (in_array(substr($word, -2), ['at', 'bl', 'iz'])) {
            $word .= 'e';
        } elseif (static::isDoubleConsonant($word)) {
            if (!in_array(substr($word, -1), ['l', 's', 'z'])) {
                $word = substr($word, 0, -1);
            }
        } elseif (static::measure($word) == 1 && static::isCVC($word)) {
            $word .= 'e';
        }

        return $word;
    }

    /**
     * Step 1c.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step1c(string $word): string
    {
        if (preg_match('/' . static::$vowel . '+/', substr($word, 0, -1)) && substr($word, -1) == 'y') {
            $word = substr($word, 0, -1) . 'i';
        }
        return $word;
    }

    /**
     * Step 2.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step2(string $word): string
    {
        $sufix = substr($word, -2, 1);
        if (static::measure(substr($word, 0, -7)) > 0 && substr($word, -7) == 'ational') {
            return substr($word, 0, -5) . 'e';
        } elseif (static::measure(substr($word, 0, -6)) > 0 && substr($word, -6) == 'tional') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'enci') {
            return substr($word, 0, -1) . 'e';
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'anci') {
            return substr($word, 0, -1) . 'e';
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'izer') {
            return substr($word, 0, -1);
        } elseif (static::measure(substr($word, 0, -3)) > 0 && substr($word, -3) == 'bli') { // Originally abli
            return substr($word, 0, -1) . 'e';
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'alli') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'entli') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -3)) > 0 && substr($word, -3) == 'eli') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'ousli') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -7)) > 0 && substr($word, -7) == 'ization') {
            return substr($word, 0, -5) . 'e';
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'ation') {
            return substr($word, 0, -3) . 'e';
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'ator') {
            return substr($word, 0, -2) . 'e';
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'alism') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -7)) > 0 && substr($word, -7) == 'iveness') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -7)) > 0 && substr($word, -7) == 'fulness') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -7)) > 0 && substr($word, -7) == 'ousness') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'aliti') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'iviti') {
            return substr($word, 0, -3) . 'e';
        } elseif (static::measure(substr($word, 0, -6)) > 0 && substr($word, -6) == 'biliti') {
            return substr($word, 0, -5) . 'le';
        }

        return $word;
    }

    /**
     * Step 3.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step3(string $word): string
    {
        if (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'icate') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'ative') {
            return substr($word, 0, -5);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'alize') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -5)) > 0 && substr($word, -5) == 'iciti') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'ical') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -3)) > 0 && substr($word, -3) == 'ful') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -4)) > 0 && substr($word, -4) == 'ness') {
            return substr($word, 0, -4);
        }

        return $word;
    }

    /**
     * Step 4.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step4(string $word): string
    {
        $sufix = substr($word, -2);
        if (static::measure(substr($word, 0, -2)) > 1 && $sufix == 'al') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -4)) > 1 && substr($word, -4) == 'ance') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -4)) > 1 && substr($word, -4) == 'ence') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -2)) > 1 && $sufix == 'er') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -2)) > 1 && $sufix == 'ic') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -4)) > 1 && substr($word, -4) == 'able') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -4)) > 1 && substr($word, -4) == 'ible') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ant') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -5)) > 1 && substr($word, -5) == 'ement') {
            return substr($word, 0, -5);
        } elseif (static::measure(substr($word, 0, -4)) > 1 && substr($word, -4) == 'ment') {
            return substr($word, 0, -4);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ent') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && in_array($sufix, ['ion', 'ou'])) {
            $base = substr($word, 0, -3);
            if (in_array(substr($base, -1), ['s', 't'])) {
                return $base;
            }
        } elseif (static::measure(substr($word, 0, -2)) > 1 && $sufix == 'ou') {
            return substr($word, 0, -2);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ism') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ate') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'iti') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ous') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ive') {
            return substr($word, 0, -3);
        } elseif (static::measure(substr($word, 0, -3)) > 1 && substr($word, -3) == 'ize') {
            return substr($word, 0, -3);
        }

        return $word;
    }

    /**
     * Step 5.
     *
     * @param string $word The word to stem.
     *
     * @return string The stemmed word.
     */
    protected static function step5(string $word): string
    {
        // Part a.
        if (static::measure(substr($word, 0, -1)) > 1 && substr($word, -1) == 'e') {
            return substr($word, 0, -1);
        } elseif (static::measure(substr($word, 0, -1)) == 1 && !static::isCVC(substr($word, 0, -1)) && substr($word, -1) == 'e') {
            return substr($word, 0, -1);
        }

        // Part b.
        if (static::measure($word) > 1 && static::isDoubleConsonant($word) && substr($word, -1) == 'l') {
            $word = substr($word, 0, -1);
        }

        return $word;
    }

    /**
     * Measures the number of consonant sequences in a word.
     *
     * @param string $word The word to measure.
     *
     * @return int The number of consonant sequences in the word.
     */
    protected static function measure(string $word): int
    {
        return preg_match_all('/' . static::$vowel . static::$consonant . '/', $word);
    }

    /**
     * Checks for a double consonant in the word.
     *
     * @param string $word The word to check.
     *
     * @return bool Whether the word has a double consonant.
     */
    protected static function isDoubleConsonant(string $word): bool
    {
        return preg_match('/' . static::$consonant . '{2}$/', $word);
    }

    /**
     * Checks if a word ends with a CVC sequence.
     *
     * @param string $word The word to check.
     *
     * @return bool Whether the word ends with a CVC sequence.
     */
    protected static function isCVC(string $word): bool
    {
        $pattern = '/' . static::$consonant . static::$vowel . static::$consonant . '$/';
        if (preg_match($pattern, $word)) {
            $char = substr($word, -1);
            if (!in_array($char, ['w', 'x', 'y'])) {
                return true;
            }
        }

        return false;
    }
}
