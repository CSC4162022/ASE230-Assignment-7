<?php
    include "./Entity.php";
    // Create
    /*
    Entity::new_entity('beatles.csv.php', 'csv', ['firstname'=>'George','lastname'=>'Harrison']);  //append new entity
    Entity::new_entity('beatles.csv.php', 'csv', ['c'=>['firstname'=>'George','lastname'=>'Harrison']], true, true); //overwrite with new entity - associated array
    Entity::new_entity('beatles.csv.php', 'csv', [['firstname'=>'John','lastname'=>'Lennon'],['firstname'=>'Paul','lastname'=>'McCartney']]); //append new entity
    */
    // Get single entity from file
    //Entity::get_entity('beatles.csv.php', 'csv', 1);

    // Get all items from file
    //Entity::get_entities('beatles.csv.php', 'csv');

    //MODIFY
    /*
    Entity::modify_entity('beatles.csv.php', 'csv', 0, ['firstname'=>'Thom','lastname'=>'Marbles'], false);  //modify/append first index - overwrite false
    Entity::modify_entity('beatles.csv.php', 'csv', 10000, ['firstname'=>'Grog','lastname'=>'Frog']); //try unallocated index
    Entity::modify_entity('beatles.csv.php', 'csv', 2, ['Key1'=>['firstname'=>'George','lastname'=>'Harrison'],
        'Key2'=>['firstname'=>'Ringo','lastname'=>'Starr']], true); //modify 3rd index - overwrite true
    */
    //add new entities for deletion
    //Entity::new_entity('beatles.csv.php', 'csv', [['firstname'=>'John','lastname'=>'Lennon'],['firstname'=>'Paul','lastname'=>'McCartney']]); //append new entity

    //DELETE
    //Entity::delete_entity('beatles.csv.php', 'csv', 0); // delete 1st entity
    //Entity::delete_entity('beatles.csv.php', 'csv', -11)); //attempt delete unallocated index
    //Entity::delete_entity('beatledddddds.csv.php', 'csv', 2)); //attempt invalid file name
    //Entity::delete_entity('beatles.csv.php', 'csv', 2); // delete 3rd entity
