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

//Display success message
if(isset($_GET['updated'])){
?>
<div class="form_row success">
    <h4>Saved changes</h4>
</div>
<?php
}

//Display error message
if(isset($_GET['error'])){
?>
<div class="form_row error">
    <h4>An error occurred</h4>
</div>
<?php
}

?>
<!-- Edit profile form -->
<div class="form_row">
    <h4>Edit profile</h4>
</div>
<form action="../request_login.php?action=update_profile" method="POST">
    <div class="form_row">
        <p>Username</p>
        <input type="text" class="disabled" readonly value="<?= $profile['username'] ?>" />
    </div>
    <div class="form_row">
        <p>Display name</p>
        <input type="text" name="fullname" value="<?= $profile['fullname'] ?>" />
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
        <p>Biography</p>
    </div>
    <textarea name="bio" ><?= $profile['bio'] ?></textarea>
    
    <input type="hidden" name="key" value="<?= $auth->key ?>" />
    <div class="form_row">
        <input type="submit" value="Save changes"/>
    </div>
</form>
<div class="form_row border"></div>
<!-- Password reset form -->
<div class="form_row">
        <p>New password</p>
        <input type="password" name="password" />
</div>
<div class="form_row">
        <input type="submit" value="Change password"/>
    </div>
<div>
<!-- Permissions info section -->
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