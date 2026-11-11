import { Image, ImageSourcePropType, Text, View } from 'react-native';

type Props = { name: string; color: string; size?: number; photo?: ImageSourcePropType };

export function Avatar({ name, color, size = 48, photo }: Props) {
  if (photo) {
    return <Image source={photo} accessibilityLabel={name} style={{ width: size, height: size, borderRadius: size / 2, backgroundColor: color }} />;
  }
  const initials = name.split(' ').map((w) => w[0]).slice(0, 2).join('');
  return (
    <View style={{ width: size, height: size, borderRadius: size / 2, backgroundColor: color, alignItems: 'center', justifyContent: 'center' }}>
      <Text style={{ color: '#fff', fontWeight: '700', fontSize: size * 0.36 }}>{initials}</Text>
    </View>
  );
}
