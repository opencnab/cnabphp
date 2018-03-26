<?php

/*
* The MIT License (MIT)
* 
* Copyright (c) 2018 Tiago M. Abreu
* 
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in
* all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
* THE SOFTWARE.
*/


/******************************************
/* Variaveis de Configurações 
/*****************************************/
$GLOBALS['CNAB'] = 0;
$GLOBALS['SHOW_LOG'] = FALSE;

$GLOBALS['CNAB_LINE'] = 0;


$GLOBALS['LOG_VALUES'] = array();
$GLOBALS['LOG_VALUES_ERROS'] = array();

class CnabPHP {

    public function setCnab($bits)
    {
        if($bits == 240 || $bits == 400){
            $GLOBALS['CNAB'] = $bits;
            
            return TRUE;
        }

        echo "Formato de Cnab não autorizado pela Febraran";
        exit;
    }

    public function setHeader()
    {
        $this->newRegister();
        return TRUE;
    }

    public function setTrailer()
    {
        $this->newRegister();
        return TRUE;
    }

    public function showCnab(){
        foreach($this->cnab as $cnab){
            echo $cnab;
            echo "<br>";
        }

        if($GLOBALS['SHOW_LOG'] = 1){
            $this->showLogValuesErros();
        }
    } 

    public function showCnabDump(){
        var_dump($this->cnab);
        exit;
    } 

    public function setValue($value, $posini, $posfim, $picture)
    {
        $value = trim($value);
        
        $value = substr($value, 0, ($posfim-($posini-1)));
        
        if(!isset($picture))
        {
            echo "Tipo de informação não fornecido";
            exit;
        }
        
        if($picture == 'text'){
            if(isset($value))
            {
                $value = str_replace(' ', '*', $value);
                $value = $this->text($value, ($posfim-($posini-1)));
            }
        }
        
        if($picture == 'int'){
            if(isset($value))
            {
                $value = $this->zeros($value, ($posfim-($posini-1)));
            }
        }

        $this->setLogValues($value);
        $this->validateSizeValue($value);
        $this->cnab[$GLOBALS['CNAB_LINE']] = substr_replace($this->cnab[$GLOBALS['CNAB_LINE']], $value, $posini-1, ($posfim-($posini-1))); 
    }



    public function newRegister(){
        $GLOBALS['CNAB_LINE'] = $GLOBALS['CNAB_LINE'] + 1;

        $this->cnab[$GLOBALS['CNAB_LINE']] = '';

        for($i=0; $i < $GLOBALS['CNAB']; $i++){ 
            $this->cnab[$GLOBALS['CNAB_LINE']] .= "#"; 
        }
        return true;
    }

    public function setBlank($posini, $posfim)
    {
        $str = $this->text('*', ($posfim-($posini-1)));
        return $this->cnab[$GLOBALS['CNAB_LINE']] = substr_replace($this->cnab[$GLOBALS['CNAB_LINE']], $str, $posini-1, ($posfim-($posini-1))); ;
    }

    public function text($str, $nr)
    {
        $str = strtoupper($str);
        $str = str_pad($str, $nr, '*', STR_PAD_RIGHT); 
        return $str;
    }

    public function zeros($str, $nr)
    {
        return str_pad($str, $nr, "0", STR_PAD_LEFT); 
    }
        







    /*
    * Configurações para LOG de Erros
    */

    public function setLog($bool)
    {
        $GLOBALS['SHOW_LOG'] = $bool;
    }
    

    public function setLogValues($value)
    {
        $GLOBALS['LOG_VALUES'][] = $value;
    }

    public function validateSizeValue($value)
    {
        $GLOBALS['LOG_VALUES_ERROS'][] = 'OK'; 
    }

    public function showLogValuesErros()
    {
        foreach($GLOBALS['LOG_VALUES_ERROS'] as $idValue => $erros){
            if($erros <> 'OK'){
                echo '<br>';
                echo $GLOBALS['LOG_VALUES'][$idValue];
                echo '<br>';
                echo $erros;
                echo '<br><br>';
            }
        }
    }




}