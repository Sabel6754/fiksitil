import { Ionicons } from '@expo/vector-icons';
import { ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { Avatar } from '../components/Avatar';
import { IconName } from '../data';
import { colors, radius } from '../theme';

const items: { icon: IconName; label: string }[] = [
  { icon: 'person-outline', label: 'Informations personnelles' },
  { icon: 'location-outline', label: 'Mes adresses' },
  { icon: 'card-outline', label: 'Paiements et transactions' },
  { icon: 'heart-outline', label: 'Favoris' },
  { icon: 'help-circle-outline', label: 'Aide et support' },
  { icon: 'log-out-outline', label: 'Se déconnecter' },
];

export default function ProfileScreen() {
  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: colors.bg }} edges={['top']}>
      <ScrollView contentContainerStyle={{ padding: 20, gap: 10 }}>
        <View style={{ alignItems: 'center', gap: 6, marginBottom: 12 }}>
          <Avatar name="John Doe" color={colors.primary} size={88} />
          <Text style={{ fontSize: 22, fontWeight: '800' }}>John Doe</Text>
          <Text style={{ color: colors.muted }}>john.doe@email.com</Text>
        </View>
        {items.map((i) => (
          <View key={i.label} style={styles.item}>
            <Ionicons name={i.icon} size={22} color={colors.primaryDark} />
            <Text style={{ flex: 1, fontWeight: '600' }}>{i.label}</Text>
            <Ionicons name="chevron-forward" size={18} color={colors.muted} />
          </View>
        ))}
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  item: { flexDirection: 'row', alignItems: 'center', gap: 12, backgroundColor: colors.surface, borderRadius: radius.md, padding: 16, borderWidth: 1, borderColor: colors.border },
});
