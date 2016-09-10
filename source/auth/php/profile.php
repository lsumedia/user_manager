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

$auth = new authenticator();

$profile = $auth->profile();

$groups = authenticator::server_get_groups();

//Display success message
if(isset($_GET['updated'])){ ?>
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

if(isset($_GET['goodpassword'])){ ?>
    <div class="form_row success">
        <h4>Password changed</h4>
    </div>
    <?php
}

if(isset($_GET['badpassword'])){ ?>
    <div class="form_row error">
        <h4>Password must be at least 8 characters long</h4>
    </div>
    <?php
}

?>
<!-- Edit profile form -->
<div class="form_row">
    <h4>Edit profile</h4>
</div>
<form action="../request_login.php?action=update_profile" method="POST" class="row" autocomplete="false">
    
    <div class="col s12">
        <label for="username_field">Username</label>
        <input id="username_field" type="text" class="disabled" readonly value="<?= $profile['username'] ?>" />
    </div>
    <div class="col s12">
        <label for="name_field">Display name</label>
        <input id="name_field" type="text" name="fullname" value="<?= $profile['fullname'] ?>" />
    </div>
    <div class="col s12">
        <label for="email_field">Email</label>
        <input id="email_field" type="email" name="email" value="<?= $profile['email'] ?>" />
    </div>
    <div class="col s12">
        <label for="dpurl_field">Profile picture URL (Leave blank to use Gravatar)</label>
        <input autocomplete="off" id="dp_url_field" type="url" name="dp_url" value="<?= $profile['raw']['dp_url'] ?>" />
    </div>
    <div class="input-field col s12">
        <textarea class="materialize-textarea" id="bio" name="bio" ><?= $profile['bio'] ?></textarea>
        <label for="bio">Biography</label>
    </div>

    <!-- Password change section -->
    <div class="col s12 border"></div>
    <div class="col s12">
            <label for="password_field">New password</label>
            <input id="password_field" type="text" name="password" onfocus="this.type = 'password'; //Prevent autocomplete"/>
    </div>
    
    <input type="hidden" name="key" value="<?= $auth->key ?>" />
    <div class="col s12">
        <input class="btn green" type="submit" value="Save changes"/>
    </div>
</form>

<div class="row">
    <div class="col s12 m6 l6">
        <!-- Permissions info section -->
        <div class="form_row">
            <h4>Your groups</h4>
        </div>
        <ul>
            <?php
            foreach($profile['group_ids'] as $group_id){
                echo "<li>{$groups[$group_id]['group_name']}</li>";
            }
            ?>
        </ul>
    </div>

    <div class="col s12 m6 l6">
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
</div>

<script>
$(document).ready(function(){
   $('#bio').trigger('autoresize'); 
});
</script>