import { createContext, useContext, useEffect, useState } from 'react';
import { api } from '../api/client';

const AuthContext = createContext(null);

export function AuthProvider({ children }) {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  const refresh = async () => {
    try {
      const data = await api('auth/me');
      setUser(data.user);
    } catch {
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    refresh();
  }, []);

  const login = async (payload) => {
    const data = await api('auth/login', { method: 'POST', body: payload });
    setUser(data.user);
    return data.user;
  };

  const register = async (payload) => {
    const data = await api('auth/register', { method: 'POST', body: payload });
    setUser(data.user);
    return data.user;
  };

  const logout = async () => {
    await api('auth/logout', { method: 'POST', body: {} });
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout, refresh, isAdmin: user?.role === 'admin' }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  return useContext(AuthContext);
}
