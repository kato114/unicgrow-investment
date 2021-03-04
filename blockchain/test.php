<?php
$image = "https://chart.googleapis.com/chart?chs=300*300&cht=qr&chl=bitcoin:3CieWDAgFCpDgGnTsivYcAu8SWje9QB3Nb?amount=0.0009";
$image = "http://chart.apis.google.com/chart?cht=qr&chs=300x300&chl=bitcoin:3CieWDAgFCpDgGnTsivYcAu8SWje9QB3Nb?amount=0.0009";
        $imageFile = file_get_contents($image);
        $imageData = base64_encode($imageFile);
        $src = 'data: '.mime_content_type($image).';base64,'.$imageData;
        print '<img src="' . $src . '">';