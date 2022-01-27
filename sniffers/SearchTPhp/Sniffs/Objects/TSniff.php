<?php

namespace SearchTPhp\Sniffs\Objects;

use Drupal\Sniffs\Semantics\FunctionCall;
use PHP_CodeSniffer\Files\File;

class TSniff extends FunctionCall {

    protected $excludedFunctions = [
        'summary',
        'buildConfigurationForm',
    ];

    /**
     * Processes this test, when one of its tokens is encountered.
     *
     * @param \PHP_CodeSniffer\Files\File $phpcsFile The file being scanned.
     * @param int                         $stackPtr  The position of the current token
     *                                               in the stack passed in $tokens.
     *
     * @return void|int
     */
    public function process(File $phpcsFile, $stackPtr) {
        $tokens = $phpcsFile->getTokens();
        $token = $tokens[$stackPtr];
        if ($token['content'] == 't') {

            // Inherit.
            if (count($token['conditions']) > 1) {
                $keys = array_keys($token['conditions']);
                $function_token_number = array_pop($keys);
                $function_token_number = $function_token_number + 2;
                $function_token = $tokens[$function_token_number];
                if (in_array($function_token['content'], $this->excludedFunctions)) {
                    return;
                }
            }

            $text_token_number = $stackPtr;

            $text_tokens = [];
            $text_token_found = FALSE;
            while (TRUE) {
                $text_token_number++;
                $text_token = $tokens[$text_token_number];
                if ($text_token['type'] == "T_CONSTANT_ENCAPSED_STRING") {
                    $text_tokens[] = $tokens[$text_token_number];
                    $text_token_found = TRUE;
                }
                elseif ($text_token_found) {
                    break;
                }
            }

            $text = '';

            foreach ($text_tokens as $text_token) {
                $text .= $text_token['content'];
            }

            if (in_array($text[0], ["'", "\""])) {
                $origin_text = $text;
                $text = '';
                for ($i = 1; $i < strlen($origin_text)-1; $i++) {
                    $text .= $origin_text[$i];
                }
            }

            $filename = 'list.po';
            try {
                $current = file_get_contents($filename);
            }
            catch (\Exception $e) {
                $current = '';
            }
            $current .= $text . "\n";
            file_put_contents($filename, $current);
        }
    }

}
