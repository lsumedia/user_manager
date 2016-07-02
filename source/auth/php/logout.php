<h4>You are being logged out</h4>

<?php if(isset($_GET['key'])){ ?>
<form id="logout-form" action="../request_login.php?action=logout" method="POST" >
    <input type="hidden" value="<?= $_GET['key'] ?>" name="key" />
    <input type="hidden" value="<?= $_GET['source'] ?>" name="source" />
</form>

<script>
    var form = document.getElementById('logout-form');
    form.submit();
</script>
<?php }else{ ?>

<p>
    Error occurred - no login key was specified.
</p>

<?php } ?>
