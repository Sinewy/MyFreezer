<footer class="loginFooter">
	<div>Made By <a href="http://www.jurezilavec.com">JZInfo</a>, Copyright © <?php echo date("Y"); ?></div>
</footer>

</body>

</html>

<?php
if (isset($dbc)) {
	mysqli_close($dbc);
}
?>