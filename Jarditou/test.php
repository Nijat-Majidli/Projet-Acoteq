<?php

    require "connection_bdd.php";
        
    // $req = $db->prepare('SELECT user_blocked FROM users WHERE user_login = :user_login');

    // $req->execute(array(':user_login' => 'Nijat'));
    
    // $reponse = $req->fetch();  

    // var_dump($reponse);

        
    // echo time() + (1*1*60*60);

    // $req = $db->query('SELECT user_unblock_time FROM users');
    
    // $reponse = $req->fetch();

    // echo $reponse['user_unblock_time'];


    echo time();
    
    echo '<br>';

    $unblock_time = time() + (1*1*3*60);
    
    echo $unblock_time;


    
    

    // var_dump($reponse);


    // $nbLigne = $result->rowCount();   

    // while($row = $result->fetch(PDO::FETCH_OBJ))  
    // {
    //     var_dump($row);
    // }

    
   












    ?>