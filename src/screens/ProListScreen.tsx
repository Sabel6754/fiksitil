import { ScrollView, Text, View } from 'react-native';
import { ProCard } from '../components/ProCard';
import { pros } from '../data';
import { colors } from '../theme';

export default function ProListScreen({ navigation, route }: any) {
  const category = route.params?.category;
  return (
    <ScrollView style={{ backgroundColor: colors.bg }} contentContainerStyle={{ padding: 20, gap: 12 }}>
      {category ? <Text style={{ color: colors.muted }}>Catégorie : {category}</Text> : null}
      <View style={{ gap: 12 }}>
        {pros.map((p) => <ProCard key={p.id} pro={p} onPress={() => navigation.navigate('ProDetail', { id: p.id })} />)}
      </View>
    </ScrollView>
  );
}
