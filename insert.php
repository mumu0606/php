<?php
    /* 自動でlapyutaデータベースの作成からデータの挿入まで完了します */
    //MySQLサーバ接続に必要な値を変数に代入
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $db_name = 'lapyuta';

    // 変数を設定して、MySQLサーバに接続
    $database = mysqli_connect($host, $username, $password, $db_name);

    // 接続を確認し、接続できていない場合にはエラーを出力して終了する
    if ($database == false) {
        die('Connect Error (' . mysqli_connect_errno() . ') ' . mysqli_connect_error());
    }

    // MySQL に utf8 で接続するための設定をする
    $charset = 'utf8';
    mysqli_set_charset($database, $charset);

/*
    //lapyutaデータベースの作成
    function create_lapyuta_database(){
        $sql = 'create database lapyuta';
        $statement = mysqli_prepare($database, $sql);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
    }
*/

    //menuテーブルの作成
    function create_menu_table(){
        $sql = 'create table menu ( name varchar(32), energy int, protein int)';
        $statement = mysqli_prepare($database, $sql);
        mysqli_stmt_execute($statement);
        mysqli_stmt_close($statement);
    }

    //jsonファイルあたりからデータを取得しmenuテーブルに格納する
    function get_from_json_to_menu(){
        $url = "menu.json";
        $fp = fopen($url, 'r') or die("can not open this file by hiroki");
        while(($line = fgets($fp)) != false){
            //jsonデータで文字化けを起こさないように
            $json = mb_convert_encoding($line, 'UTF8', 'ASCII,JIS,UTF-8,EUC-JP,SJIS-WIN');
            //jsonデータを連想配列に
            $arr = json_decode($json,true);

            //sql実行してデータベースにデータ格納
            $sql = 'insert into lapyuta.menu (name, energy, protein) values (?, ?, ?)';
            $statement = mysqli_prepare($database, $sql);
            mysqli_stmt_bind_param($statement, 'sii', $arr['name'], $arr['energy'], $arr['protein']);
            mysqli_stmt_execute($statement);
            mysqli_stmt_close($statement);
        }
    }

    //ユーザーテーブルの作成

    //create_lapyuta_database();
    create_menu_table();
    get_from_json_to_menu();
?>