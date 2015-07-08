<?php
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
    /* Controle de jogadores e fichas de poker, na modalidade cash game. */
    /*              Desenvolvido por: Reinaldo Silveira                  */
    /* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */


    if (!isset($_SESSION))
       session_start();
       
    $aux = time() . rand(1, 99999);

    $codigoCaptcha = substr(md5($aux), 0, 7);

    $_SESSION['captcha'] = $codigoCaptcha;
    
    
    $imagensCaptcha[0] = "fundocaptcha1.png";
    $imagensCaptcha[1] = "fundocaptcha2.png";
    $imagensCaptcha[2] = "fundocaptcha3.png";
    $imagensCaptcha[3] = "fundocaptcha4.png";

    $imagemCaptcha = imagecreatefrompng($imagensCaptcha[rand(0, 3)]);

    $fonteCaptcha = imageloadfont("anonymous.gdf");

    $corCaptcha = imagecolorallocate($imagemCaptcha, rand(0, 255), rand(0, 150), rand(0, 200));

    imagestring($imagemCaptcha, $fonteCaptcha, 35, 5, $codigoCaptcha, $corCaptcha);


    header("Content-type: image/png");

    imagepng($imagemCaptcha);

    imagedestroy($imagemCaptcha);

?>