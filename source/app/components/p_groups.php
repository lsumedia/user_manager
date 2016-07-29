<?php


class group_page extends page{

    public $name = 'groups';
    public $title = 'Groups';
    
    public function content() {
        
        $group = new group(1);
        
        var_dump($group);
        
        foreach($_POST['permissions'] as $perm){
            echo $perm;
        }
        
        ?>
<form method="POST" action="">
    Submit<input type="checkbox" name="permissions" value="lsutv_submit_content" /><br />
    Edit<input type="checkbox" name="permissions" value="lsutv_edit_content" /><br />
    BCC<input type="checkbox" name="permissions" value="lsutv_manage_channels" /><br />
    <input type="submit" />
</form>
<?php
        
    }
    
    public function header_content() {
    }
}
