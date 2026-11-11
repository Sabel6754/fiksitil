import { Ionicons } from '@expo/vector-icons';
import { Pressable, ScrollView, StyleSheet, Text, View } from 'react-native';
import { SafeAreaView } from 'react-native-safe-area-context';
import { categories } from '../data';
import { colors, radius, shadow } from '../theme';

export default function ServicesScreen({ navigation }: any) {
  return (
    <SafeAreaView style={{ flex: 1, backgroundColor: colors.bg }} edges={['top']}>
      <ScrollView contentContainerStyle={{ padding: 20 }}>
        <Text style={styles.title}>Services</Text>
        <View style={styles.grid}>
          {categories.map((c) => (
            <Pressable key={c.id} style={[styles.tile, shadow]} onPress={() => navigation.navigate('ProList', { category: c.label })}>
              <View style={styles.icon}><Ionicons name={c.icon} size={26} color={colors.primaryDark} /></View>
              <Text style={styles.label}>{c.label}</Text>
            </Pressable>
          ))}
        </View>
      </ScrollView>
    </SafeAreaView>
  );
}

const styles = StyleSheet.create({
  title: { fontSize: 26, fontWeight: '800', marginBottom: 16, color: colors.text },
  grid: { flexDirection: 'row', flexWrap: 'wrap', gap: 12 },
  tile: { width: '47.5%', backgroundColor: '#fff', borderRadius: radius.md, padding: 16, alignItems: 'center', gap: 10, borderWidth: 1, borderColor: colors.border },
  icon: { width: 56, height: 56, borderRadius: 18, backgroundColor: colors.primarySoft, alignItems: 'center', justifyContent: 'center' },
  label: { fontWeight: '600', color: colors.text },
});
