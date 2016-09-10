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
?>

<style>
   body{
        background-image:url('<?= $config['login_background'] ?>');
        background-size:cover;
    }
</style>
<div class="row">
    <div class="login-card card z-depth-3 login-form col s12 l8 offset-l2">
        <form action="../request_login.php?action=register" method="POST" id="register_form">
            <div class="card-content">
                <p class="center-align"><img class="main-logo" src="res/media_logo.png" /></p>
                <h4 class="center">Registration</h4>
                <br />
                <label for="fullname_field">Full name</p>
                <input type="text" id="fullname_field" name="username" />
                <label for="email_field">Email address</p>
                <input type="text" id="email_field" name="email" />
                <label for="password_field">Password</label>
                <input type="password" id="password_field" name="password" />
                <?php
                    if(isset($_GET['error'])){
                        echo '<p class="red-text">Error - registration not allowed</p>';
                    }
                ?>
            </div>
            <div class="card-action">
                <!-- <input type="submit" class="btn-flat right" value="Sign in"/> -->
                <a>&nbsp;</a><!-- lord forgive me-->
                
                <a class="right blue-text" href="javascript:void(0);">Register</a>
                <a class="right blue-text" href="./?p=login">Sign In instead</a>
            </div>
            <input type="hidden" value="<?= $_GET['redirect'] ?>" name="redirect" />
        </form>
    </div>
</div>