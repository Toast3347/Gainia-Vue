import { defineStore } from 'pinia';
import { ref, computed } from 'vue';
import api from '@/services/api'; 
import router from '@/router';

function getInitialAuthState() {
    let user = null;
    let token = localStorage.getItem('token');

    try {
        const storedUser = localStorage.getItem('user');
        if (storedUser) {
            user = JSON.parse(storedUser);
        }
    } catch (error) {
        console.error("Failed to parse user data from localStorage. Clearing invalid auth state.", error);
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        user = null;
        token = null;
    }

    return { user, token };
}

export const useAuthStore = defineStore('auth', () => {
    const initialState = getInitialAuthState();

    const user = ref(initialState.user);
    const token = ref(initialState.token);
    const message = ref({ text: null, type: null });

    const isAuthenticated = computed(() => !!token.value);

    function setAuth(userData, authToken) {
        user.value = userData;
        token.value = authToken;
        localStorage.setItem('user', JSON.stringify(userData));
        localStorage.setItem('token', authToken);
        message.value = { text: null, type: null };
    }

    function clearAuth() {
        user.value = null;
        token.value = null;
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        router.push('/login');
    }

    function setMessage(text, type) {
        message.value = { text, type };
    }

    function clearMessage() {
        message.value = { text: null, type: null };
    }

    async function login(credentials) {
        try {
            clearMessage();
            const response = await api.post('/login', credentials);
            const { user, token } = response.data;
            setAuth(user, token);
            router.push('/dashboard');
        } catch (error) {
            console.error('Login failed:', error);
            setMessage('Login failed. Please check your credentials.', 'error');
        }
    }

    async function register(credentials) {
        try {
            clearMessage();
            const response = await api.post('/user', credentials);
            
            if (response.data === true) {
                setMessage('Account created successfully! Redirecting to login...', 'success');
                setTimeout(() => {
                    router.push('/login');
                }, 2000);
            } else {
                if (response.status === 400) {
                    setMessage('Registration failed. password is not strong enough.', 'error');
                }
                else {
                    setMessage('An error occurred during registration. The email might already be taken.', 'error');
                }
            }
        } catch (error) {
            console.error('Registration failed:', error);
            if (error.response.status === 400) {
                setMessage('An error occurred during registration. The username might already be taken.', 'error');
            }
            else {
                setMessage('An error occurred during registration. The email might already be taken.', 'error');
            }
        }
    }

    function logout() {
        clearAuth();
    }

    return { user, token, isAuthenticated, message, login, logout, register, clearMessage };
});
