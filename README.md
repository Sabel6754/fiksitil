# Fiksitil

Application mobile de mise en relation avec des professionnels à domicile
(électricien, plombier, réparateur, etc.). Prototype front avec données fictives.

Stack : React Native + Expo (compatible Expo Go), TypeScript, React Navigation.

## Lancer le projet

```bash
npm install
npx expo start
```

Scannez ensuite le QR code avec l'application **Expo Go** (iOS/Android).

## Écrans

Accueil, Services, Missions, Messages, Profil, liste des pros, fiche d'un pro.

## Structure

- `App.tsx` : point d'entrée
- `src/navigation.tsx` : onglets et pile de navigation
- `src/screens/` : écrans
- `src/components/` : composants réutilisables
- `src/data.ts` : données fictives
- `src/theme.ts` : couleurs et styles

## Site Fiksitil (version à lancer sur Hostinger) — dossier `site/`

PHP + MySQL, sans framework, adapté au mobile. Fonctionnalités : catégories, recherche par zone,
profils de prestataires, commande à domicile sans compte client (avec lien WhatsApp), espace
prestataire, administration (activation des places payées par MonCash/NatCash, suivi des commissions).

### Mise en ligne sur Hostinger
1. hPanel > Bases de données MySQL : créez une base et un utilisateur.
2. phpMyAdmin : importez `site/schema.sql`.
3. Copiez `site/config.php.example` en `site/config.php` et remplissez les valeurs (base, numéros MonCash/NatCash, WhatsApp support, prix).
4. Envoyez le contenu de `site/` dans `public_html/` (Gestionnaire de fichiers ou Git).
5. Ouvrez `https://votre-domaine/install.php` pour créer l'admin, puis **supprimez `install.php`**.

## Page d'accueil statique (Fiksitil) — `index.html`

**Avec Fiksitil, tout s’arrange.**

Fiksitil est une marketplace locale de services à domicile pour **Pignon, Hinche, Saint-Raphaël** et les zones voisines.

### Première version

La landing page actuelle présente :

- la recherche de services ;
- les catégories principales ;
- le parcours client en trois étapes ;
- l’inscription des prestataires intéressés ;
- le logo officiel Fiksitil ;
- une interface responsive compatible avec Hostinger.

### Stack de départ

- HTML5
- CSS3
- JavaScript vanilla

Cette base servira ensuite à intégrer PHP/Laravel, MySQL, les comptes prestataires, les demandes de service et les paiements NatCash/MonCash.

### Structure

- `index.html`
- `assets/images/fixitil-logo.png`
- `assets/css/style.css`
- `assets/js/app.js`

### Déploiement Hostinger

Téléverser le contenu du dépôt dans le dossier `public_html`. Le point d’entrée est `index.html`.
