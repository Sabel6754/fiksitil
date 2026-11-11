import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { useState } from 'react';
import { Alert, Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Avatar } from '../components/Avatar';
import { getPro } from '../data';
import { colors, radius, shadow } from '../theme';

const tabs = ['À propos', 'Disponibilités', 'Avis'];

export default function ProDetailScreen({ route }: any) {
  const pro = getPro(route.params.id);
  const [tab, setTab] = useState(0);
  const [fav, setFav] = useState(false);

  return (
    <View style={{ flex: 1, backgroundColor: colors.bg }}>
      <ScrollView contentContainerStyle={{ paddingBottom: 110 }}>
        <LinearGradient colors={['#FBE3BD', '#FFFFFF']} style={styles.hero}>
          <Avatar name={pro.name} color={pro.color} photo={pro.photo} size={110} />
          <Text style={styles.name}>{pro.name}</Text>
          <Text style={styles.muted}>{pro.trade} · {pro.years} ans d'expérience</Text>
          <View style={styles.stats}>
            <Stat icon="briefcase" value={`${pro.years} ans`} label="Expérience" />
            <Stat icon="star" value={String(pro.rating)} label="Note" />
            <Stat icon="people" value={`${pro.customers}+`} label="Clients" />
          </View>
        </LinearGradient>

        <View style={styles.tabs}>
          {tabs.map((t, i) => (
            <Pressable key={t} onPress={() => setTab(i)} style={[styles.tab, tab === i && styles.tabOn]}>
              <Text style={{ fontWeight: '600', color: tab === i ? colors.text : colors.muted }}>{t}</Text>
            </Pressable>
          ))}
        </View>

        <View style={{ padding: 20, gap: 14 }}>
          {tab === 0 && (
            <>
              <Text style={styles.h}>À propos de {pro.name}</Text>
              <Text style={styles.muted}>{pro.about}</Text>
              <View style={{ flexDirection: 'row', gap: 12 }}>
                <View style={[styles.fee, { flex: 1 }]}><Text style={styles.muted}>Tarif horaire</Text><Text style={styles.feeV}>{pro.rate} €</Text></View>
                <View style={[styles.fee, { flex: 1 }]}><Text style={styles.muted}>Équipe (4-7 h)</Text><Text style={styles.feeV}>{pro.rate * 7} €</Text></View>
              </View>
            </>
          )}
          {tab === 1 && ['Lun – Ven : 08:00 – 18:00', 'Sam : 09:00 – 13:00', 'Dim : indisponible'].map((l) => (
            <View key={l} style={styles.fee}><Text>{l}</Text></View>
          ))}
          {tab === 2 && [['Marie D.', 'Travail propre et ponctuel, je recommande !'], ['Paul R.', 'Très professionnel, prix honnête.']].map(([n, t]) => (
            <View key={n} style={styles.fee}><Text style={{ fontWeight: '700' }}>{n} ★ 5.0</Text><Text style={styles.muted}>{t}</Text></View>
          ))}
        </View>
      </ScrollView>

      <SafeAreaView edges={['bottom']} style={styles.footer}>
        <Pressable style={[styles.cta, shadow]} onPress={() => Alert.alert('Réservation', `Demande envoyée à ${pro.name} (démo).`)}>
          <Ionicons name="calendar-outline" size={20} color="#fff" />
          <Text style={styles.ctaText}>Réserver {pro.name.split(' ')[0]}</Text>
        </Pressable>
        <Pressable style={styles.fav} onPress={() => setFav(!fav)}>
          <Ionicons name={fav ? 'star' : 'star-outline'} size={22} color={colors.primary} />
        </Pressable>
      </SafeAreaView>
    </View>
  );
}

function Stat({ icon, value, label }: { icon: any; value: string; label: string }) {
  return (
    <View style={[styles.stat, shadow]}>
      <Ionicons name={icon} size={20} color={colors.primary} />
      <Text style={{ fontWeight: '700' }}>{value}</Text>
      <Text style={{ fontSize: 11, color: colors.muted }}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  hero: { alignItems: 'center', paddingTop: 20, paddingBottom: 20, gap: 4 },
  name: { fontSize: 22, fontWeight: '800', marginTop: 8 },
  muted: { color: colors.muted, lineHeight: 20 },
  stats: { flexDirection: 'row', gap: 12, marginTop: 16 },
  stat: { backgroundColor: '#fff', borderRadius: radius.md, paddingVertical: 12, width: 96, alignItems: 'center', gap: 2, borderWidth: 1, borderColor: colors.border },
  tabs: { flexDirection: 'row', backgroundColor: '#F3F3F5', borderRadius: radius.md, marginHorizontal: 20, padding: 4 },
  tab: { flex: 1, alignItems: 'center', paddingVertical: 10, borderRadius: 12 },
  tabOn: { backgroundColor: '#fff' },
  h: { fontSize: 18, fontWeight: '700' },
  fee: { backgroundColor: '#fff', borderRadius: radius.md, padding: 14, borderWidth: 1, borderColor: colors.border, gap: 4 },
  feeV: { fontSize: 18, fontWeight: '800' },
  footer: { position: 'absolute', bottom: 0, left: 0, right: 0, flexDirection: 'row', gap: 12, padding: 16, backgroundColor: '#fff' },
  cta: { flex: 1, flexDirection: 'row', gap: 8, justifyContent: 'center', alignItems: 'center', backgroundColor: colors.primary, borderRadius: radius.md, height: 52 },
  ctaText: { color: '#fff', fontWeight: '700', fontSize: 16 },
  fav: { width: 52, height: 52, borderRadius: radius.md, borderWidth: 1, borderColor: colors.primary, alignItems: 'center', justifyContent: 'center' },
});
