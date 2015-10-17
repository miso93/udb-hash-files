<?php
require_once(__DIR__.'/header.php');
require_once(__DIR__.'/define.php');
require_once(__DIR__.'/EncryptionData.php');

if(isset($_GET['fileInput'])){
//    $uploaddir = __DIR__.'/files/hash/';
//    $file = $uploaddir . basename($uploaddir.$_GET['fileInput'].'.encrypt');
    $uploaddir = __DIR__.'/files/hash/';
    $uploadfile = $uploaddir . basename($_FILES['fileInput']['name']);

    ?>
    <div class="col-sm-12">
        <?php
        if (move_uploaded_file($_FILES['fileInput']['tmp_name'], $uploadfile)) {
            echo 'File is valid, and was successfully uploaded.';
        } else {
            echo "Possible file upload attack!\n";
            die();
        }
        ?>
    </div>

<?php
    if(file_exists($uploadfile)){
        $decrypted_string = EncryptionData::mc_decrypt(file_get_contents($uploadfile), ENCRYPTION_KEY);
        if($decrypted_string == -1){
            echo 'iv size is different !';
        }
        if($decrypted_string == -2){
            echo 'Hash mac is different !';
        }
        else {
            echo 'Decrypted data:' .'<br>';
            echo  '<textarea class="form-control" rows="5">'.$decrypted_string.'</textarea>';

            ?>
            <div class="col-sm-12">
                <p></p>
                <a class="btn btn-success" href="/download.php?file=<?= basename($_FILES['fileInput']['name']) ?>">Download data as file</a>
            </div>
            <?php
        }
    } else {
        echo 'File doesn\'t exist';
    }

} else {
    ?>
    <div class="col-sm-12">
        Please select file for decryption.
    </div>
    <div class="col-sm-12">
        <form class="form form-horizontal" action="" method="post" enctype="multipart/form-data" style="margin-top: 30px;">
            <div class="form-group">
                <label class="control-label col-sm-2" id="file">File</label>
                <div class="col-sm-8">
                    <input id="input-id" type="file" class="file" name="fileInput">
                </div>
            </div>
        </form>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $("#input-id").fileinput();

        });

    </script>
    <?php
}
require_once(__DIR__.'/footer.php');