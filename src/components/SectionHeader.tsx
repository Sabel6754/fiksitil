import { Pressable, StyleSheet, Text, View } from 'react-native';
import { colors } from '../theme';

export function SectionHeader({ title, action, onAction }: { title: string; action?: string; onAction?: () => void }) {
  return (
    <View style={styles.row}>
      <Text style={styles.title}>{title}</Text>
      {action ? <Pressable onPress={onAction}><Text style={styles.action}>{action}</Text></Pressable> : null}
    </View>
  );
}

const styles = StyleSheet.create({
  row: { flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center', marginTop: 24, marginBottom: 12 },
  title: { fontSize: 18, fontWeight: '700', color: colors.text },
  action: { fontSize: 13, fontWeight: '600', color: colors.primaryDark },
});
