<?php

/* 
 * The MIT License
 *
 * Copyright 2016 Cameron.
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


   function js_import(){
        
            $dir = './js';

            $comp_includes = scandir($dir);

            foreach($comp_includes as $comp_ifile){
                if(substr($comp_ifile, (strlen($comp_ifile)-3),3) == ".js"){
                    echo "<script src=\"js/$comp_ifile\" type=\"text/javascript\"></script>", PHP_EOL;
                }
                if(strpos($comp_ifile, '.js') !== false){
                    //echo "<script src=\"js/$comp_ifile\" type=\"text/javascript\"></script>", PHP_EOL;
                }
            }
    }