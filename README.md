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
