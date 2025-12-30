<?php
session_start();
// SQLite DB initializer and helper
function getDB(){
    static $db = null;
    if($db) return $db;
    $path = __DIR__ . '/data.sqlite';
    $needSeed = !file_exists($path);
    $db = new PDO('sqlite:' . $path);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if($needSeed){
        $sql = file_get_contents(__DIR__ . '/migrations.sql');
        $db->exec($sql);
        // seed default admin
        $stmt = $db->prepare("SELECT COUNT(*) FROM users");
        $stmt->execute();
        if($stmt->fetchColumn() == 0){
            $pw = password_hash('admin123', PASSWORD_DEFAULT);
            $ins = $db->prepare("INSERT INTO users (email, name, password, role, created_at) VALUES (?, ?, ?, ?, datetime('now'))");
            $ins->execute(['admin@nightshade.test', 'Night Shade Admin', $pw, 'admin']);
        }
    } else {
        // If DB already exists, ensure seeded services exist (useful when migrations.sql was updated later)
        try{
            $cnt = $db->query('SELECT COUNT(*) FROM services')->fetchColumn();
            if($cnt < 6){
                $seed = [
                    ['NS303','Night Shade Premium','CGK','KNO','2026-01-07 15:45:00',1500000,100],
                    ['NS404','Night Shade Comfort','SUB','DPS','2026-01-08 09:15:00',1100000,150],
                    ['NS505','Night Shade Sunrise','DPS','CGK','2026-01-09 06:30:00',1300000,140],
                    ['NS606','Night Shade Midday','KNO','SUB','2026-01-09 12:00:00',1050000,160],
                    ['NS707','Night Shade Evening','CGK','MLG','2026-01-10 18:20:00',1400000,120],
                    ['NS808','Night Shade Weekend','SUB','BPN','2026-01-11 07:50:00',1600000,130]
                ];
                $ins = $db->prepare('INSERT INTO services (code,title,origin,destination,depart_at,price,seats) VALUES (?,?,?,?,?,?,?)');
                foreach($seed as $s){
                    // avoid duplicates by code
                    $exists = $db->prepare('SELECT COUNT(*) FROM services WHERE code = ?');
                    $exists->execute([$s[0]]);
                    if($exists->fetchColumn() == 0){
                        $ins->execute($s);
                    }
                }
            }
            // If some services were seeded earlier with small numbers, scale them up by 10000
            $db->exec("UPDATE services SET price = price * 10000 WHERE price < 10000");
        }catch(Exception $e){
            // ignore if services table missing or other errors
        }
        // Seed dummy bookings for each day of 2025 if not present
        try{
            $has2025 = $db->prepare("SELECT COUNT(*) FROM bookings WHERE date(created_at) BETWEEN '2025-01-01' AND '2025-12-31'");
            $has2025->execute();
            if($has2025->fetchColumn() == 0){
                $db->beginTransaction();
                // get service ids
                $svcRows = $db->query('SELECT id FROM services')->fetchAll(PDO::FETCH_COLUMN);
                if(!$svcRows) $svcRows = [1];
                $insB = $db->prepare('INSERT INTO bookings (user_id,service_id,passengers,contact,status,created_at,reference) VALUES (?,?,?,?,?,?,?)');
                $contacts = ['guest1@example.com','guest2@example.com','guest3@example.com','guest4@example.com','guest5@example.com'];
                $start = new DateTime('2025-01-01');
                $end = new DateTime('2025-12-31');
                for($d = clone $start; $d <= $end; $d->modify('+1 day')){
                    // random number of bookings for the day (0-12), but ensure some activity
                    $count = mt_rand(0,12);
                    for($i=0;$i<$count;$i++){
                        $svc = $svcRows[array_rand($svcRows)];
                        $pass = mt_rand(1,4);
                        $contact = $contacts[array_rand($contacts)];
                        $status = 'booked';
                        $created = $d->format('Y-m-d') . ' ' . str_pad(mt_rand(6,22),2,'0',STR_PAD_LEFT) . ':' . str_pad(mt_rand(0,59),2,'0',STR_PAD_LEFT) . ':' . str_pad(mt_rand(0,59),2,'0',STR_PAD_LEFT);
                        $ref = strtoupper(substr(md5($created . $svc . mt_rand()),0,8));
                        $insB->execute([null,$svc,$pass,$contact,$status,$created,$ref]);
                    }
                }
                $db->commit();
            }
        }catch(Exception $e){
            // ignore
            if($db->inTransaction()) $db->rollBack();
        }
    }
    return $db;
}

function current_user(){
    if(!empty($_SESSION['user_id'])){
        $db = getDB();
        $stmt = $db->prepare('SELECT id, email, name, role FROM users WHERE id = ?');
        $stmt->execute([$_SESSION['user_id']]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    return null;
}

function require_login(){
    if(empty($_SESSION['user_id'])){
        header('Location: login.php'); exit;
    }
}

function require_admin(){
    $u = current_user();
    if(!$u || ($u['role'] !== 'admin')){
        header('Location: ../index.php'); exit;
    }
}

?>
