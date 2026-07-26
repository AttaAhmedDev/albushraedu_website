import { BrowserRouter, Routes, Route } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import Layout from './components/Layout';
import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import Worksheets from './pages/Worksheets';
import Presentations from './pages/Presentations';
import Games from './pages/Games';
import Flashcards from './pages/Flashcards';
import Admin from './pages/Admin';

export default function App() {
  return (
    <AuthProvider>
      <BrowserRouter basename="/OnlineLearningPlatform">
        <Layout>
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/login" element={<Login />} />
            <Route path="/register" element={<Register />} />
            <Route path="/worksheets/:type" element={<Worksheets />} />
            <Route path="/presentations/:type" element={<Presentations />} />
            <Route path="/games/:subject" element={<Games />} />
            <Route path="/flashcards/:kind" element={<Flashcards />} />
            <Route path="/admin" element={<Admin />} />
          </Routes>
        </Layout>
      </BrowserRouter>
    </AuthProvider>
  );
}
