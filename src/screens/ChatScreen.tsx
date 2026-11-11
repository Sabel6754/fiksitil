import { ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Avatar } from '../components/Avatar';
import { chats, getPro } from '../data';
import { colors } from '../theme';

export default function ChatScreen() {
  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: colors.bg }} edges={['top']}>
      <ScrollView contentContainerStyle={{ padding: 20, gap: 8 }}>
        <Text style={styles.title}>Messages</Text>
        {chats.map((c) => {
          const pro = getPro(c.proId);
          return (
            <View key={c.id} style={styles.row}>
              <Avatar name={pro.name} color={pro.color} size={52} />
              <View style={{ flex: 1 }}>
                <Text style={{ fontWeight: '700', fontSize: 16 }}>{pro.name}</Text>
                <Text style={{ color: colors.muted }} numberOfLines={1}>{c.last}</Text>
              </View>
              <View style={{ alignItems: 'flex-end', gap: 4 }}>
                <Text style={{ color: colors.muted, fontSize: 12 }}>{c.time}</Text>
                {c.unread > 0 && <View style={styles.badge}><Text style={{ color: '#fff', fontSize: 11, fontWeight: '700' }}>{c.unread}</Text></View>}
              </View>
            </View>
          );
        })}
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  title: { fontSize: 26, fontWeight: '800', marginBottom: 8 },
  row: { flexDirection: 'row', alignItems: 'center', gap: 12, paddingVertical: 10, borderBottomWidth: 1, borderBottomColor: colors.border },
  badge: { backgroundColor: colors.primary, borderRadius: 10, minWidth: 20, height: 20, alignItems: 'center', justifyContent: 'center', paddingHorizontal: 5 },
});
