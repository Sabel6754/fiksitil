import { Ionicons } from '@expo/vector-icons';
import type { ComponentProps } from 'react';

export type IconName = ComponentProps<typeof Ionicons>['name'];

export type Pro = {
  id: string;
  name: string;
  trade: string;
  years: number;
  rating: number;
  reviews: number;
  rate: number;
  customers: number;
  about: string;
  color: string;
};

export const categories: { id: string; label: string; icon: IconName }[] = [
  { id: 'repair', label: 'Réparation', icon: 'construct-outline' },
  { id: 'install', label: 'Installation', icon: 'settings-outline' },
  { id: 'wiring', label: 'Câblage', icon: 'flash-outline' },
  { id: 'plumbing', label: 'Plomberie', icon: 'water-outline' },
  { id: 'clean', label: 'Ménage', icon: 'sparkles-outline' },
  { id: 'paint', label: 'Peinture', icon: 'color-palette-outline' },
  { id: 'carpentry', label: 'Menuiserie', icon: 'hammer-outline' },
  { id: 'ac', label: 'Climatisation', icon: 'snow-outline' },
];

export const pros: Pro[] = [
  { id: '1', name: 'James Carter', trade: 'Électricien', years: 8, rating: 4.9, reviews: 200, rate: 159, customers: 150,
    about: "Électricien certifié avec 8 ans d'expérience. Spécialisé dans le câblage résidentiel et commercial, de la rénovation complète à la mise aux normes.", color: '#F7931E' },
  { id: '2', name: 'Liam Johnson', trade: 'Plombier', years: 12, rating: 4.7, reviews: 312, rate: 200, customers: 280,
    about: 'Plombier expérimenté : fuites, chauffe-eau, sanitaires et installations neuves. Intervention rapide et devis transparent.', color: '#4C8BF5' },
  { id: '3', name: 'Noah Wilson', trade: 'Réparateur', years: 9, rating: 4.9, reviews: 190, rate: 138, customers: 175,
    about: "Réparation d'appareils électroménagers : lave-linge, réfrigérateur, four, micro-ondes. Pièces d'origine garanties.", color: '#2E9E5B' },
  { id: '4', name: 'Lucas Bennett', trade: 'Menuisier', years: 10, rating: 4.6, reviews: 120, rate: 199, customers: 110,
    about: 'Menuiserie sur mesure, pose de portes, placards et parquets. Travail soigné et finitions irréprochables.', color: '#8E5BD6' },
  { id: '5', name: 'Fatima Abdullahi', trade: 'Peintre', years: 6, rating: 4.5, reviews: 102, rate: 120, customers: 90,
    about: "Peinture intérieure et extérieure, enduits et décoration. Conseils couleurs offerts avec chaque chantier.", color: '#E8590C' },
];

export type Job = {
  id: string; title: string; date: string; time: string; proId: string;
  status: 'active' | 'requested' | 'history';
};

export const jobs: Job[] = [
  { id: 'j1', title: "Installation de climatiseur", date: '30 mai 2026', time: '09:00 – 10:00', proId: '1', status: 'active' },
  { id: 'j2', title: 'Réparation de fuite', date: '02 juin 2026', time: '14:00 – 15:30', proId: '2', status: 'active' },
  { id: 'j3', title: 'Réparation lave-linge', date: '05 juin 2026', time: '11:00 – 12:00', proId: '3', status: 'requested' },
  { id: 'j4', title: 'Pose de parquet', date: '12 avr. 2026', time: '08:00 – 17:00', proId: '4', status: 'history' },
];

export const chats = [
  { id: 'c1', proId: '1', last: "Je serai chez vous demain à 9h.", time: '09:41', unread: 2 },
  { id: 'c2', proId: '2', last: 'Le devis est prêt, je vous l’envoie.', time: 'Hier', unread: 0 },
  { id: 'c3', proId: '3', last: 'Merci pour votre confiance !', time: 'Lun', unread: 0 },
];

export const getPro = (id: string) => pros.find((p) => p.id === id)!;
