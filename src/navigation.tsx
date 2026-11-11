import { Ionicons } from '@expo/vector-icons';
import { NavigationContainer, DefaultTheme } from '@react-navigation/native';
import { createBottomTabNavigator } from '@react-navigation/bottom-tabs';
import { createNativeStackNavigator } from '@react-navigation/native-stack';
import BookingsScreen from './screens/BookingsScreen';
import ChatScreen from './screens/ChatScreen';
import HomeScreen from './screens/HomeScreen';
import ProDetailScreen from './screens/ProDetailScreen';
import ProListScreen from './screens/ProListScreen';
import ProfileScreen from './screens/ProfileScreen';
import ServicesScreen from './screens/ServicesScreen';
import { colors } from './theme';

const Tab = createBottomTabNavigator();
const Stack = createNativeStackNavigator();

const icons: Record<string, [any, any]> = {
  Home: ['home', 'home-outline'],
  Services: ['grid', 'grid-outline'],
  Booking: ['calendar', 'calendar-outline'],
  Chat: ['chatbubbles', 'chatbubbles-outline'],
  Profile: ['person', 'person-outline'],
};

const labels: Record<string, string> = {
  Home: 'Accueil', Services: 'Services', Booking: 'Missions', Chat: 'Messages', Profile: 'Profil',
};

function Tabs() {
  return (
    <Tab.Navigator
      screenOptions={({ route }) => ({
        headerShown: false,
        tabBarActiveTintColor: colors.primary,
        tabBarInactiveTintColor: colors.muted,
        tabBarLabel: labels[route.name],
        tabBarIcon: ({ focused, color, size }) => (
          <Ionicons name={icons[route.name][focused ? 0 : 1]} size={size} color={color} />
        ),
      })}
    >
      <Tab.Screen name="Home" component={HomeScreen} />
      <Tab.Screen name="Services" component={ServicesScreen} />
      <Tab.Screen name="Booking" component={BookingsScreen} />
      <Tab.Screen name="Chat" component={ChatScreen} />
      <Tab.Screen name="Profile" component={ProfileScreen} />
    </Tab.Navigator>
  );
}

export default function Navigation() {
  return (
    <NavigationContainer theme={{ ...DefaultTheme, colors: { ...DefaultTheme.colors, background: colors.bg, primary: colors.primary } }}>
      <Stack.Navigator>
        <Stack.Screen name="Tabs" component={Tabs} options={{ headerShown: false }} />
        <Stack.Screen name="ProList" component={ProListScreen} options={{ title: 'Professionnels' }} />
        <Stack.Screen name="ProDetail" component={ProDetailScreen} options={{ title: 'Profil', headerTransparent: false }} />
      </Stack.Navigator>
    </NavigationContainer>
  );
}
