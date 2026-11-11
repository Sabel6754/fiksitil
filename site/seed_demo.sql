SET NAMES utf8mb4;
-- Données de TEST (fictives). À importer APRÈS schema.sql, puis supprimer ce fichier du serveur.
-- Admin : téléphone 37000000. Prestataires de test : mot de passe Demo1234.

INSERT INTO providers (name,phone,password_hash,category_id,zone,price,status,is_admin,paid_until) VALUES
('Admin','37000000','$2y$10$CNruF7Ncnxmv5BmsSiLtE.tfrqPMRElr7jSHVjxRx0QizU.9ekgrq',1,'Pillon',0,'active',1,NULL);

INSERT INTO providers (name,phone,password_hash,category_id,zone,price,price_note,description,status,paid_until) VALUES
('Sabel Coiffure (TEST)','37000001','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',1,'Pillon',500,'par coupe, à domicile','Coiffeur à domicile : coupe homme, dégradé, barbe. Je me déplace avec mon matériel.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Jean Plombier (TEST)','37000002','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',2,'Inch',1500,'déplacement inclus','Fuites, robinets, tuyauterie, installation de chauffe-eau et de citernes.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Marc Électricité (TEST)','37000003','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',3,'Saint-Raphaël',2000,'selon le travail','Installation électrique, panneaux solaires, réparation de pannes, mise aux normes.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Starlink Pro (TEST)','37000004','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',4,'Pillon',3000,'installation complète','Installation et configuration de Starlink, Wi-Fi, routeurs et répéteurs.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Sécurité Caméras (TEST)','37000005','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',5,'Inch',2500,'par caméra posée','Installation et réglage de caméras de surveillance avec accès depuis le téléphone.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Frigo Service (TEST)','37000006','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',6,'Saint-Raphaël',1000,'diagnostic','Réparation de réfrigérateurs, congélateurs, climatiseurs et machines à laver.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Cordonnier Pierre (TEST)','37000007','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',7,'Pillon',300,'par paire','Réparation de souliers, sandales, sacs. Récupération et livraison à domicile.','active',DATE_ADD(CURDATE(),INTERVAL 30 DAY)),
('Maçon Joseph (TEST)','37000008','$2y$10$byStcbxyrBYstsTK6UQmBuKaOjdQpUzJ42KyA5cR/I27BedzxfV8C',8,'Inch',2500,'par jour','Murs, dalles, carrelage, réparations de maison.','pending',NULL);

INSERT INTO orders (provider_id,client_name,client_phone,client_zone,address,when_text,note,price,commission,status) VALUES
(2,'Client Test A','37111111','Pillon','Près de l\'église','Demain à 10h','Coupe + barbe',500,50,'new'),
(2,'Client Test B','37222222','Pillon','Maison bleue, route principale','Samedi 14h',NULL,500,50,'done'),
(3,'Client Test C','37333333','Inch','Derrière le marché','Aujourd\'hui','Fuite sous l\'évier',1500,150,'accepted');
