import { Ionicons } from '@expo/vector-icons';
import { Pressable, StyleSheet, Text, View } from 'react-native';
import { Pro } from '../data';
import { colors, radius, shadow } from '../theme';
import { Avatar } from './Avatar';

export function ProCard({ pro, onPress }: { pro: Pro; onPress: () => void }) {
  return (
    <Pressable onPress={onPress} style={({ pressed }) => [styles.card, shadow, pressed && { opacity: 0.85 }]}>
      <Avatar name={pro.name} color={pro.color} photo={pro.photo} size={64} />
      <View style={{ flex: 1 }}>
        <Text style={styles.trade}>{pro.trade}</Text>
        <Text style={styles.name}>{pro.name}</Text>
        <Text style={styles.muted}>{pro.years} ans d'expérience</Text>
        <View style={styles.row}>
          <Ionicons name="star" size={14} color={colors.primary} />
          <Text style={styles.rating}>{pro.rating} ({pro.reviews})</Text>
        </View>
      </View>
      <View style={styles.price}><Text style={styles.priceText}>{pro.rate} €/h</Text></View>
    </Pressable>
  );
}

const styles = StyleSheet.create({
  card: { flexDirection: 'row', alignItems: 'center', gap: 12, backgroundColor: colors.card, borderRadius: radius.md, padding: 12, borderWidth: 1, borderColor: colors.border },
  trade: { fontSize: 12, color: colors.muted },
  name: { fontSize: 16, fontWeight: '700', color: colors.text },
  muted: { fontSize: 12, color: colors.muted },
  row: { flexDirection: 'row', alignItems: 'center', gap: 4, marginTop: 4 },
  rating: { fontSize: 12, fontWeight: '600', color: colors.text },
  price: { backgroundColor: colors.primary, borderRadius: radius.sm, paddingHorizontal: 10, paddingVertical: 6 },
  priceText: { color: '#fff', fontWeight: '700', fontSize: 12 },
});
