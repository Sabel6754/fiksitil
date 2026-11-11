import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { useState } from 'react';
import { Pressable, ScrollView, StyleSheet, Text, TextInput, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { ProCard } from '../components/ProCard';
import { SectionHeader } from '../components/SectionHeader';
import { categories, pros } from '../data';
import { colors, radius, shadow } from '../theme';

const tradeByCategory: Record<string, string[]> = {
  repair: ['Réparateur'],
  install: ['Électricien', 'Réparateur'],
  wiring: ['Électricien'],
  plumbing: ['Plombier'],
  paint: ['Peintre'],
  carpentry: ['Menuisier'],
};

export default function HomeScreen({ navigation }: any) {
  const [query, setQuery] = useState('');
  const [active, setActive] = useState('all');
  const list = pros.filter(
    (p) =>
      (p.name + p.trade).toLowerCase().includes(query.toLowerCase()) &&
      (active === 'all' || tradeByCategory[active]?.includes(p.trade)),
  );

  return (
    <LinearGradient colors={['#FBE3BD', '#FFFFFF']} locations={[0, 0.35]} style={{ flex: 1 }}>
      <SafeAreaView style={{ flex: 1 }} edges={['top']}>
        <ScrollView contentContainerStyle={styles.content} showsVerticalScrollIndicator={false}>
          <View style={styles.header}>
            <Text style={styles.logo}>Fiksitil <Ionicons name="flash" size={22} color={colors.primary} /></Text>
            <Pressable style={[styles.iconBtn, shadow]}><Ionicons name="notifications-outline" size={22} color={colors.text} /></Pressable>
          </View>

          <View style={[styles.search, shadow]}>
            <Ionicons name="search" size={20} color={colors.text} />
            <TextInput placeholder="De quel service avez-vous besoin ?" placeholderTextColor={colors.muted} style={{ flex: 1 }} value={query} onChangeText={setQuery} />
            <Ionicons name="options-outline" size={20} color={colors.primary} />
          </View>

          <LinearGradient colors={[colors.primary, colors.primaryDark]} start={{ x: 0, y: 0 }} end={{ x: 1, y: 1 }} style={styles.banner}>
            <View style={styles.badge}><Text style={styles.badgeText}>Populaire</Text></View>
            <Text style={styles.bannerTitle}>Trouvez un professionnel</Text>
            <Text style={styles.bannerSub}>Câblage, réparations ou installations ? Réservez en quelques secondes.</Text>
            <Pressable style={styles.bannerBtn} onPress={() => navigation.navigate('ProList')}>
              <Text style={{ fontWeight: '700', color: colors.text }}>Réserver</Text>
            </Pressable>
            <Ionicons name="construct" size={110} color="rgba(255,255,255,0.25)" style={styles.bannerIcon} />
          </LinearGradient>

          <SectionHeader title="Nos services" action="Tout voir" onAction={() => navigation.navigate('Services')} />
          <ScrollView horizontal showsHorizontalScrollIndicator={false} contentContainerStyle={{ gap: 10 }}>
            {[{ id: 'all', label: 'Tous', icon: 'apps-outline' as const }, ...categories].map((c) => {
              const on = c.id === active;
              return (
                <Pressable key={c.id} onPress={() => setActive(c.id)} style={[styles.chip, on && styles.chipOn]}>
                  <Ionicons name={c.icon} size={18} color={on ? '#fff' : colors.text} />
                  <Text style={{ color: on ? '#fff' : colors.text, fontWeight: '600' }}>{c.label}</Text>
                </Pressable>
              );
            })}
          </ScrollView>

          <SectionHeader title="Pros recommandés" action="Tout voir" onAction={() => navigation.navigate('ProList')} />
          <View style={{ gap: 12 }}>
            {list.map((p) => <ProCard key={p.id} pro={p} onPress={() => navigation.navigate('ProDetail', { id: p.id })} />)}
            {list.length === 0 && <Text style={{ color: colors.muted }}>Aucun professionnel pour ce service pour le moment.</Text>}
          </View>
        </ScrollView>
      </SafeAreaView>
    </LinearGradient>
  );
}

const styles = StyleSheet.create({
  content: { padding: 20, paddingBottom: 40 },
  header: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginBottom: 16 },
  logo: { fontSize: 26, fontWeight: '800', color: colors.text },
  iconBtn: { width: 44, height: 44, borderRadius: 14, backgroundColor: '#fff', alignItems: 'center', justifyContent: 'center' },
  search: { flexDirection: 'row', alignItems: 'center', gap: 10, backgroundColor: '#fff', borderRadius: radius.md, paddingHorizontal: 14, height: 52 },
  banner: { borderRadius: radius.lg, padding: 20, marginTop: 20, overflow: 'hidden' },
  badge: { alignSelf: 'flex-start', backgroundColor: '#fff', borderRadius: radius.pill, paddingHorizontal: 10, paddingVertical: 4 },
  badgeText: { fontSize: 12, fontWeight: '600' },
  bannerTitle: { fontSize: 24, fontWeight: '800', color: '#fff', marginTop: 12, width: '70%' },
  bannerSub: { color: '#FFF3E0', marginTop: 6, width: '65%' },
  bannerBtn: { alignSelf: 'flex-start', backgroundColor: '#fff', borderRadius: radius.md, paddingHorizontal: 20, paddingVertical: 10, marginTop: 14 },
  bannerIcon: { position: 'absolute', right: -10, bottom: -10 },
  chip: { flexDirection: 'row', alignItems: 'center', gap: 6, backgroundColor: '#fff', borderRadius: radius.pill, paddingHorizontal: 14, paddingVertical: 10, borderWidth: 1, borderColor: colors.border },
  chipOn: { backgroundColor: colors.primary, borderColor: colors.primary },
});
