import { Ionicons } from '@expo/vector-icons';
import { useState } from 'react';
import { Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Avatar } from '../components/Avatar';
import { getPro, jobs, Job } from '../data';
import { colors, radius, shadow } from '../theme';

const tabs: { key: Job['status']; label: string }[] = [
  { key: 'active', label: 'En cours' },
  { key: 'requested', label: 'Demandés' },
  { key: 'history', label: 'Historique' },
];

export default function BookingsScreen({ navigation }: any) {
  const [tab, setTab] = useState<Job['status']>('active');
  const list = jobs.filter((j) => j.status === tab);
  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: colors.bg }} edges={['top']}>
      <ScrollView contentContainerStyle={{ padding: 20, gap: 14 }}>
        <Text style={styles.title}>Mes missions</Text>
        <View style={styles.tabs}>
          {tabs.map((t) => (
            <Pressable key={t.key} onPress={() => setTab(t.key)} style={[styles.tab, tab === t.key && styles.tabOn]}>
              <Text style={{ fontWeight: '600', color: tab === t.key ? colors.primaryDark : colors.muted }}>{t.label}</Text>
            </Pressable>
          ))}
        </View>
        {list.map((j) => {
          const pro = getPro(j.proId);
          return (
            <View key={j.id} style={[styles.card, shadow]}>
              <Text style={styles.job}>{j.title}</Text>
              <Text style={styles.muted}>{j.date} · {j.time}</Text>
              <View style={styles.pro}>
                <Avatar name={pro.name} color={pro.color} size={40} />
                <View style={{ flex: 1 }}>
                  <Text style={{ fontWeight: '700' }}>{pro.name}</Text>
                  <Text style={styles.muted}>★ {pro.rating} ({pro.reviews})</Text>
                </View>
              </View>
              <View style={{ flexDirection: 'row', gap: 10 }}>
                <Pressable style={styles.btnLight} onPress={() => navigation.navigate('Chat')}>
                  <Ionicons name="chatbubble-outline" size={16} /><Text style={{ fontWeight: '600' }}>Message</Text>
                </Pressable>
                <Pressable style={styles.btnDark}>
                  <Ionicons name="call-outline" size={16} color="#fff" /><Text style={{ fontWeight: '600', color: '#fff' }}>Appeler</Text>
                </Pressable>
              </View>
            </View>
          );
        })}
        {list.length === 0 && <Text style={styles.muted}>Aucune mission ici pour le moment.</Text>}
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  title: { fontSize: 26, fontWeight: '800' },
  tabs: { flexDirection: 'row', backgroundColor: colors.surface, borderRadius: radius.md, padding: 4 },
  tab: { flex: 1, alignItems: 'center', paddingVertical: 10, borderRadius: 12 },
  tabOn: { backgroundColor: '#fff' },
  card: { backgroundColor: colors.surface, borderRadius: radius.md, padding: 16, gap: 10, borderWidth: 1, borderColor: colors.border },
  job: { fontSize: 16, fontWeight: '700' },
  muted: { color: colors.muted, fontSize: 13 },
  pro: { flexDirection: 'row', alignItems: 'center', gap: 10, backgroundColor: '#fff', borderRadius: radius.md, padding: 10 },
  btnLight: { flex: 1, flexDirection: 'row', gap: 6, justifyContent: 'center', alignItems: 'center', backgroundColor: '#fff', borderRadius: radius.md, height: 44 },
  btnDark: { flex: 1, flexDirection: 'row', gap: 6, justifyContent: 'center', alignItems: 'center', backgroundColor: '#1B1B1F', borderRadius: radius.md, height: 44 },
});
