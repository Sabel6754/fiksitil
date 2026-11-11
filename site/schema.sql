SET NAMES utf8mb4;

CREATE TABLE categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(60) NOT NULL,
  icon VARCHAR(8) NOT NULL DEFAULT '🛠️'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE providers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  phone VARCHAR(20) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  category_id INT NOT NULL,
  zone VARCHAR(60) NOT NULL,
  price INT NOT NULL,
  price_note VARCHAR(80) DEFAULT NULL,
  description TEXT,
  status ENUM('pending','active','suspended') NOT NULL DEFAULT 'pending',
  paid_until DATE DEFAULT NULL,
  is_admin TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  provider_id INT NOT NULL,
  client_name VARCHAR(100) NOT NULL,
  client_phone VARCHAR(20) NOT NULL,
  client_zone VARCHAR(60) NOT NULL,
  address VARCHAR(255) NOT NULL,
  when_text VARCHAR(100) NOT NULL,
  note TEXT,
  price INT NOT NULL,
  commission INT NOT NULL DEFAULT 0,
  commission_paid TINYINT(1) NOT NULL DEFAULT 0,
  status ENUM('new','accepted','done','declined','cancelled') NOT NULL DEFAULT 'new',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (provider_id) REFERENCES providers(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categories (name, icon) VALUES
('Coiffeur / Coiffeuse','💈'),('Plombier','🚰'),('Électricien','💡'),
('Installation Starlink / Internet','📡'),('Caméras de surveillance','📷'),
('Réparation de frigo / électroménager','🧊'),('Cordonnier','👞'),
('Maçon / Construction','🧱'),('Menuisier','🪚'),('Peintre','🎨'),
('Mécanicien','🔧'),('Couture / Tailleur','🧵'),('Autre service','✨');
