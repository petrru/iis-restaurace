<div class="info">
    <p>Pre vytvorenie rezervácie prosím vyplňte nasledujúce údaje<br><br>Údaje s označením '*' sú povinné</p>
</div>

<div class="login_form">
    <form action="reservation" method="post">
        Vaše meno:<br>
        <input type="text" name="customer_name" value="<?php
        echo htmlentities($_POST['customer_name'] ?? ''); ?>"><br>
        Deň rezervácie:<br>
        <input type="text" name="reservation_day" value="<?php
        echo htmlentities($_POST['reservation_day'] ?? ''); ?>"><br>
        Čas rezervácie:<br>
        <input type="text" name="reservation_time" value="<?php
        echo htmlentities($_POST['reservation_time'] ?? ''); ?>"><br>
        Počet ľudí:<br>
        <input type="text" name="people_amount" value="<?php
        echo htmlentities($_POST['people_amount'] ?? ''); ?>"><br>
        Miestnosť:<br>
        <?php
            $room = new Room();
            $q = $room->select("SELECT room_id, description FROM rooms");
            $q->execute();

            while ($q->fetch()) {
                echo "<label><input type=\"radio\" name=\"reservation_room\" value=\"$room->room_id\"";
                if (($_POST['reservation_room'] ?? '-') == $room->room_id) {
                    echo " checked";
                }
                echo ">$room->description</label><br>";
            }

        ?>
        Vaše telefónne číslo:<br>
        <input type="text" name="customer_phone" value="<?php
        echo htmlentities($_POST['customer_phone'] ?? ''); ?>"><br>
        Váš email:<br>
        <input type="text" name="customer_email" value="<?php
        echo htmlentities($_POST['customer_email'] ?? ''); ?>"><br>

        <div class="button">
            <input type="submit" value="Rezervovať">
        </div>
    </form>
</div>

