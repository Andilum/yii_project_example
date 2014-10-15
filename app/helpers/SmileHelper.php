<?php

class SmileHelper {
    public static $smiles = array(
        array('code' => ':)', 'position' => '0'),
        array('code' => ':D', 'position' => '-17'),
        array('code' => ';)', 'position' => '-34'),
        array('code' => 'XD', 'position' => '-51'),
        array('code' => ':b', 'position' => '-68'),
        array('code' => ':p', 'position' => '-85'),
        array('code' => '(love)', 'position' => '-102'),
        array('code' => 'B)', 'position' => '-119'),
        array('code' => '8(', 'position' => '-136'),
        array('code' => '(smirk)', 'position' => '-153'),
        array('code' => '|-)', 'position' => '-170'),
        array('code' => ':$', 'position' => '-187'),
        array('code' => ';(', 'position' => '-204'),
        array('code' => ':(', 'position' => '-221'),
        array('code' => '8O', 'position' => '-238'),
        array('code' => ':|', 'position' => '-255'),
        array('code' => '(mm)', 'position' => '-272'),
        array('code' => '>(', 'position' => '-289'),
        array('code' => ':@', 'position' => '-306'),
        array('code' => '(A)', 'position' => '-323'),
        array('code' => '(:|', 'position' => '-340'),
        array('code' => '8|', 'position' => '-357'),
        array('code' => ':O', 'position' => '-374'),
        array('code' => ':x', 'position' => '-391'),
        array('code' => ':*', 'position' => '-408'),
        array('code' => '(devil)', 'position' => '-425'),
        array('code' => '(heart)', 'position' => '-442'),
        array('code' => '(yes)', 'position' => '-459'),
        array('code' => '(no)', 'position' => '-476'),
        array('code' => '(wait)', 'position' => '-493'),
        array('code' => '(peace)', 'position' => '-510'),
        array('code' => '(ok)', 'position' => '-527'),
    );

    public static function replaceSmilesFromText($text) {
        foreach (self::$smiles as $smile) {
            $text = str_replace($smile['code'], '<img src="/i/blank.gif" alt="" class="b-feedback-emoji-smiles" style="background-position: 0 ' . $smile['position'] . 'px;" />', $text);
        }
        return $text;
    }
}