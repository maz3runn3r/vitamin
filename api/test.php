<?php

//echo mb_convert_encoding('Malmö', "UTF-8", mb_detect_encoding('Malmö', "UTF-8, ISO-8859-1, ISO-8859-15", true));

echo mb_convert_encoding('Malmö', "ISO-8859-1", mb_detect_encoding('Malmö', "UTF-8, ISO-8859-1, ISO-8859-15", true));

?>