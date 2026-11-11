import { Ionicons } from '@expo/vector-icons';
import { LinearGradient } from 'expo-linear-gradient';
import { useState } from 'react';
import { Alert, Image, Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
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
        <LinearGradient colors={['#FBE3BD', '#FFFAF3']} style={styles.hero}>
          <View style={styles.heroMain}>
            <View style={{ flex: 1, gap: 4 }}>
              <Text style={styles.muted}>🛠 {pro.trade}</Text>
              <Text style={styles.name}>{pro.name}</Text>
              <Text style={styles.muted}>{pro.years} ans d'expérience</Text>
              <View style={styles.pricePill}><Text style={styles.pricePillText}>{pro.rate} €/h</Text></View>
            </View>
            {pro.photo ? (
              <Image source={pro.photo} style={styles.photo} accessibilityLabel={pro.name} />
            ) : (
              <Avatar name={pro.name} color={pro.color} size={150} />
            )}
          </View>
          <View style={styles.stats}>
            <Stat icon="briefcase" value={`${pro.years}+ ans`} label="Expérience" />
            <Stat icon="star" value={String(pro.rating)} label="Note" alt />
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
              <View style={styles.who}>
                <Avatar name={pro.name} color={pro.color} photo={pro.photo} size={48} />
                <View style={{ flex: 1 }}>
                  <Text style={{ fontWeight: '700' }}>{pro.name}</Text>
                  <Text style={styles.muted}>{pro.trade}</Text>
                </View>
                <Pressable style={styles.round} onPress={() => Alert.alert('Message', `Conversation avec ${pro.name} (démo).`)}>
                  <Ionicons name="chatbubble-ellipses-outline" size={20} color={colors.primary} />
                </Pressable>
                <Pressable style={styles.round} onPress={() => Alert.alert('Appel', `Appel de ${pro.name} (démo).`)}>
                  <Ionicons name="call-outline" size={20} color={colors.primary} />
                </Pressable>
              </View>
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

function Stat({ icon, value, label, alt }: { icon: any; value: string; label: string; alt?: boolean }) {
  return (
    <View style={[styles.stat, shadow, alt && { borderColor: '#7A4FD6' }]}>
      <Ionicons name={icon} size={20} color={colors.primary} />
      <Text style={{ fontWeight: '700' }}>{value}</Text>
      <Text style={{ fontSize: 11, color: colors.muted }}>{label}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  hero: { paddingHorizontal: 20, paddingTop: 16, paddingBottom: 20 },
  heroMain: { flexDirection: 'row', alignItems: 'flex-end', gap: 12 },
  photo: { width: 160, height: 190, borderRadius: 28 },
  name: { fontSize: 24, fontWeight: '800', lineHeight: 28 },
  muted: { color: colors.muted, lineHeight: 20 },
  pricePill: { alignSelf: 'flex-start', backgroundColor: colors.primary, borderRadius: radius.sm, paddingHorizontal: 12, paddingVertical: 6, marginTop: 6 },
  pricePillText: { color: '#fff', fontWeight: '700' },
  stats: { flexDirection: 'row', justifyContent: 'space-between', gap: 10, marginTop: 16 },
  stat: { flex: 1, backgroundColor: 'rgba(255,255,255,0.92)', borderRadius: radius.md, paddingVertical: 12, alignItems: 'center', gap: 2, borderWidth: 2, borderColor: '#F7B267' },
  who: { flexDirection: 'row', alignItems: 'center', gap: 10, marginVertical: 4 },
  round: { width: 42, height: 42, borderRadius: 21, borderWidth: 1, borderColor: colors.primary, alignItems: 'center', justifyContent: 'center' },
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
