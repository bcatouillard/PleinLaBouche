<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


function actionAccueil($twig){
    echo $twig->render('index.html.twig', array());
}

function actionMaintenance($twig){
    echo $twig->render('maintenance.html.twig', array());
}

function actionActualites($twig,$db){
    $menu = new Menu($db);
    $menuduJour = $menu->selectByDate();
    $form = array();
    $form['menu'] = $menuduJour;
    echo $twig->render('actualites.html.twig', array('form'=>$form));
}

function actionProduits($twig, $db){
    $form = array();
    $produit = new Produit($db);
    $liste = $produit->selectAll();
    $form['lesProduits'] = $liste;
    $form['directory'] = "images/produit/";
    echo $twig->render('plats.html.twig', array('form'=>$form));
}

function actionContact($twig){
    $form = array();
    if(isset($_POST['btEnvoyer'])){
        $mail_to = "catouillard.benjamin@gmail.com";
        $mail_from = $_POST['mail'];
        $name = $_POST['name'];
        $prenom = $_POST['fname'];
        $name = $prenom ." ". $name;
        $tel = $_POST['tel'];
        $msg = $_POST['msg'];
        $body = "Email provenant de : ". $name ."<br>";
        $body .= "Adresse mail : ". $mail_from. " et numéro de téléphone : ".$tel."<br>";
        $body .= "Contenu du message : ".$msg ."<br>";
        echo $body;
        $captcha = $_POST['g-recaptcha-response'];
        $secretKey = "6Le1h4cUAAAAAKgoN0WV7T1vnQuP08iakHH873Yu";
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                'secret' => $secretKey,
                'response' => $captcha
            )
        ));
        $response = curl_exec($curl);
        
        if(strpos($response, '"success": true') != false) {
            $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
            try {
                //Server settings
                $mail->CharSet = 'UTF-8';
                //$mail->SMTPDebug = 1;                                 // Enable verbose debug output
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'catouillard.benjamin@gmail.com';                 // email utilisateur
                $mail->Password = 'Belline62Cocalife';                           // mdp utilisateur
                $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 587;                                    // TCP port to connect to
                //Recipients
                $mail->setFrom($mail_from, $name);
                $mail->addAddress('catouillard.benjamin@gmail.com');     // Email de réception
                //Content
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = "Formulaire de contact";
                $mail->Body = $body;
                $mail->send();
            }
            catch(Exception $e){
                echo $e;
            }
        }
        curl_close($curl);
    }
    echo $twig->render('contact.html.twig', array('form'=>$form));
}

function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
    }
?>