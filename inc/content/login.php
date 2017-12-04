<?php if (isset($_SESSION['invalid_password'])): ?>
<div class="incorrect_data">
    Nesprávne meno alebo heslo.
</div>
<?php unset($_SESSION['invalid_password']); endif; ?>

<div class="login_form">
    <form action="login" method="post">
        Prihlasovacie meno:<br>

        <input type="text" name="username" value="<?php
            echo $_POST['username'] ?? ''; ?>"><br>
        Heslo:<br>
        <input type="password" name="password"><br>
        <?php
            if (isset($_GET['back'])) {
                $back = htmlspecialchars(base64_decode($_GET['back']));
                echo "<input type='hidden' name='back' value='$back'>";
            }
            elseif (isset($_POST['back'])) {
                $back = htmlspecialchars($_POST['back']);
                echo "<input type='hidden' name='back' value='$back'>";
            }
        ?>
        <div class="button">
            <input type="submit" value="Prihlásiť sa">
        </div>
    </form>
</div>
