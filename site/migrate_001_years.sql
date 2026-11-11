-- À exécuter UNE fois sur une base déjà installée (les nouvelles installations utilisent schema.sql).
ALTER TABLE providers ADD COLUMN years TINYINT UNSIGNED DEFAULT NULL AFTER description;
