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

require_once('../app/components/authenticator.php');
require_once('../app/config.php');

$auth = new authenticator();

$profile = $auth->profile();

?>
 <div class="form_row">
        <h4>Edit profile</h4>
    </div>
<form action="../request_login.php?action=update" method="POST">
    <div class="form_row">
        <p>Display name</p>
        <input type="text" name="username" value="<?= $profile['fullname'] ?>" />
    </div>
    <div class="form_row">
        <p>Email</p>
        <input type="email" name="email" value="<?= $profile['email'] ?>" />
    </div>
    <div class="form_row">
        <p>Profile picture URL (Leave blank to use Gravatar)</p>
        <input type="url" name="dp_url" value="<?= $profile['dp_url'] ?>" />
    </div>
    <div class="form_row">
        <input type="submit" value="Save changes"/>
    </div>
</form>
<div>
    <div class="form_row">
        <h4>Your permissions</h4>
    </div>
    <ul>
        <?php
        foreach($profile['permissions'] as $permission){
            echo "<li>{$permissions[$permission]}</li>";
        }
        ?>
    </ul>
</div>