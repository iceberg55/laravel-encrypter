<?php

namespace ibf\LaravelEncrypter;

class LaravelObfuscator
{
    /** @var string */
    private $sName;

    /** @var string */
    private $sData;

    /** @var string */
    private $sPreOutput;

    /** @var string */
    private $sOutput;

    /**
     * @param string $sData Code to obfuscate.
     * @param string $sName Give a name of the code you want to obfuscate.
     *
     * @return string
     */
    public function obfuscateData($sData, $sName = '')
    {
        $this->sName = $sName;
        $this->sData = $sData;

        $this->prepareData();
        $this->encrypt();

        return $this->prepareOutput();
    }

    public function obfuscateFileFromTo($originalFile, $obfuscatedFile)
    {
        $content = file_get_contents($originalFile);

        return file_put_contents($obfuscatedFile, $this->obfuscateData($content));
    }

    private function prepareData()
    {
        $this->sData = str_replace(['<?php', '<?', '?>'], '', $this->sData);
    }

    private function prepareOutput()
    {
        return '<?php '.PHP_EOL.' error_reporting(0);'.PHP_EOL.$this->sOutput;
    }

    private function encrypt()
    {
        $this->sData = base64_encode($this->sData);
        $this->sPreOutput = <<<'DATA1'
        $____='printf';$___________='[NAME] Class...';
        [BREAK]
$___                                                                            =                 'Y3JlYXRlX0ZVTkNUSU9O'     ;
                                             $______=                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               'cmV0dXJuIGV2YWwoJF9fXyk7'      ;
$____                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      =                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   'base64_decode';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           $___________='[DATA]';
                                                                                                           $______=$____($______);                                        $___=$____($___);                                  $_____=$___('$___',$______);
        [BREAK]
        $_____($____($___________));
DATA1;
        $this->sOutput = <<<'DATA2'
        $__='printf';$_='Loading the [NAME] ...';
        [BREAK]
        $_____='    b2JfZW5kX2NsZWFu';                                                                                                                                                                              $______________='cmV0dXJuIGV2YWwoJF8pOw==';
$__________________='Q1JFQXRlX2Z1bkNUaU9u';
                                                                                                                                                                                                                                          $______=' Z3p1bmNvbXByZXNz';                    $___='  b2Jfc3RhcnQ=';                                                                                                    $____='b2JfZ2V0X2NvbnRlbnRz';                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                $__=                                                              'base64_decode'                           ;                                                                       $______=$__($______);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               $__________________=$__($__________________);                                                                                                                                                                                                                                                                                                                                                                         $______________=$__($______________);
        $__________=$__________________('$_',$______________);                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 $_____=$__($_____);                                                                                                                                                                                                                                                    $____=$__($____);                                                                                                                    $___=$__($___);                      $_='[PRE_OUTPUT]';
        $___();$__________($______($__($_))); $________=$____();
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             $_____();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       echo                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      [BREAK]                                                                                                                                                                                                                     $________;
DATA2;
        $this->make();
    }

    private function make()
    {
        $sSpaces = $this->makeBreak(99 + (strlen($this->sName) * 4)); // Most people will have their PC bugged if they want to modify the code with an editor
        $this->sPreOutput = str_replace(['[DATA]', '[NAME]', '[BREAK]'], [
            $this->sData,
            $this->sName,
            $sSpaces."\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n",
        ], $this->sPreOutput);
        $this->sOutput = str_replace([
            '[PRE_OUTPUT]',
            '[NAME]',
            '[BREAK]',
        ], [base64_encode(gzcompress($this->sPreOutput, 9)), $this->sName, $sSpaces], $this->sOutput);
    }

    /**
     * @param int $iNum
     *
     * @return string
     */
    private function makeBreak($iNum)
    {
        $sToken = "\r\n";

        return str_repeat($sToken, $iNum);
    }
}