<?php


class group_page extends page{

    public $name = 'groups';
    public $title = 'Groups';
    
    public function content() {
        
        if(isset($_GET['id'])){
            //Edit group page
            
            $group_id = $_GET['id'];
            
            $e_group = new group($group_id);
            
            ?>
            <!-- Breadcrumbs -->
            <ol class="breadcrumb">
              <li><a href="./?p=groups">Groups</a></li>
              <li><a href="./?p=groupss&id=<?= $group_id ?>">Edit <?= $e_group->group_name ?></a></li>
            </ol>
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <h3>Edit group</h3>
                </div>
            </div>
            <?php
            
            self::edit_group_form($e_group);
            
            
        }else{
            //List groups
            
            $groups = group::list_all_raw();
            
            $group_list = new ajax_list($groups, "group_list");
            $group_list->display();
        }
        
        
    }
    
    public function header_content() {
         if(isset($_GET['id'])){
            $this->title = "";
        }
    }
    
    public static function edit_group_form($group){
        ?>
        <div class="row">
            <form method="POST" action="./?p=groups&id=<?= $group->group_id ?>" class="col-lg-12 col-sm-12">
                <div class="form-group">
                    <label for="group_name">Group name</label>
                    <input type="text" class="form-control" name="group_name" value="<?= $group->group_name ?>" id="group_name"/>
                </div>
                <div class="form-group">
                    <label for="group_dec">Group description</label>
                    <input type="text" class="form-control" name="description" value="<?= $group->description ?>" id="group_desc"/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success pull-right">Save changes</button>
                </div>
            </form>
        </div>    
        <?php
    }
}
